<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ClassEnum;
use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassInfo;
use App\Services\InstituteService;
use App\Services\StudentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPromotionController extends Controller
{
    private $studentService;
    private $instituteService;

    public function __construct(StudentService $studentService, InstituteService $instituteService)
    {
        $this->studentService = $studentService;
        $this->instituteService = $instituteService;
    }

    public function studentPromote(Request $request)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();
            return view('frontend/noipunno/student-add/promotion/promote', compact('branchs', 'classList', 'institute'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentPromoteList(Request $request)
    {
        $request->validate(
            [
                'branch' => 'required',
                'shift' => 'required',
                'version' => 'required',
                'class' => 'required',
                'section' => 'required',
            ],
            [
                'branch.required' => 'ব্রাঞ্চের তথ্য নির্বাচন করুন',
                'shift.required' => 'শিফট নির্বাচন করুন',
                'version.required' => 'ভার্সন নির্বাচন করুন',
                'class.required' => 'শ্রেণি নির্বাচন করুন',
                'section.required' => 'সেকশন নির্বাচন করুন',
            ]
        );
        try {
            $request_data = $request->all();
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();
            if ($request_data) {
                $students = $this->studentService->getStudentListByAcademicDetails($request_data, $eiinId, 2023);
            } else {
                $students = [];
            }
            return view('frontend/noipunno/student-add/promotion/promote-list', compact('branchs', 'classList', 'institute', 'request_data', 'students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function studentPromotedList(Request $request)
    {
        try {
            $request->validate(
                [
                    'checkedStudents' => 'required',
                ],
                [
                    'checkedStudents.required' => 'কোন শিক্ষার্থী সিলেক্ট করা হয়নি।',
                ]
            );
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();
            foreach ($request->checkedStudents as $student_uid) {
                $students[] = $this->studentService->getStudentInfoByUid($student_uid);
            }
            return view('frontend/noipunno/student-add/promotion/promoted-list', compact('branchs', 'classList', 'institute', 'students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentPromoteStore(Request $request)
    {
        $request->validate(
            [
                'branch_uid' => 'required',
                'shift_uid' => 'required',
                'version_uid' => 'required',
                'class_uid' => 'required',
                'section_uid' => 'required',
                // 'roll.*' => 'required',
            ],
            [
                'branch_uid.required' => 'ব্রাঞ্চের তথ্য নির্বাচন করুন',
                'shift_uid.required' => 'শিফট নির্বাচন করুন',
                'version_uid.required' => 'ভার্সন নির্বাচন করুন',
                'class_uid.required' => 'শ্রেণি নির্বাচন করুন',
                'section_uid.required' => 'সেকশন নির্বাচন করুন',
                // 'roll.required' => 'রোল নম্বর প্রদান করুন',
            ]
        );
        DB::beginTransaction();
        try {

            $class_room = ClassRoom::where('branch_id', $request->branch_uid)
                ->where('version_id', $request->version_uid)
                ->where('shift_id', $request->shift_uid)
                ->where('class_id', $request->class_uid)
                ->where('section_id', $request->section_uid)
                ->where('session_year', date('Y'))
                ->first();
            if (!$class_room) {
                $class_room = new ClassRoom();

                $class_room->eiin = app('sso-auth')->user()->eiin;
                $class_room->class_id = $request->class_uid;
                $class_room->section_id = $request->section_uid;
                $class_room->session_year = date('Y');
                $class_room->branch_id = $request->branch_uid;
                $class_room->shift_id = $request->shift_uid;
                $class_room->version_id = $request->version_uid;
                $class_room->status = 1;
                $class_room->save();
            }

            foreach ($request->roll as $student_uid => $roll) {
                if (!$roll) {
                    DB::rollBack();
                    $notification = array(
                        'message' => 'অবশ্যই শিক্ষার্থীর রোল নম্বর প্রদান করতে হবে।',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }
                $roll_exixt = StudentClassInfo::where('class_room_uid', $class_room->uid)->where('roll', $roll)->where('session_year', date('Y'))->first();
                if ($roll_exixt) {
                    DB::rollBack();
                    $notification = array(
                        'message' => 'এই সেকশনে এই রোল নম্বরের শিক্ষার্থী ইতিমধ্যেই বিদ্যমান।',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }
                $student_class_info = new StudentClassInfo();
                $student_class_info->student_uid = $student_uid;
                $student_class_info->roll = $roll;
                $student_class_info->class_room_uid = $class_room->uid;
                $student_class_info->session_year = date('Y');
                $student_class_info->save();

                $student = Student::where('uid', $student_uid)->first();
                $student->incremental_no = '1';
                $student->save();
            }
            $notification = array(
                'message' => 'শিক্ষার্থীদের সফলভাবে নতুন শ্রেণিতে উন্নীত হয়েছে।',
                'alert-type' => 'success'
            );
            DB::commit();
            return redirect()->route('student.index')->with($notification);
        } catch (Exception $e) {
            DB::rollBack();
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentPromoteStore_old(Request $request)
    {
        try {
            $request->validate(
                [
                    'checkedStudents' => 'required',
                    'branch_uid' => 'required',
                    'shift_uid' => 'required',
                    'version_uid' => 'required',
                    'class_uid' => 'required',
                    'section_uid' => 'required',
                ],
                [
                    'checkedStudents.required' => 'কোন শিক্ষার্থী সিলেক্ট করা হয়নি।',
                    'branch_uid.required' => 'ব্রাঞ্চের তথ্য নির্বাচন করুন',
                    'shift_uid.required' => 'শিফট নির্বাচন করুন',
                    'version_uid.required' => 'ভার্সন নির্বাচন করুন',
                    'class_uid.required' => 'ক্লাস নির্বাচন করুন',
                    'section_uid.required' => 'সেকশন নির্বাচন করুন',
                ]
            );

            $class_room = ClassRoom::where('branch_id', $request->branch_uid)
                ->where('version_id', $request->version_uid)
                ->where('shift_id', $request->shift_uid)
                ->where('class_id', $request->class_uid)
                ->where('section_id', $request->section_uid)
                ->where('session_year', date('Y'))
                ->first();
            if (!$class_room) {
                $class_room = new ClassRoom();

                $class_room->eiin = app('sso-auth')->user()->eiin;
                $class_room->class_id = $request->class_uid;
                $class_room->section_id = $request->section_uid;
                $class_room->session_year = date('Y');
                $class_room->branch_id = $request->branch_uid;
                $class_room->shift_id = $request->shift_uid;
                $class_room->version_id = $request->version_uid;
                $class_room->status = 1;
                $class_room->save();
            }

            foreach ($request->checkedStudents as $student_id) {
                $student_class_info = new StudentClassInfo();
                $student_class_info->student_uid = $student_id;
                $student_class_info->roll = array_search($student_id, array_keys($request->roll));
                $student_class_info->class_room_uid = $class_room->uid;
                $student_class_info->session_year = date('Y');
                $student_class_info->save();

                $student = Student::where('uid', $student_id)->first();
                $student->incremental_no = '1';
                $student->save();
            }
            $notification = array(
                'message' => 'Student Promoted Successfully.',
                'alert-type' => 'success'
            );
            return back()->with($notification);
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
}

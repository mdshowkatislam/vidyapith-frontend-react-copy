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

class StudentSectionChangeController extends Controller
{
    private $studentService;
    private $instituteService;

    public function __construct(StudentService $studentService, InstituteService $instituteService)
    {
        $this->studentService = $studentService;
        $this->instituteService = $instituteService;
    }

    public function studentSectionChange(Request $request)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();
            return view('frontend/noipunno/student-add/section-change/section-change', compact('branchs', 'classList', 'institute'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentSectionChangeList(Request $request)
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
                // $students = $this->studentService->getStudentListByAcademicDetails($request_data, $eiinId, date('Y') - 1);
                $students = StudentClassInfo::with(['classRoom', 'classRoom.section', 'studentInfo'])
                    ->whereHas('classRoom', function ($query) use ($eiinId, $request_data) {
                        if (!empty($eiinId)) {
                            $query->where('eiin', $eiinId);
                        }
                        if (!empty($request_data['shift'])) {
                            $query->where('shift_id', $request_data['shift']);
                        }
                        if (!empty($request_data['version'])) {
                            $query->where('version_id', $request_data['version']);
                        }
                        if (!empty($request_data['branch'])) {
                            $query->where('branch_id', $request_data['branch']);
                        }
                        if (!empty($request_data['class'])) {
                            $query->where('class_id', $request_data['class']);
                        }
                        if (!empty($request_data['section'])) {
                            $query->where('section_id', $request_data['section']);
                        }
                        // $query->orderBy('class_id', 'desc');
                    })
                    ->join('class_rooms', 'student_class_infos.class_room_uid', '=', 'class_rooms.uid')
                    ->orderBy('class_rooms.class_id', 'asc')
                    ->orderBy('roll', 'asc')
                    ->get();
            } else {
                $students = [];
            }
            // dd($students);
            return view('frontend/noipunno/student-add/section-change/section-change-list', compact('branchs', 'classList', 'institute', 'request_data', 'students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function studentSectionChangedList(Request $request)
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
                $students[] = $this->studentService->getClassInfoByUid($student_uid);
            }
            return view('frontend/noipunno/student-add/section-change/section-changed-list', compact('branchs', 'classList', 'institute', 'students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function studentSectionChangeStore(Request $request)
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

                $class_room->eiin = auth()->user()->eiin;
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

                $student_class_info = StudentClassInfo::where('student_uid', $student_uid)->first();
                $student_class_info->student_uid = $student_uid;
                $student_class_info->roll = $roll;
                $student_class_info->class_room_uid = $class_room->uid;
                $student_class_info->session_year = date('Y');
                $student_class_info->save();
            }
            $notification = array(
                'message' => 'শিক্ষার্থীদের সফলভাবে নতুন সেকশনে যুক্ত করা হয়েছে।',
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
}

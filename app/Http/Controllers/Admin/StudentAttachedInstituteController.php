<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ClassEnum;
use App\Http\Controllers\Controller;
use App\Models\AttachedStudent;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassInfo;
use App\Services\InstituteService;
use App\Services\StudentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAttachedInstituteController extends Controller
{
    private $studentService;
    private $instituteService;

    public function __construct(StudentService $studentService, InstituteService $instituteService)
    {
        $this->studentService = $studentService;
        $this->instituteService = $instituteService;
    }

    public function studentAttachedInstitute(Request $request)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();
            return view('frontend/noipunno/student-add/attached-institute/attached-institute', compact('branchs', 'classList', 'institute'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentAttachedInstituteList(Request $request)
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
            return view('frontend/noipunno/student-add/attached-institute/attached-institute-list', compact('branchs', 'classList', 'institute', 'request_data', 'students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function studentAttachedInstituteDoneList(Request $request)
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
            $institute = $this->instituteService->getByEiinId($eiinId);
            // $classList = ClassEnum::values();
            foreach ($request->checkedStudents as $student_uid) {
                $students[] = $this->studentService->getClassInfoByUid($student_uid);
            }
            return view('frontend/noipunno/student-add/attached-institute/attached-institute-d-list', compact('institute', 'students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function studentAttachedInstituteStore(Request $request)
    {
        $request->validate(
            [
                'eiin' => 'required',
            ],
            [
                'eiin.required' => 'প্রতিষ্ঠান নির্বাচন করুন',
            ]
        );
        DB::beginTransaction();
        try {

            // $class_room = ClassRoom::where('branch_id', $request->branch_uid)
            //     ->where('eiin', $request->eiin)
            //     ->where('version_id', $request->version_uid)
            //     ->where('shift_id', $request->shift_uid)
            //     ->where('class_id', $request->class_uid)
            //     ->where('section_id', $request->section_uid)
            //     ->where('session_year', date('Y'))
            //     ->first();

            // if (!$class_room) {
            //     $class_room = new ClassRoom();

            //     $class_room->eiin = $request->eiin;
            //     $class_room->class_id = $request->class_uid;
            //     $class_room->section_id = $request->section_uid;
            //     $class_room->session_year = date('Y');
            //     $class_room->branch_id = $request->branch_uid;
            //     $class_room->shift_id = $request->shift_uid;
            //     $class_room->version_id = $request->version_uid;
            //     $class_room->status = 1;
            //     $class_room->save();
            // }

            foreach ($request->roll as $student_uid => $roll) {
                if (!$roll) {
                    DB::rollBack();
                    $notification = array(
                        'message' => 'অবশ্যই শিক্ষার্থীর রোল নম্বর প্রদান করতে হবে।',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }
                $roll_exists = AttachedStudent::where('student_uid', $student_uid)
                            ->where('old_class_room_uid', $request->old_class_room_uid)
                            ->where('roll', $roll)
                            ->where('session_year', date('Y'))
                            ->first();
                if ($roll_exists) {
                    DB::rollBack();
                    $notification = array(
                        'message' => 'এই শিক্ষার্থীকে ইতিমধ্যে প্রতিষ্ঠানে সংযুক্ত করা হয়েছে।',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }

                $attached_student_info = new AttachedStudent();

                $attached_student_info->student_uid = $student_uid;
                $attached_student_info->roll = $roll;
                $attached_student_info->attached_eiin = $request->eiin;
                $attached_student_info->old_class_room_uid = $request->old_class_room_uid;
                $attached_student_info->approve_status = 0;
                $attached_student_info->session_year = date('Y');
                $attached_student_info->save();
            }
            $notification = array(
                'message' => 'শিক্ষার্থীদের সফলভাবে নতুন প্রতিষ্ঠানে সংযুক্তির আবেদন করা হয়েছে।',
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

    public function studentAttachedInstituteRequestList(Request $request)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $requests = AttachedStudent::with(['oldClassRoom'])
                ->where('attached_eiin', $eiinId)
                ->where('approve_status', 0)
                ->get()
                ->groupBy(function ($item) {
                    return $item->oldClassRoom->eiin;
                })->map(function ($group) {
                    return $group->groupBy(function ($item) {
                        return $item->oldClassRoom->class_id;
                    });
                });
            return view('frontend/noipunno/student-add/attached-institute/attached-institute-request-list', compact('requests'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentAttachedInstituteRequestStudentList(Request $request, $eiin, $class)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();

            $students = AttachedStudent::with(['oldClassRoom', 'studentInfo'])
                ->where('attached_eiin', $eiinId)
                ->where('approve_status', 0)
                ->whereHas('oldClassRoom', function ($query) use ($eiin, $class) {
                    if (!empty($eiin)) {
                        $query->where('eiin', $eiin);
                    }
                    if (!empty($class)) {
                        $query->where('class_id', $class);
                    }
                })
                ->join('class_rooms', 'attached_students.old_class_room_uid', '=', 'class_rooms.uid')
                ->orderBy('class_rooms.class_id', 'asc')
                ->orderBy('roll', 'asc')
                ->get();

            return view('frontend/noipunno/student-add/attached-institute/attached-institute-request-student-list', compact('branchs', 'classList', 'institute', 'students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentAttachedInstituteRequestedStudentList(Request $request)
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
                $students[] = AttachedStudent::with(['oldClassRoom', 'studentInfo'])
                ->where('attached_eiin', $eiinId)
                ->where('student_uid', $student_uid)
                ->first();
            }
            return view('frontend/noipunno/student-add/attached-institute/attached-institute-requested-student-list', compact('branchs', 'classList', 'institute', 'students'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function studentAttachedInstituteRequestStore(Request $request)
    {
        $request->validate(
            [
                'branch_uid' => 'required',
                'shift_uid' => 'required',
                'version_uid' => 'required',
                'class_uid' => 'required',
                'section_uid' => 'required'
            ],
            [
                'branch.required' => 'ব্রাঞ্চের তথ্য নির্বাচন করুন',
                'shift.required' => 'শিফট নির্বাচন করুন',
                'version.required' => 'ভার্সন নির্বাচন করুন',
                'class.required' => 'শ্রেণি নির্বাচন করুন',
                'section.required' => 'সেকশন নির্বাচন করুন'
            ]
        );
        DB::beginTransaction();
        try {

            $class_room = ClassRoom::where('branch_id', $request->branch_uid)
                ->where('eiin', $request->eiin)
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
                $attached_student_info = AttachedStudent::where('student_uid', $student_uid)->where('attached_eiin', $class_room->eiin)->where('session_year', date('Y'))->first();

                if (!$attached_student_info) {
                    DB::rollBack();
                    $notification = array(
                        'message' => 'শিক্ষার্থী খুঁজে পাওয়া যায় নি।',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }
                $attached_student_info->roll = $roll;
                $attached_student_info->class_room_uid = $class_room->uid;
                $attached_student_info->approve_status = 1;
                $attached_student_info->save();

                $student_data = Student::where('uid', $student_uid)->first();
                $student_data->attached_eiin = $class_room->eiin;
                $student_data->save();
            }
            $notification = array(
                'message' => 'শিক্ষার্থীদের সফলভাবে নতুন প্রতিষ্ঠানে যুক্ত করা হয়েছে।',
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

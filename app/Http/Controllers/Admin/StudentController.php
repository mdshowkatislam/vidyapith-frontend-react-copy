<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FaildDataExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Helper\ClassEnum;
use App\Helper\UtilsCookie;
use App\Imports\StudentBulkImport;
use App\Jobs\ImportStudentsJob;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentClassInfo;
use App\Services\ClassService;
use App\Services\StudentService;
use App\Services\Api\AuthService;
use App\Services\ClassRoomService\ClassRoomService;
use App\Services\DivisionService;
use App\Services\InstituteService;
use App\Services\TestStudentBulkService;
use Exception;
use Auth;
use File;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    private $classService;
    private $studentService;
    private $authService;
    private $instituteService;
    private $testStudentBulkService;
    private $classRoomService;
    private $divisionService;

    public function __construct(StudentService $studentService, AuthService $authService, ClassService $classService, InstituteService $instituteService, TestStudentBulkService $testStudentBulkService, ClassRoomService $classRoomService, DivisionService $divisionService)
    {
        $this->classService = $classService;
        $this->studentService = $studentService;
        $this->authService = $authService;
        $this->instituteService = $instituteService;
        $this->testStudentBulkService = $testStudentBulkService;
        $this->classRoomService  = $classRoomService;
        $this->divisionService  = $divisionService;
    }

    public function index(Request $request)
    {
        $value = $request->session()->get('active_tab') ?? 'tab2';
        $request->session()->put('active_tab', $value);
        $search = $request->search;
        $request_data = @$request->all() ?? [];

        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            // $students = $this->studentService->getByEiinId($eiinId, '', $search);

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
                ->whereHas('studentInfo', function ($query) use ($search) {
                    if ($search) {
                        $query->where('student_name_en', 'like', '%' . $search . '%')
                            ->orWhere('student_name_bn', 'like', '%' . $search . '%')
                            ->orWhereHas('student_class_info', function ($query_roll) use ($search) {
                                $query_roll->where('roll', 'like', $search);
                            });
                    }
                })
                // ->orderByRaw('CAST(roll AS SIGNED INTEGER) ASC')
                ->join('class_rooms', 'student_class_infos.class_room_uid', '=', 'class_rooms.uid')
                ->orderBy('class_rooms.class_id', 'asc')
                ->orderBy('roll', 'asc')
                ->paginate(40);

            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();

            return view('frontend/noipunno/student-add/index', compact('students', 'branchs', 'classList', 'institute', 'search', 'request_data'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                // 'message' => 'শিক্ষার্থীর তথ্য পাওয়া যায় নি।',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function add(Request $request)
    {
        $value = $request->session()->get('active_tab') ?? 'tab2';
        $request->session()->put('active_tab', $value);
        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->instituteService->getByEiinId($eiinId, 1);
            $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
            $classList = ClassEnum::values();
            $divisions = $this->divisionService->list();

            return view('frontend/noipunno/student-add/add', compact('branchs', 'classList', 'institute', 'divisions'));
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function store(Request $request)
    {

        $request->session()->forget('active_tab');
        $request->session()->put('active_tab', 'tab1');




        $request->validate(
            [
                'branch'                    => 'required',
                'shift'                     => 'required',
                'version'                   => 'required',
                'class'                     => 'required',
                'section'                   => 'required',
                'registration_year'         => 'required',
                'roll'                      => 'required',
                'student_name_en'           => 'required',
                'student_name_bn'           => 'nullable',
                'brid'                      => 'nullable',
                'date_of_birth'             => 'nullable',
                'gender'                    => 'required',
                'religion'                  => 'required',
                'student_mobile_no'         => 'nullable',
                'mother_name_bn'            => 'nullable',
                'mother_name_en'            => 'nullable',
                'father_name_bn'            => 'nullable',
                'father_name_en'            => 'required',
                'father_mobile_no'          => 'nullable',
                'mother_mobile_no'          => 'nullable',
                'guardian_mobile_no'        => 'nullable',
                'image'                     => 'mimes:jpeg,png,jpg,gif|max:100',
                'br_file'                   => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:100',
                'disability_file'           => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:100',
            ],
            [
                'branch.required'           => 'ব্রাঞ্চ নির্বাচন করুন',
                'shift.required'            => 'শিফট নির্বাচন করুন',
                'version.required'          => 'ভার্সন নির্বাচন করুন',
                'class.required'            => 'শ্রেণি নির্বাচন করুন',
                'section.required'          => 'সেকশন নির্বাচন করুন',
                'registration_year.required' => 'সন প্রদান করুন',
                'roll.required'             => 'রোল নম্বর প্রদান করুন',
                'gender.required'           => 'লিঙ্গ নির্বাচন করুন',
                'religion.required'         => 'ধর্ম নির্বাচন করুন',
                'student_name_en.required'  => 'শিক্ষার্থীর নাম (ইংরেজি) প্রদান করুন',
                'father_name_en.required'   => 'পিতার নাম (ইংরেজি) প্রদান করুন',
                'image.mimes'               => 'ছবি শুধুমাত্র JPG, JPEG, PNG হবে',
                'image.max'                 => 'ছবি এর সাইজ সর্বোচ্চ হবে 100 KB',
                'br_file.mimes'             => 'জন্ম নিবন্ধন ফাইল শুধুমাত্র JPG, JPEG, PNG হবে',
                'br_file.max'               => 'জন্ম নিবন্ধন ফাইল এর সাইজ সর্বোচ্চ হবে 100 KB',
                'disability_file.mimes'     => 'ডিসএবিলিটি ফাইল শুধুমাত্র JPG, JPEG, PNG হবে',
                'disability_file.max'       => 'ডিসএবিলিটি ফাইল এর সাইজ সর্বোচ্চ হবে 100 KB',
            ]
        );



        try {
            $class_room_payload = [
                'eiin' => auth()->user()->eiin,
                'branch' => @$request->branch,
                'shift' => @$request->shift,
                'version' => @$request->version,
                'class' => @$request->class,
                'section' => @$request->section,
                'session_year' => date('Y')
            ];


            $class_room = $this->classRoomService->findOrCreateClassRoom($class_room_payload);
            $roll_exists = $this->studentService->isRollExists($class_room->uid, $request->roll);

            // $findByTrash = $this->studentService->getWithTrashedById($request->all(), auth()->user()->eiin);
            if ($roll_exists) {
                $notification = array(
                    'message' => 'এই সেকশনে এই রোল নম্বরের শিক্ষার্থী ইতিমধ্যেই বিদ্যমান।',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            } else {
                $request['eiin'] = auth()->user()->eiin;
                $this->studentService->create($request->all(), $class_room->uid);
                $notification = array(
                    'message' => 'শিক্ষার্থী সফলভাবে যুক্ত করা হয়েছে।',
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);
                // $authRequest = $this->authService->student($request->all());
                // if (@$authRequest->status == true) {
                //     $authData = (object) $authRequest->data;
                //     $request['caid'] = $authData->caid;
                //     $request['eiin'] = $authData->eiin;
                //     $this->studentService->create($request->all());
                //     $notification = array(
                //         'message' => 'Student Inserted successfully.',
                //         'alert-type' => 'success'
                //     );
                //     return redirect()->back()->with($notification);
                // } else {
                //     $notification = array(
                //         'message' => 'Student Inserted failed.',
                //         'alert-type' => 'error'
                //     );
                //     return back()->with($notification);
                // }
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => 'শিক্ষার্থীর তথ্য যুক্ত করা যায় নি।',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }


    public function edit($id)
    {
        $eiinId = auth()->user()->eiin;
        $institute = $this->instituteService->getByEiinId($eiinId, 1);
        $student = $this->studentService->getByUId($id);
        $student_class_info = StudentClassInfo::where('student_uid', $id)->first();
        $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
        $divisions = $this->divisionService->list(1);
        $classList = ClassEnum::values();
        return view('frontend/noipunno/student-add/edit', compact('student', 'branchs', 'classList', 'institute', 'student_class_info', 'divisions'));
    }

    public function print($id)
    {
        $eiinId = auth()->user()->eiin;
        $institute = $this->instituteService->getByEiinId($eiinId, 1);
        $student = $this->studentService->getByUId($id);
        $student_class_info = StudentClassInfo::where('student_uid', $id)->first();
        $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
        $divisions = $this->divisionService->list(1);
        $classList = ClassEnum::values();
        return view('frontend/noipunno/student-add/print', compact('student', 'branchs', 'classList', 'institute', 'student_class_info', 'divisions'));
    }

    //For board temporary list edit
    public function edit_board_reg($id)
    {
        $eiinId = auth()->user()->eiin;
        $institute = $this->instituteService->getByEiinId($eiinId, 1);
        $student = $this->studentService->getByUId($id);

        $student_class_info = StudentClassInfo::where('student_uid', $id)->first();

        $branchs = $this->studentService->getBranchByEiinId($eiinId, 1);
        $divisions = $this->divisionService->list(1);
        $classList = ClassEnum::values();
        return view('frontend/noipunno/student-add/edit-student-reg', compact('student', 'branchs', 'classList', 'institute', 'student_class_info', 'divisions'));
    }
     //For board temporary list edit

    public function update(Request $request, $id)
    {




        try {
            $request->validate(
                [
                    'branch'                    => 'required',
                    'shift'                     => 'required',
                    'version'                   => 'required',
                    'class'                     => 'required',
                    'section'                   => 'required',
                    'registration_year'         => 'required',
                    'roll'                      => 'required',
                    'student_name_en'           => 'required',
                    'student_name_bn'           => 'nullable',
                    'brid'                      => 'nullable',
                    'date_of_birth'             => 'nullable',
                    'gender'                    => 'required',
                    'religion'                  => 'required',
                    'student_mobile_no'         => 'nullable',
                    'mother_name_bn'            => 'nullable',
                    'mother_name_en'            => 'nullable',
                    'father_name_bn'            => 'nullable',
                    'father_name_en'            => 'required',
                    'father_mobile_no'          => 'nullable',
                    'mother_mobile_no'          => 'nullable',
                    'guardian_mobile_no'        => 'nullable',
                    'image'                     => 'mimes:jpeg,png,jpg,gif|max:100',
                    'br_file'                   => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:100',
                    'disability_file'           => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:100',
                ],
                [
                    'branch.required'           => 'ব্রাঞ্চ নির্বাচন করুন',
                    'shift.required'            => 'শিফট নির্বাচন করুন',
                    'version.required'          => 'ভার্সন নির্বাচন করুন',
                    'class.required'            => 'ক্লাস নির্বাচন করুন',
                    'section.required'          => 'সেকশন নির্বাচন করুন',
                    'registration_year.required' => 'সন প্রদান করুন',
                    'roll.required'             => 'রোল নম্বর প্রদান করুন',
                    'gender.required'           => 'লিঙ্গ নির্বাচন করুন',
                    'religion.required'         => 'ধর্ম নির্বাচন করুন',
                    'student_name_en.required'  => 'শিক্ষার্থীর নাম (ইংরেজি) প্রদান করুন',
                    'father_name_en.required'   => 'পিতার নাম (ইংরেজি) প্রদান করুন',
                    'image.mimes'               => 'ছবি শুধুমাত্র JPG, JPEG, PNG হবে',
                    'image.max'                 => 'ছবি এর সাইজ সর্বোচ্চ হবে 100 KB',
                    'br_file.mimes'             => 'জন্ম নিবন্ধন ফাইল শুধুমাত্র JPG, JPEG, PNG হবে',
                    'br_file.max'               => 'জন্ম নিবন্ধন ফাইল এর সাইজ সর্বোচ্চ হবে 100 KB',
                    'disability_file.mimes'     => 'ডিসএবিলিটি ফাইল শুধুমাত্র JPG, JPEG, PNG হবে',
                    'disability_file.max'       => 'ডিসএবিলিটি ফাইল এর সাইজ সর্বোচ্চ হবে 100 KB',
                    // 'student_name_bn.required' => 'শিক্ষার্থীর নাম (বাংলা) প্রদান করুন',
                    // 'father_mobile_no.required' => 'পিতার মোবাইল নাম্বার প্রদান করুন',
                ]
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        try {
            $class_room_payload = [
                'eiin' => auth()->user()->eiin,
                'branch' => @$request->branch,
                'shift' => @$request->shift,
                'version' => @$request->version,
                'class' => @$request->class,
                'section' => @$request->section,
                'session_year' => date('Y')
            ];
            $class_room = $this->classRoomService->findOrCreateClassRoom($class_room_payload);
            $result = $this->studentService->isRollExists($class_room->uid, $request->roll);
            // $result = $this->studentService->checkRollExists($id, $request->roll);
            if ($result && $result->student_uid != $id) {
                $notification = array(
                    'message' => 'এই সেকশনে এই রোল নম্বরের শিক্ষার্থী ইতিমধ্যেই বিদ্যমান।',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            } else {
                $this->studentService->update($request, $id, $class_room->uid);
                $notification = array(
                    'message' => 'শিক্ষার্থীর তথ্য সফলভাবে আপডেট করা হয়েছে।',
                    'alert-type' => 'success'
                );
                return redirect()->route('student.index')->with($notification);

                // $authRequest = $this->authService->accountUpdate($request->all(), $id, auth()->user()->eiin, 1, 1);
                // if (@$authRequest->status == true) {
                //     $this->studentService->update($request->all(), $id);
                //     $notification = array(
                //         'message' => 'Student Updated successfully.',
                //         'alert-type' => 'success'
                //     );
                //     return redirect()->route('student.index')->with($notification);
                // } else {
                //     $notification = array(
                //         'message' => 'Student Updated failed.',
                //         'alert-type' => 'error'
                //     );
                //     return back()->with($notification);
                // }
            }
        } catch (Exception $e) {
            dd($e);
            $notification = array(
                'message' => 'শিক্ষার্থীর তথ্য আপডেট করা যায় নি।',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }
    public function update_reg(Request $request, $id)
    {


        try {
            $request->validate(
                [
                    'branch'                    => 'required',
                    'shift'                     => 'required',
                    'version'                   => 'required',
                    'class'                     => 'required',
                    'section'                   => 'required',
                    'registration_year'         => 'required',
                    'roll'                      => 'required',
                    'student_name_en'           => 'required',
                    'student_name_bn'           => 'nullable',
                    'brid'                      => 'nullable',
                    'date_of_birth'             => 'nullable',
                    'gender'                    => 'required',
                    'religion'                  => 'required',
                    'student_mobile_no'         => 'nullable',
                    'mother_name_bn'            => 'nullable',
                    'mother_name_en'            => 'nullable',
                    'father_name_bn'            => 'nullable',
                    'father_name_en'            => 'required',
                    'father_mobile_no'          => 'nullable',
                    'mother_mobile_no'          => 'nullable',
                    'guardian_mobile_no'        => 'nullable',
                    'image'                     => 'mimes:jpeg,png,jpg,gif|max:100',
                    'br_file'                   => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:100',
                    'disability_file'           => 'mimes:jpeg,png,jpg,pdf,doc,docx|max:100',
                ],
                [
                    'branch.required'           => 'ব্রাঞ্চ নির্বাচন করুন',
                    'shift.required'            => 'শিফট নির্বাচন করুন',
                    'version.required'          => 'ভার্সন নির্বাচন করুন',
                    'class.required'            => 'ক্লাস নির্বাচন করুন',
                    'section.required'          => 'সেকশন নির্বাচন করুন',
                    'registration_year.required' => 'সন প্রদান করুন',
                    'roll.required'             => 'রোল নম্বর প্রদান করুন',
                    'gender.required'           => 'লিঙ্গ নির্বাচন করুন',
                    'religion.required'         => 'ধর্ম নির্বাচন করুন',
                    'student_name_en.required'  => 'শিক্ষার্থীর নাম (ইংরেজি) প্রদান করুন',
                    'father_name_en.required'   => 'পিতার নাম (ইংরেজি) প্রদান করুন',
                    'image.mimes'               => 'ছবি শুধুমাত্র JPG, JPEG, PNG হবে',
                    'image.max'                 => 'ছবি এর সাইজ সর্বোচ্চ হবে 100 KB',
                    'br_file.mimes'             => 'জন্ম নিবন্ধন ফাইল শুধুমাত্র JPG, JPEG, PNG হবে',
                    'br_file.max'               => 'জন্ম নিবন্ধন ফাইল এর সাইজ সর্বোচ্চ হবে 100 KB',
                    'disability_file.mimes'     => 'ডিসএবিলিটি ফাইল শুধুমাত্র JPG, JPEG, PNG হবে',
                    'disability_file.max'       => 'ডিসএবিলিটি ফাইল এর সাইজ সর্বোচ্চ হবে 100 KB',
                    // 'student_name_bn.required' => 'শিক্ষার্থীর নাম (বাংলা) প্রদান করুন',
                    // 'father_mobile_no.required' => 'পিতার মোবাইল নাম্বার প্রদান করুন',
                ]
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            // dd($e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        try {
            $class_room_payload = [
                'eiin' => auth()->user()->eiin,
                'branch' => @$request->branch,
                'shift' => @$request->shift,
                'version' => @$request->version,
                'class' => @$request->class,
                'section' => @$request->section,
                'session_year' => date('Y')
            ];
            $class_room = $this->classRoomService->findOrCreateClassRoom($class_room_payload);
            $result = $this->studentService->isRollExists($class_room->uid, $request->roll);
            // $result = $this->studentService->checkRollExists($id, $request->roll);
            if ($result && $result->student_uid != $id) {
                $notification = array(
                    'message' => 'এই সেকশনে এই রোল নম্বরের শিক্ষার্থী ইতিমধ্যেই বিদ্যমান।',
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            } else {
                $this->studentService->update($request, $id, $class_room->uid);
                $notification = array(
                    'message' => 'শিক্ষার্থীর তথ্য সফলভাবে আপডেট করা হয়েছে।',
                    'alert-type' => 'success'
                );
                return redirect()->route('student.board_registration.temp.list_tab', $request->class)->with($notification);

                // $authRequest = $this->authService->accountUpdate($request->all(), $id, auth()->user()->eiin, 1, 1);
                // if (@$authRequest->status == true) {
                //     $this->studentService->update($request->all(), $id);
                //     $notification = array(
                //         'message' => 'Student Updated successfully.',
                //         'alert-type' => 'success'
                //     );
                //     return redirect()->route('student.index')->with($notification);
                // } else {
                //     $notification = array(
                //         'message' => 'Student Updated failed.',
                //         'alert-type' => 'error'
                //     );
                //     return back()->with($notification);
                // }
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => 'শিক্ষার্থীর তথ্য আপডেট করা যায় নি।',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function import(Request $request)
    {
        // dd($request->all());
        $request->session()->forget('active_tab');
        $request->session()->put('active_tab', 'tab2');

        $request->validate(
            [
                'branch' => 'required',
                'shift' => 'required',
                'version' => 'required',
                'class' => 'required',
                'section' => 'required',
                'registration_year' => 'required',
                'file' => 'required|mimes:xlsx',
            ],
            [
                'branch.required' => 'ব্রাঞ্চ নির্বাচন করুন',
                'shift.required' => 'শিফট নির্বাচন করুন',
                'version.required' => 'ভার্সন নির্বাচন করুন',
                'class.required' => 'ক্লাস নির্বাচন করুন',
                'section.required' => 'সেকশন নির্বাচন করুন',
                'registration_year.required' => 'সন প্রদান করুন',
                'file.required' => 'অবশ্যই Excel ফাইল প্রদান করতে হবে। ফাইলের টাইপ (.xlsx) হতে হবে।',
                'file.mimes' => 'ফাইলের টাইপ অবশ্যই (.xlsx) হতে হবে',
            ]
        );


        // $request->validate([
        //     'class' => 'required',
        //     'registration_year' => 'required',
        //     'file' => 'required|mimes:xlsx,csv',
        // ]);

        if ($request->hasFile('file')) {
            session()->remove('lastBatch');

            $file = $request->file('file');

            $importedData = new StudentBulkImport();

            $importedData = Excel::toArray($importedData, $file);
            $total = 0;

            $requestData = [
                'branch' => $request['branch'],
                'shift' => $request['shift'],
                'version' => $request['version'],
                'class' => $request['class'],
                'section' => $request['section'],
                'registration_year' => $request['registration_year'],
                // 'roll'=>$request['roll'],
            ];
            if (isset($importedData[0]) && count($importedData[0]) > 0) {
                $batch = Bus::batch([])->name('Import Students')->dispatch();
                foreach ($importedData[0] as $key => $value) {
                    // Check if all values in the row are not null
                    if (!$this->areAllValuesNull($value)) {
                        $auth = auth()->user();
                        $token = UtilsCookie::getCookie();
                        // dd($value,$requestData,$batch->id,$auth, $token);
                        $batch->add(new ImportStudentsJob($value, $requestData, $batch->id, $auth, $token, $this->classRoomService, $this->studentService));
                        // $insert = $this->testStudentBulkService->InsertData($value,$requestData,$batch->id,auth()->user());
                        // $batch->add(new ImportStudentsJob($value,$requestData,$batch->id,auth()->user()));
                        $total += 1;
                    }
                }

                session()->put('lastBatch', $batch->id);
                $notification = [
                    'message' => 'Total ' . $total . ' data added to the queue successfully.',
                    'alert-type' => 'success',
                    'total' => $total
                ];

                return redirect()->back()->with($notification);
            } else {
                $notification = [
                    'message' => 'Error processing Excel rows.',
                    'alert-type' => 'error',
                ];

                return redirect()->back()->with($notification);
            }
        }
    }

    public function importResult(Request $request)
    {
        try {
            $batchId = $request->batchId;  // Batch ID

            $batch = Bus::findBatch($batchId);

            if (!$batch) {
                // Handle the case where the batch with the provided ID doesn't exist
                return response()->json(['error' => 'Batch not found'], 404);
            }

            $totalJobs = $batch->totalJobs;  // Total number of jobs in the batch
            $pendingJobs = $batch->pendingJobs;  // Number of pending jobs in the batch
            $failedJobs = $batch->failedJobs;  // Get failed jobs in the batch
            $finishedJobs = $batch->finishedJobs;  // Get successfully completed jobs in the batch

            $progress = ($totalJobs - $pendingJobs) / $totalJobs * 100;  // Calculate progress percentage

            if ($progress == 100) {
                $checkErrorData = DB::table('students_import_faild_data')->where('batch_id', $request->batchId)->get();
                if (count($checkErrorData) > 0) {
                    $show_dnldBtn = true;
                } else {
                    $show_dnldBtn = false;
                }

                $failedData = count($checkErrorData);
            } else {
                $show_dnldBtn = false;
                $failedData = 0;
            }

            return response()->json([
                'totalJobs' => $totalJobs,
                'pendingJobs' => $pendingJobs,
                'failedData' => $failedData,
                'finishedJobs' => $finishedJobs,
                'progress' => number_format((float)$progress, 2, '.', ''),
                'show_dnldBtn' => $show_dnldBtn
            ]);
        } catch (\Exception $e) {
            // Handle any unexpected exceptions
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function exportFailedData($session_id)
    {
        if (!$session_id) {
            $notification = [
                'message' => 'Something went wrong while exporting',
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }

        $failedData  = DB::table('students_import_faild_data')->where('batch_id', $session_id)->count();

        if ($failedData <= 0) {
            $notification = [
                'message' => 'No data found on our database',
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }

        return Excel::download(new FaildDataExport($session_id), 'failed_data.xlsx');
    }

    public function removeSessionVariable(Request $request)
    {
        $variableName = $request->input('variableName');
        if ($variableName) {
            // session()->remove($variableName);
            session()->forget($variableName);

            return response()->json(['message' => 'Session variable removed successfully', 'reload_url' => 'yes']);
        } else {
            return response()->json(['error' => 'Invalid variable name'], 400);
        }
    }


    private function areAllValuesNull(array $array): bool
    {
        foreach ($array as $value) {
            if (!is_null($value)) {
                return false; // At least one value is not null
            }
        }
        return true; // All values are null
    }

    public function download()
    {
        $eiinId = auth()->user()->eiin;

        $filePath = public_path('student/eiin_students.xlsx');

        $newName = $eiinId . '_students.xlsx';

        $headers = [
            'Content-Type' => 'application/xlsx',
            'Content-Disposition' => 'attachment; filename="' . $newName . '"',
        ];

        return response()->file($filePath, $headers);

        // return response()->download(public_path('student/eiin_students.xlsx'));
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function getAllRequiredDropdownForStudents(Request $request)
    {
        $branchId = $request->input('id');
        $eiinId = auth()->user()->eiin;
        $versions = $this->studentService->getVersionByEiinId($branchId, $eiinId);
        $shifts =  $this->studentService->getShiftByEiinId($branchId, $eiinId);

        $response = [
            'versions' => $versions,
            'shifts' => $shifts,
        ];

        return response()->json($response);
    }

    public function getSectionDropdownForStudents(Request $request)
    {
        $branchId = $request->input('branch_id');
        $classId = $request->input('class_id');
        $shiftId = $request->input('shift_id');
        $versionId = $request->input('version_id');
        $eiinId = auth()->user()->eiin;

        $response = $this->studentService->getSectionByEiinId($branchId, $classId, $shiftId, $versionId, $eiinId);

        return response()->json($response);
    }

    public function delete(Request $request)
    {
        //$this->studentService->delete($request->id);
        $student_uid = $request->id;

        $student = Student::where('uid', $student_uid)->first();
        $class_info = StudentClassInfo::where('student_uid', $student_uid)->first();
        $attendances_info = StudentAttendance::where('student_uid', $student_uid)->first();
        $vw_pi_evolation_info = DB::connection('db_evaluation')->table('vw_pi_evolation')->where('student_uid', $student_uid)->first();


        $message = '';
        if ($attendances_info) {
            $message  .= '<p>ইতিমধ্যে এই শিক্ষার্থীর অধীনে উপস্থিতির তথ্য রয়েছে।</p>';
        }

        if ($vw_pi_evolation_info) {
            $message  .= '<p>ইতিমধ্যে এই শিক্ষার্থীর অধীনে মূল্যায়নের তথ্য রয়েছে।</p>';
        }

        if ($attendances_info ||  $vw_pi_evolation_info) {
            $message  .= '<p class="fw-bold"> এই শিক্ষার্থীর তথ্য মুছে ফেলা সম্ভব নয়। প্রয়োজনে নিষ্ক্রিয় করুন।</p>';
            return response()->json(['status' => 'error', 'message' => $message]);
        }

        $student->delete();
        $class_info->delete();

        return response()->json(['status' => 'success', 'message' => 'শিক্ষার্থীর তথ্যটি মুছে ফেলা হয়েছে।']);
    }

    public function studentRecStatus(Request $request)
    {
        $status = (int) $request->rec_status;
        try {
            if ($status == 1) {
                $student_uid = $request->id;
                $class_info = StudentClassInfo::where('student_uid', $student_uid)->first();
                $class_info->rec_status = 0;
                $class_info->save();
                return response()->json(['status' => 'success', 'message' => 'এই শিক্ষার্থীকে নিষ্ক্রিয় করা হয়েছে।']);
            } else {

                $student_uid = $request->id;
                $class_info = StudentClassInfo::where('student_uid', $student_uid)->first();
                $class_info->rec_status = 1;
                $class_info->save();
                return response()->json(['status' => 'success', 'message' => 'এই শিক্ষার্থীকে সক্রিয় করা হয়েছে।']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'স্ট্যাটাস পরিবর্তন করতে সমস্যা হয়েছে|'], 500);
        }
    }
}

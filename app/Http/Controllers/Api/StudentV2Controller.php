<?php

namespace App\Http\Controllers\Api;

use App\Helper\SmsService;
use App\Helper\TeacherInfo;
use App\Models\StudentSms;
use Exception;
use App\Models\Student;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ValidtorMapper;
use App\Services\StudentService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\SmsLogService;
use App\Http\Requests\Student\StudentStoreRequest;
use App\Http\Requests\Student\StudentUpdateRequest;
use App\Services\ClassRoomService\ClassRoomService;
use App\Http\Requests\Student\StudentQuickRegRequest;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\MarkDistribution;
use App\Models\StudentClassInfo;
use App\Models\SubjectTeacher;
use App\Services\TeacherService;

class StudentV2Controller extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $teacherService;
    private $studentService;
    private $classRoomService;
    private $smsLogService;

    public function __construct(TeacherService $teacherService,StudentService $studentService, ClassRoomService $classRoomService, SmsLogService $smsLogService)
    {
        $this->teacherService = $teacherService;
        $this->studentService = $studentService;
        $this->classRoomService = $classRoomService;
        $this->smsLogService = $smsLogService;
    }

    public function getAllStudent(Request $request)
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $students = $this->studentService->getAllByEiinId($eiinId, null, $request->all());
            return $this->successResponse($students, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function index(Request $request)
    {
        \Log::info("xxx1");
        try {
               $eiinId = getAuthInfo()['eiin'];
            
            $user_type_id = getAuthInfo()['user_type_id'];
            \Log::info("xxx2");
            if($user_type_id == 1){
                \Log::info("xxx3");
                $teacher = TeacherInfo::teacherInfo();
                if($teacher['teacher_type'] == 'subject_teacher'){
                    $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                    $students = $this->studentService->getByClassRoomId($eiinId, null, $request->all(), $class_room_uid, 4000);
                }else if($teacher['teacher_type'] == 'class_teacher'){
                    $class_room_uid = $teacher['data']->pluck('uid')->unique();
                    $students = $this->studentService->getByClassRoomId($eiinId, null, $request->all(), $class_room_uid, 4000);
                }
            }else{
                 \Log::info("xxx4");
                $students = $this->studentService->getByEiinId($eiinId, null, $request->all(), 4000);
            }
            return $this->successResponse($students, Response::HTTP_OK);
        } catch (Exception $e) {
                   \Log::info("xxx5",["eror"=>$e->getMessage()]);
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }
    
    public function pginateIndex(Request $request)
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            
            // user_type_id == 1 mean teacher
            if(app('sso-auth')->user()->user_type_id == 1){
                $teacher = TeacherInfo::teacherInfo();
                if($teacher['teacher_type'] == 'subject_teacher'){
                    $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                    $students = $this->studentService->getByClassRoomId($eiinId, null, $request->all(), $class_room_uid, 30);
                }else if($teacher['teacher_type'] == 'class_teacher'){
                    $class_room_uid = $teacher['data']->pluck('uid')->unique();
                    $students = $this->studentService->getByClassRoomId($eiinId, null, $request->all(), $class_room_uid, 30);
                }
            }else{
                $students = $this->studentService->getByEiinId($eiinId, null, $request->all(), 30);
            }
            return $this->successResponse($students, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function getById($id)
    {
        try {
            $student = $this->studentService->getById($id);
            if ($student) {
                return $this->successResponse($student, Response::HTTP_OK);
            } else {
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function store(StudentStoreRequest $request)
    {
        try {
            $class_room_payload = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch' => @$request->branch,
                'shift' => @$request->shift,
                'version' => @$request->version,
                'class' => @$request->class,
                'section' => @$request->section,
                'session_year' => date('Y')
            ];
            $class_room = $this->classRoomService->findOrCreateClassRoom($class_room_payload);
            $roll_exists = $this->studentService->isRollExists($class_room->uid, $request->roll);

            if ($roll_exists) {
                $message = 'এই সেকশনে এই রোল ('.$request->roll.') নম্বরের শিক্ষার্থী ইতিমধ্যেই বিদ্যমান।';
                return $this->errorResponse($message, Response::HTTP_CONFLICT);
            } else {

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = app('sso-auth')->user()->eiin . '_' . date('Ymd') . '_' . time() . '.' . $image->getClientOriginalExtension();

                    $filePath = $image->storeAs('student/image', $filename, 'public');

                    $request['filePath'] = $filePath;
                }else {
                    $request['filePath'] = null;
                }

                $request['eiin'] = app('sso-auth')->user()->eiin;
                $student = $this->studentService->create($request->all(), $class_room->uid);

                $message = 'শিক্ষার্থী সফলভাবে যুক্ত করা হয়েছে।';
                return $this->successResponseWithData($student, $message, Response::HTTP_OK);
            }
        } catch (Exception $e) {
            $message = 'শিক্ষার্থীর তথ্য যুক্ত করা যায় নি।';
            return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
        }
    }

    public function studentQuickReg(StudentQuickRegRequest $request)
    {
        try {
            DB::beginTransaction();
            $studentInfo = [];
            foreach ($request->students as $key => $data) {
                $class_room_payload = [
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch'            => @$request->branch,
                    'version'           => @$request->version,
                    'session_year'      => @$request->session ?? date('Y'),
                    'shift'             => @$request->shift,
                    'roll'              => @$data['roll'],
                    'student_unique_id' => @$data['student_unique_id'],
                    'student_name_en'   => @$data['student_name_en'],
                    'gender'            => @$data['gender'],
                    'class'             => @$data['class'],
                    'section'           => @$data['section'],
                    'group'             => @$data['group'],
                    'student_mobile_no' => @$data['student_mobile_no'],
                ];
                $class_room = $this->classRoomService->findOrCreateClassRoom($class_room_payload);
                $roll_exists = $this->studentService->isRollExists($class_room->uid, $data['roll']);

                if ($roll_exists) {
                    $message = 'এই সেকশনে এই রোল ('.$data['roll'].') নম্বরের শিক্ষার্থী ইতিমধ্যেই বিদ্যমান।';
                    return $this->errorResponse($message, Response::HTTP_CONFLICT);
                } else {
                    $request['eiin'] = app('sso-auth')->user()->eiin;
                    $studentInfo[] = $this->studentService->create($class_room_payload, $class_room->uid);
                }
            }

            DB::commit();

            $message = 'শিক্ষার্থী সফলভাবে যুক্ত করা হয়েছে।';
            return $this->successResponseWithData($studentInfo, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'শিক্ষার্থীর তথ্য যুক্ত করা যায় নি।';
            return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
        }
    }

    public function studentExcelUpload(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'branch'            => 'required',
                'version'           => 'required',
                'shift'             => 'required',
                'class'             => 'required',
                'section'           => 'required',

                // Students array validation
                'students'                 => 'required|array|min:1',
                'students.*.registration_year' => 'required',
                'students.*.roll'              => 'required',
                'students.*.student_name_en'    => 'required',
                'students.*.gender'            => 'required',
                'students.*.father_name_en'    => 'required',
                'students.*.father_mobile_no'  => 'required',
                'students.*.religion'          => 'required',
                'students.*.student_unique_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($request) {
                        $exists = Student::where('eiin', app('sso-auth')->user()->eiin)
                                         ->where('student_unique_id', $value)
                                         ->exists();
                        if ($exists) {
                            $fail('এই EIIN এর অধীনে Student Unique ID ('.$value.') ইতিমধ্যেই বিদ্যমান।');
                        }
                    }
                ],
            ]);
            
            if ($validation->fails()) {
                               \Log::info('9992', $validation->errors()->toArray());  
                return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            }


            DB::beginTransaction();

            $studentInfo = [];
            foreach ($request->students as $key => $data) {
                $class_room_payload = [
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch'            => @$request->branch,
                    'version'           => @$request->version,
                    'session_year'      => @$data['registration_year'] ?? date('Y'),
                    'shift'             => @$request->shift,
                    'roll'              => @$data['roll'],
                    'student_unique_id' => @$data['student_unique_id'],
                    'student_name_en'   => @$data['student_name_en'],
                    'gender'            => @$data['gender'],
                    'class'             => @$request->class,
                    'section'           => @$request->section,
                    'group'             =>  @$request->group ?? null,
                    'father_name_en'    => @$data['father_name_en'],
                    'father_mobile_no'  => @$data['father_mobile_no'],
                    'religion'          => @$data['religion'],
                ];

                $class_room = $this->classRoomService->findOrCreateClassRoom($class_room_payload);
                $roll_exists = $this->studentService->isRollExists($class_room->uid, $data['roll']);

                if ($roll_exists) {
                    $message = 'এই সেকশনে এই রোল ('.$data['roll'].') নম্বরের শিক্ষার্থী ইতিমধ্যেই বিদ্যমান।';
                    return $this->errorResponse($message, Response::HTTP_CONFLICT);
                } else {
                    $request['eiin'] = app('sso-auth')->user()->eiin;
                    $studentInfo[] = $this->studentService->create($class_room_payload, $class_room->uid);
                }
            }

            DB::commit();

            $message = 'শিক্ষার্থী সফলভাবে যুক্ত করা হয়েছে।';
            return $this->successResponseWithData($studentInfo, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'শিক্ষার্থীর তথ্য যুক্ত করা যায় নি।';
            return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
        }
    }

    public function update(StudentUpdateRequest $request)
    {
        try {
            $class_room_payload = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch' => @$request->branch,
                'shift' => @$request->shift,
                'version' => @$request->version,
                'class' => @$request->class,
                'section' => @$request->section,
                'session_year' => date('Y')
            ];
            $class_room = $this->classRoomService->findOrCreateClassRoom($class_room_payload);
            $roll_exists = $this->studentService->isRollExists($class_room->uid, $request->roll);

            if ($roll_exists && $roll_exists->student_uid != $request->uid) {
                $message = 'এই সেকশনে এই রোল নম্বরের শিক্ষার্থী ইতিমধ্যেই বিদ্যমান।';
                return $this->errorResponse($message, Response::HTTP_CONFLICT);
            } else {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = app('sso-auth')->user()->eiin . '_' . date('Ymd') . '_' . time() . '.' . $image->getClientOriginalExtension();

                    $filePath = $image->storeAs('student/image', $filename, 'public');

                    $request['filePath'] = $filePath;
                }else {
                    $student = Student::on('db_read')->where('caid', $request->uid)->orwhere('uid', $request->uid)->first();
                    $request['filePath'] = $student->image;
                }
                $request['eiin'] = app('sso-auth')->user()->eiin;
                $student = $this->studentService->update($request->all(), $request->uid, $class_room->uid);

                $message = 'শিক্ষার্থীর তথ্য সফলভাবে আপডেট করা হয়েছে।';
                return $this->successResponseWithData($student, $message, Response::HTTP_OK);
            }
        } catch (Exception $e) {
            $message = 'শিক্ষার্থীর তথ্য আপডেট করা যায় নি।';
            return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        $related_items = [];
        $related_items = $this->studentService->getRelatedItemsForStudent($related_items, $id);

        if ($related_items['attendances_info']) {
            $message['attendances_info_exists'] = 'ইতিমধ্যে এই শিক্ষার্থীর অধীনে উপস্থিতির তথ্য রয়েছে।';
        }

        // if ($related_items['evolation_info']) {
        //     $message['evolation_info_exists'] = 'ইতিমধ্যে এই শিক্ষার্থীর অধীনে মূল্যায়নের তথ্য রয়েছে।';
        // }

        // if ($related_items['attendances_info'] || $related_items['evolation_info']) {
        if ($related_items['attendances_info']) {
            // $message  .= 'অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।';
            return response()->json(['status' => 'error', 'message' => $message]);
        }

        $this->studentService->delete($id);
        return $this->successMessage('শিক্ষার্থীর তথ্যটি মুছে ফেলা হয়েছে।');
    }

    public function studentChangeStatus(Request $request)
    {
        $status = (int) $request->rec_status;
        $student_uid = $request->uid;
        try {
            $student = $this->studentService->changeStatus($student_uid, $status);
            if ($student->rec_status == 1) {
                $message = 'এই শিক্ষার্থীকে সক্রিয় করা হয়েছে।';
                return $this->successResponseWithData($student, $message, Response::HTTP_OK);
            } elseif ($student->rec_status == 0) {
                $message = 'এই শিক্ষার্থীকে নিষ্ক্রিয় করা হয়েছে।';
                return $this->successResponseWithData($student, $message, Response::HTTP_OK);
            } else {
                $message = 'শিক্ষার্থীর স্ট্যাটাস পরিবর্তন করা যায় নি।';
                return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            $message = 'শিক্ষার্থীর স্ট্যাটাস পরিবর্তন করা যায় নি।';
            return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
        }
    }


    public function classWiseStudent(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'branch_id'  => 'required',
            'version_id' => 'required',
            'shift_id'   => 'required',
            'class_id'   => 'required',
            'section_id' => 'required',
            'exam_type'  => 'required',
            'exam_id'    => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            // return $this->error($validation->errors()->first(), 400, []);
        }

        $studentUids = StudentClassInfo::whereHas('classRoom', function ($query) use ($request) {
            $query->where('branch_id', $request->branch_id)
                ->where('version_id', $request->version_id)
                ->where('shift_id', $request->shift_id)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id);
        })->pluck('student_uid');

        $students = Student::select('uid', 'eiin', 'caid', 'student_name_en', 'student_name_bn', 'roll', 'section', 'shift', 'version', 'class')
            ->whereIn('uid', $studentUids)
            ->orderByRaw('CAST(roll AS UNSIGNED) ASC')
            ->get();

        $studentMarks = MarkDistribution::where([
            'eiin'              => app('sso-auth')->user()->eiin,
            'class_id'          => $request->class_id,
            'section_id'        => $request->section_id,
            'exam_type'         => $request->exam_type,
            'exam_id'           => $request->exam_id,
        ])->get()->keyBy('student_id');


        // return $studentMarks;

        $students = $students->map(function ($student) use ($studentMarks) {
            $student->mark_distribution_uid = null;
            $student->mark_written          = null;
            $student->mark_mcq              = null;
            $student->practical             = null;
            $student->status                = null;
            $student->remark                = null;
            $student->is_submitted          = 0;

            if ($studentMarks->has($student->uid)) {
                $marks = $studentMarks->get($student->uid);
                $student->mark_distribution_uid = $marks->uid;
                $student->mark_written          = $marks->written_mark;
                $student->mark_mcq              = $marks->mcq_mark;
                $student->practical             = $marks->practical_mark;
                $student->status                = $marks->status;
                $student->remark                = $marks->remark;
                $student->is_submitted          = $marks->is_submitted;
            }

            return $student;
        });

        return $this->successResponseWithData($students, '', Response::HTTP_OK);
    }

    public function sectionWiseStudent(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'branch_id'  => 'required',
            'version_id' => 'required',
            'shift_id'   => 'required',
            'class_id'   => 'required',
            'section_id' => 'required',
            'period'     => 'required',
            'date'       => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            // return $this->error($validation->errors()->first(), 400, []);
        }

        // $studentUids = StudentClassInfo::whereHas('classRoom', function ($query) use ($request) {
        //     $query->where('branch_id', $request->branch_id)
        //         ->where('version_id', $request->version_id)
        //         ->where('shift_id', $request->shift_id)
        //         ->where('class_id', $request->class_id)
        //         ->where('section_id', $request->section_id)
        //         ->where('session_year', '2026');
        // })->pluck('student_uid');

        // $students = Student::select('uid', 'eiin', 'caid', 'student_name_en', 'student_name_bn', 'roll', 'section', 'shift', 'version', 'class')
        //     ->whereIn('uid', $studentUids)
        //     ->orderByRaw('CAST(roll AS UNSIGNED) ASC')
        //     ->get();

        $classRolls = StudentClassInfo::whereHas('classRoom', function ($query) use ($request) {
            $query->where('branch_id', $request->branch_id)
                ->where('version_id', $request->version_id)
                ->where('shift_id', $request->shift_id)
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('session_year', '2026');
        })->pluck('roll', 'student_uid');

        $students = Student::select('uid', 'eiin', 'caid', 'student_name_en', 'student_name_bn', 'roll', 'section', 'shift', 'version', 'class')
            ->whereIn('uid', $classRolls->keys())
            ->orderByRaw('CAST(roll AS UNSIGNED) ASC')
            ->get();

        $students = $students->map(function ($student) use ($classRolls) {
            $roll = $classRolls->get($student->uid);
            if ($roll !== null) {
                $student->roll = $roll;
            }

            return $student;
        });

        $attendances = Attendance::where([
            'eiin'          => app('sso-auth')->user()->eiin,
            'branch_id'     => $request->branch_id,
            'shift_id'      => $request->shift_id,
            'version_id'    => $request->version_id,
            'class_id'      => $request->class_id,
            'section_id'    => $request->section_id,
            'period'        => $request->period,
            // 'date'          => $request->date,
        ])
        ->whereRaw("DATE(`date`) = ?", [date('Y-m-d', strtotime($request->date))])
        ->get()->keyBy('student_id');


        // return $studentMarks;

        $students = $students->map(function ($student) use ($attendances) {
            $student->attendance_uid = null;
            $student->period         = null;
            $student->date           = null;
            $student->status         = null;
            $student->remark         = null;

            if ($attendances->has($student->uid)) {
                $data = $attendances->get($student->uid);
                $student->attendance_uid = $data->uid;
                $student->period         = $data->period;
                $student->date           = $data->date;
                $student->status         = $data->status;
                $student->remark         = $data->remark;
            }

            return $student;
        });

        return $this->successResponseWithData($students, '', Response::HTTP_OK);
    }

    public function sectionWiseStudentList(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'branch_id'  => 'required',
            'version_id' => 'required',
            'shift_id'   => 'required',
            'class_id'   => 'required',
            'section_id' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
        }

        $students = Student::select('uid', 'eiin', 'caid', 'student_name_en', 'student_name_bn', 'roll', 'section', 'shift', 'version', 'class', 'guardian_mobile_no', 'father_mobile_no', 'mother_mobile_no')
                            ->where([
                                'branch' => $request->branch_id,
                                'version' => $request->version_id,
                                'shift' => $request->shift_id,
                                'class' => $request->class_id,
                                'section' => $request->section_id,
                            ])->get();

        return $this->successResponseWithData($students, '', Response::HTTP_OK);
    }

    public function bulkStudentsSmsSend(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'branch_id'  => 'required',
            'version_id' => 'required',
            'shift_id'   => 'required',
            'class_id'   => 'required',
            'section_id' => 'required',

            'studentData' => 'required|array|min:1',
            'studentData.*.student_id' => 'required',
            'studentData.*.phone_no' => 'required',
            'studentData.*.text' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            // return $this->error($validation->errors()->first(), 400, []);
        }

        if(!array_key_exists('studentData', $request->all()) || count($request->studentData) == 0) return $this->errorResponse('কমপক্ষে একজন ছাত্র/ছাত্রী হাজিরা ইনপুট দিন', Response::HTTP_NOT_ACCEPTABLE);

        $students = [];
        foreach ($request->studentData as $key => $data) {
            $payload = [
                'eiin'          => app('sso-auth')->user()->eiin,
                'branch_id'     => $request->branch_id,
                'version_id'    => $request->version_id,
                'shift_id'      => $request->shift_id,
                'class_id'      => $request->class_id,
                'section_id'    => $request->section_id,
                'student_id'    => $data['student_id'] ?? null,
                'phone_no'      => $data['phone_no'] ?? null,
                'text'          => $data['text'] ?? null,
            ];

            $students[] = StudentSms::create($payload);

            $textSend = SmsService::sendSMS($data['text'],  $data['phone_no']);
            $this->smsLogService->store(app('sso-auth')->user()->eiin, $data['phone_no'], $data['text'], $textSend, $data['student_id']);
        }

        return $this->successResponseWithData($students, '', Response::HTTP_OK);
    }
}

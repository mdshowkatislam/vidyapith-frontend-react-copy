<?php

namespace App\Http\Controllers\Api;

use App\Helper\SmsService;
use App\Helper\TeacherInfo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\AttendanceStoreAiRequest;
use App\Http\Requests\Attendance\AttendanceStoreRequest;
use App\Http\Requests\Attendance\AttendanceUpdateRequest;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Session;
use App\Models\Student;
use App\Services\Api\SmsLogService;
use App\Services\AttendanceService;
use App\Services\StudentService;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Exception;

class AttendanceController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $attendanceService;
    private $studentService;
    private $smsLogService;

    public function __construct(AttendanceService $attendanceService, StudentService $studentService, SmsLogService $smsLogService)
    {
        $this->attendanceService = $attendanceService;
        $this->studentService = $studentService;
        $this->smsLogService = $smsLogService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info('CCC0');

        try {
            $eiinId = getAuthInfo()['eiin'];
            $userTypeId = getAuthInfo()['user_type_id'];
            if ($userTypeId == 1) {
                $teacher = TeacherInfo::teacherInfo();
                if ($teacher['teacher_type'] == 'subject_teacher') {
                    $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                    $section_id = ClassRoom::whereIn('uid', $class_room_uid)->pluck('section_id')->unique();
                    $classTestList = $this->attendanceService->getBySectionId($eiinId, null, $section_id);
                } else if ($teacher['teacher_type'] == 'class_teacher') {
                    $section_id = $teacher['data']->pluck('section_id')->unique();
                    $classTestList = $this->attendanceService->getBySectionId($eiinId, null, $section_id);
                }
            } else {
                \Log::info('CCC', ['eiin' => $eiinId]);
                $classTestList = $this->attendanceService->getByEiinId($eiinId);
            }
            return $this->successResponse($classTestList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceStoreRequest $request)
    {
        try {
            if (!array_key_exists('studentData', $request->all()) || count($request->studentData) == 0)
                return $this->errorResponse('কমপক্ষে একজন ছাত্র/ছাত্রী হাজিরা ইনপুট দিন', Response::HTTP_NOT_ACCEPTABLE);

            $status = 'তৈরি';
            $attendance = [];
            foreach ($request->studentData as $key => $data) {
                $payload = [
                    'eiin' => app('sso-auth')->user()->eiin,
                    'caid' => app('sso-auth')->user()->caid,
                    'pdsid' => app('sso-auth')->user()->pdsid,
                    'branch_id' => $request->branch_id,
                    'shift_id' => $request->shift_id,
                    'version_id' => $request->version_id,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'period' => $request->period,
                    'date' => $request->date,
                    'entry_time' => $data['entry_time'] ?? null,
                    'source' => 'web',
                    'student_id' => $data['uid'],
                    'status' => $data['status'],
                    'remark' => array_key_exists('remark', $data) ? $data['remark'] : null,
                ];

                if (isset($data['attendance_uid']) && !empty($data['attendance_uid'])) {
                    $status = 'আপডেট';
                    $payload['uid'] = $data['attendance_uid'];
                    $attendance[] = $this->attendanceService->update($payload);
                } else {
                    $attendanceData = Attendance::where([
                        'eiin' => app('sso-auth')->user()->eiin,
                        'branch_id' => $request->branch_id,
                        'shift_id' => $request->shift_id,
                        'version_id' => $request->version_id,
                        'class_id' => $request->class_id,
                        'section_id' => $request->section_id,
                        'period' => $request->period,
                        'student_id' => $data['uid'],
                    ])
                        ->whereRaw('DATE(`date`) = ?', [date('Y-m-d', strtotime($request->date))])  // Compare only the date part
                        ->first();

                    if ($attendanceData) {
                        $status = 'আপডেট';
                        $payload['uid'] = $attendanceData->uid;
                        $attendance[] = $this->attendanceService->update($payload);
                    } else {
                        $status = 'তৈরি';
                        $attendance[] = $this->attendanceService->create($payload);
                    }
                }

                $student = $this->studentService->getStudentInfoByUid($data['uid']);

                $parentNumber = $student->guardian_mobile_no
                    ?? $student->father_mobile_no
                    ?? $student->mother_mobile_no;

                $parentName = 'Guardian';
                $studentName = $student->student_name_en ?? $student->student_name_bn;

                if (array_key_exists('is_both_sms', $request->all()) && $request->is_both_sms == 1) {
                    if (!empty($parentNumber)) {
                        if ($data['status'] == 'Present') {
                            $html = "Dear {$parentName} of {$studentName}, your child is present today at school. Thank you.";
                        } elseif ($data['status'] == 'Absent') {
                            $html = "Dear {$parentName} of {$studentName}, your child  is absent today at school. Please contact the school if necessary.Thank you.";
                        } elseif ($data['status'] == 'Late') {
                            $html = "Dear {$parentName} of {$studentName}, your child arrived late at school today. Please ensure timely attendance. Thank you.";
                        }
                        $textSend = SmsService::sendSMS($html, $parentNumber);
                        $this->smsLogService->store(app('sso-auth')->user()->eiin, $parentNumber, $html, $textSend, $data['uid']);
                    }
                }

                if (array_key_exists('is_present_sms', $request->all()) && $request->is_present_sms == 1) {
                    if ($data['status'] == 'Present') {
                        if (!empty($parentNumber)) {
                            $html = "Dear {$parentName} of {$studentName}, your child is present today at school. Thank you.";
                            $textSend = SmsService::sendSMS($html, $parentNumber);
                            $res = $this->smsLogService->store(app('sso-auth')->user()->eiin, $parentNumber, $html, $textSend, $data['uid']);
                        }
                    }
                }

                if (array_key_exists('is_absent_sms', $request->all()) && $request->is_absent_sms == 1) {
                    if ($data['status'] == 'Absent') {
                        if (!empty($parentNumber)) {
                            $html = "Dear {$parentName} of {$studentName}, your child  is absent today at school. Please contact the school if necessary.Thank you.";
                            $textSend = SmsService::sendSMS($html, $parentNumber);
                            $this->smsLogService->store(app('sso-auth')->user()->eiin, $parentNumber, $html, $textSend, $data['uid']);
                        }
                    }
                }

                if (array_key_exists('is_late_sms', $request->all()) && $request->is_late_sms == 1) {
                    if ($data['status'] == 'Late') {
                        if (!empty($parentNumber)) {
                            $html = "Dear {$parentName} of {$studentName}, your child arrived late at school today. Please ensure timely attendance. Thank you.";
                            $textSend = SmsService::sendSMS($html, $parentNumber);
                            $this->smsLogService->store(app('sso-auth')->user()->eiin, $parentNumber, $html, $textSend, $data['uid']);
                        }
                    }
                }
            }
            $message = 'হাজিরা সফলভাবে ' . $status . ' করা হয়েছে।';
            return $this->successResponseWithData($attendance, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'হাজিরা তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function storeByAi(AttendanceStoreAiRequest $request)
    {
        try {
            $studentData = Student::where('uid', $request->uid)->first();
            // $studentData = Student::where('uid', '1821132174584086')->first();

            if (!$studentData) {
                throw new Exception('Student not found', Response::HTTP_NOT_FOUND);
            }

            $payload = [
                'eiin' => $studentData->eiin,
                'branch_id' => $studentData->branch,
                'shift_id' => $studentData->shift,
                'version_id' => $studentData->version,
                'class_id' => $studentData->class,
                'section_id' => $studentData->section,
                'period' => 1,
                'date' => now()->toDateString(),
                'student_id' => $studentData->uid,
                'status' => 'Present',
                'source' => 'ai',
                'remark' => array_key_exists('remark', $request->all()) ? $request->remark : null,
            ];

            $attendance = Attendance::where([
                'student_id' => $studentData->uid,
                'period' => 1,
                'date' => now()->toDateString()
            ])->first();

            // return $attendance;

            if (isset($attendance)) {
                $status = 'আপডেট';
                // $payload['uid'] = $attendance->uid;
                // $attendance[] = $this->attendanceService->update($payload);
            } else {
                $status = 'তৈরি';
                $attendance[] = $this->attendanceService->create($payload);
            }

            $message = 'হাজিরা সফলভাবে ' . $status . ' করা হয়েছে।';
            return $this->successResponseWithData($attendance, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // $message = 'হাজিরা তৈরি করা সম্ভব হয় নি।';
            // return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function storeByFingerPrint(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'studentData' => 'required|array',
                'studentData.*.id' => 'required',
                // 'studentData.*.machine_id' => 'required',
                // 'studentData.*.time' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            }
            if (!array_key_exists('studentData', $request->all()) || count($request->studentData) == 0)
                return $this->errorResponse('কমপক্ষে একজন ছাত্র/ছাত্রী হাজিরা ইনপুট দিন', Response::HTTP_NOT_ACCEPTABLE);

            $attendance = [];
            foreach ($request->studentData as $key => $data) {
                $student = Student::where('id', $data['id'])->first();
                // $studentData = Student::where('uid', '1821132174584086')->first();

                if (!$student) {
                    throw new Exception('Student not found', Response::HTTP_NOT_FOUND);
                }

                $payload = [
                    'eiin' => $student->eiin,
                    'branch_id' => $student->branch,
                    'shift_id' => $student->shift,
                    'version_id' => $student->version,
                    'class_id' => $student->class,
                    'section_id' => $student->section,
                    'period' => 1,
                    'date' => isset($data['time']) ? Carbon::parse($data['time'])->toDateString() : now()->toDateString(),
                    'entry_time' => isset($data['time']) ? Carbon::parse($data['time'])->format('H:i:s') : now()->format('H:i:s'),
                    'student_id' => $student->uid,
                    'status' => 'Present',
                    'source' => 'fingerprint',
                    'machine_id' => $data['machine_id'] ?? null,
                    'remark' => array_key_exists('remark', $request->all()) ? $request->remark : null,
                ];

                $attendance = Attendance::where([
                    'student_id' => $student->uid,
                    'period' => 1,
                    'date' => now()->toDateString()
                ])->first();

                // return $attendance;

                if (isset($attendance)) {
                    $status = 'আপডেট';
                    // $payload['uid'] = $attendance->uid;
                    // $attendance[] = $this->attendanceService->update($payload);
                } else {
                    $status = 'তৈরি';
                    $attendance[] = $this->attendanceService->create($payload);
                }
            }

            $message = 'হাজিরা সফলভাবে ' . $status . ' করা হয়েছে।';
            return $this->successResponseWithData($attendance, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // $message = 'হাজিরা তৈরি করা সম্ভব হয় নি।';
            // return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($uid)
    {
        try {
            $branch = $this->attendanceService->getById($uid);
            if ($branch) {
                return $this->successResponse($branch, Response::HTTP_OK);
            } else {
                return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->attendanceService->delete($id);
        return response()->json(['status' => 'success', 'message' => 'হাজিরা তথ্যটি মুছে ফেলা হয়েছে।']);
    }

    public function studentWiseAttendance(Request $request)
    {
        $req = $request->all();
        $sessionId = $req['session'] ?? null;
        $session = Session::where('uid', $req['session'])->value('session') ?? date('Y');
        $attendance = Attendance::with('student')
            ->when(array_key_exists('student_id', $req) && !empty($req['student_id']), function ($query) use ($req) {
                return $query->where('student_id', @$req['student_id']);
            })
            ->when($session, function ($query) use ($session) {
                return $query->whereYear('date', $session);  // Filter by year extracted from 'date' column
            })
            ->get();
        return $this->successResponseWithData($attendance, '', Response::HTTP_OK);
    }

    public function sectionWiseMonthlyAttendance(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'class_id' => 'required',
                'section_id' => 'required',
                'month' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            }
            $year = $request->year ?? date('Y');
            $month = $request->month;

            $students = Student::where(['class' => $request->class_id, 'section' => $request->section_id])->get();
            if (count($students) == 0)
                return $this->errorResponse('Student not found!', Response::HTTP_NOT_FOUND);

            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

            $attendanceRecords = Attendance::where(['class_id' => $request->class_id, 'section_id' => $request->section_id])
                ->where('period', 1)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get()
                ->groupBy('student_id');

            $attendances = [];
            foreach ($students as $student) {
                $attendances[$student->uid] = [];
                $attendances[$student->uid]['name_en'] = $student->student_name_en;
                $attendances[$student->uid]['name_bn'] = $student->student_name_bn;
                $attendances[$student->uid]['roll'] = $student->roll;

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $currentDate = Carbon::createFromDate($year, $month, $day);

                    // Handle Fridays and Saturdays
                    if (in_array($currentDate->dayOfWeek, [Carbon::FRIDAY])) {
                        $attendances[$student->uid][$day] = 'Friday';
                        continue;
                    }
                    if (in_array($currentDate->dayOfWeek, [Carbon::SATURDAY])) {
                        $attendances[$student->uid][$day] = 'Saturday';
                        continue;
                    }

                    // Check if attendance records exist for the student
                    if (isset($attendanceRecords[$student->uid])) {
                        // $attendance = $attendanceRecords[$student->uid]->whereRaw("DATE(`date`) = ?", [date('Y-m-d', strtotime($currentDate->toDateString()))])->first();
                        $attendance = $attendanceRecords[$student->uid]
                            ->filter(function ($record) use ($currentDate) {
                                return Carbon::parse($record->date)->toDateString() === $currentDate->toDateString();
                            })
                            ->first();
                        $attendances[$student->uid][$day] = $attendance ? $attendance->status : '-';
                    } else {
                        $attendances[$student->uid][$day] = '-';
                    }
                }
            }

            // return response()->json([
            //     'status' => 'success',
            //     'data' => $attendances,
            // ], 200);
            return $this->successResponseWithData($attendances, '', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse([$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMyAttendance(Request $request)
    {
        $sessionYear = Session::where('uid', $request->input('session'))
            ->value('session') ?? now()->year;

        $attendance = Attendance::query()
            ->select('date', 'student_id', 'period', 'status', 'remark')
            ->with(['student:id,uid,student_name_en,roll'])
            ->when($request->filled('student_id'), function ($query) use ($request) {
                $query->where('student_id', $request->student_id);
            })
            ->whereYear('date', $sessionYear)
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'period' => $item->period,
                    'status' => $item->status,
                    'remark' => $item->remark,
                ];
            });

        return $this->successResponseWithData($attendance, '', Response::HTTP_OK);
    }

    public function getAttendanceByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'student_id' => 'nullable'
        ]);

        $attendance = Attendance::query()
            ->select('date', 'student_id', 'period', 'status', 'remark')
            ->with(['student:id,uid,student_name_en,roll'])
            ->whereDate('date', $request->date)
            ->when($request->filled('student_id'), function ($query) use ($request) {
                $query->where('student_id', $request->student_id);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'roll' => $item->student->roll ?? null,
                    'name' => $item->student->student_name_en ?? null,
                    'period' => $item->period,
                    'status' => $item->status,
                    'remark' => $item->remark,
                ];
            });

        return $this->successResponseWithData($attendance, '', Response::HTTP_OK);
    }
}

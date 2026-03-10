<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamConfigure;
use App\Models\MeritList;
use App\Services\ExamConfigureService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ResultConfiguration\ResultConfigurationStoreRequest;
use App\Models\Attendance;
use App\Models\MarkDistribution;
use App\Models\Result;
use App\Models\ResultConfiguration;
use App\Models\AttendanceConfigure;
use App\Models\Student;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\ResultConfigurationService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ResultConfigureController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $resultConfigurationService;
    private $examConfigureService;

    public function __construct(ResultConfigurationService $resultConfigurationService, ExamConfigureService $examConfigureService)
    {
        $this->resultConfigurationService = $resultConfigurationService;
        $this->examConfigureService = $examConfigureService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $classTestList = $this->resultConfigurationService->getByEiinId($eiinId);
            return $this->successResponse($classTestList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(ResultConfigurationStoreRequest $request)
    {
        try {
            if (!array_key_exists('examData', $request->all()) || count($request->examData) == 0) return $this->errorResponse('কমপক্ষে একজন ছাত্র/ছাত্রী হাজিরা ইনপুট দিন', Response::HTTP_NOT_ACCEPTABLE);

            $status = 'তৈরি';
            $resultConfiguration = [];

            foreach ($request->examData as $key => $data) {
                if (!array_key_exists('exam_type', $data) || empty($data['exam_type'])) return $this->errorResponse('Exam Type Is Required', Response::HTTP_NOT_ACCEPTABLE);

                // Set default full_mark for attendance and behavior if not provided
                $defaultFullMark = ($data['exam_type'] === 'attendance' || $data['exam_type'] === 'behavior') ? 100 : $data['full_mark'];
                
                $payload = [
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch_id'         => $request->branch_id,
                    'class_id'          => $request->class_id,
                    'section_id'        => $request->section_id,
                    'subject_id'        => $request->subject_id,
                    'exam_category_id'  => $request->exam_category_id ?? null,
                    'exam_type'         => $data['exam_type'],
                    'is_best'           => $data['is_best'] ?? 0,
                    'num_of_best'       => $data['is_best'] ? $data['num_of_best'] : null,
                    'full_mark'         => $defaultFullMark ?? 100,
                    'percent'           => $data['percent'] ?? 0,
                    'year'              => date('Y'),
                    'is_optional_subject'=> $request->is_optional_subject == true ? 1 : 0,
                    'is_separately_pass' => $request->is_separately_pass == true ? 1 : 0,
                ];

                if (isset($data['result_configuration_uid']) && !empty($data['result_configuration_uid'])) {
                    $status = 'আপডেট';
                    $payload['uid'] = $data['result_configuration_uid'];
                    $resultConfiguration[] = $this->resultConfigurationService->update($payload);
                } else {
                    $status = 'তৈরি';
                    $resultConfiguration[] = $this->resultConfigurationService->create($payload);
                }
            }

            // Ensure consistency: Update ALL existing exam type records for this subject to have the same is_optional_subject and is_separately_pass values
            ResultConfiguration::where([
                'branch_id' => $request->branch_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'exam_category_id' => $request->exam_category_id,
            ])->update([
                'is_optional_subject' => $request->is_optional_subject == true ? 1 : 0,
                'is_separately_pass' => $request->is_separately_pass == true ? 1 : 0,
            ]);

            $message = 'হাজিরা সফলভাবে ' . $status . ' করা হয়েছে।';
            return $this->successResponseWithData($resultConfiguration, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'হাজিরা তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($uid)
    {
        try {
            $branch = $this->resultConfigurationService->getById($uid);
            if ($branch) {
                return $this->successResponse($branch, Response::HTTP_OK);
            } else {
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->resultConfigurationService->delete($id);
        return response()->json(['status' => 'success', 'message' => 'হাজিরা তথ্যটি মুছে ফেলা হয়েছে।']);
    }


   public function subjectWiseResultConfigure(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'branch_id'  => 'required',
            'class_id'   => 'required',
            'section_id'   => 'required',
            'subject_id' => 'required',
            'exam_category_id' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
        }

        $examCategory = ExamConfigure::where('uid', $request->exam_category_id)->first();

        $examArray = [
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'exam_category_id' => $request->exam_category_id,
            'exam_type_name' => $examCategory->exam_type ?? null,
            'examData' => [
                ['result_configuration_uid'=> null, 'exam_type' => 'final_exam', 'is_best' => null, 'num_of_best' => null, 'full_mark' => 100, 'percent' => null],
                ['result_configuration_uid'=> null, 'exam_type' => 'term_exam', 'is_best' => null, 'num_of_best' => null, 'full_mark' => 100, 'percent' => null],
                ['result_configuration_uid'=> null, 'exam_type' => 'assignment', 'is_best' => null, 'num_of_best' => null, 'full_mark' => 100, 'percent' => null],
                ['result_configuration_uid'=> null, 'exam_type' => 'monthly_test', 'is_best' => null, 'num_of_best' => null, 'full_mark' => 100, 'percent' => null],
                ['result_configuration_uid'=> null, 'exam_type' => 'bi_weekly_test', 'is_best' => null, 'num_of_best' => null, 'full_mark' => 100, 'percent' => null],
                ['result_configuration_uid'=> null, 'exam_type' => 'weekly_test', 'is_best' => null, 'num_of_best' => null, 'full_mark' => 100, 'percent' => null],
                ['result_configuration_uid'=> null, 'exam_type' => 'class_test', 'is_best' => null, 'num_of_best' => null, 'full_mark' => 100, 'percent' => null],
                ['result_configuration_uid'=> null, 'exam_type' => 'attendance', 'is_best' => null, 'num_of_best' => null, 'full_mark' => 100, 'percent' => null],
                ['result_configuration_uid'=> null, 'exam_type' => 'behavior', 'is_best' => null, 'num_of_best' => null, 'full_mark' => 100, 'percent' => null],
            ],
        ];

        // Get global is_optional_subject and is_separately_pass from any existing exam type for this subject
        // If no records exist, default to 0 (not optional)
        $anyExistingConfig = ResultConfiguration::where([
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'exam_category_id' => $request->exam_category_id,
        ])->first();

        $globalOptionalSubject = $anyExistingConfig ? $anyExistingConfig->is_optional_subject : 0;
        $globalSeparatelyPass = $anyExistingConfig ? $anyExistingConfig->is_separately_pass : 0;

        // Map each exam type and fetch existing data from the database if available
        foreach ($examArray['examData'] as $key => $exam) {
            $existingConfig = ResultConfiguration::where([
                'branch_id'  => $request->branch_id,
                'class_id'   => $request->class_id,
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'exam_category_id' => $request->exam_category_id,
                'exam_type'  => $exam['exam_type'],
            ])->first();

            if ($existingConfig) {
                $examArray['examData'][$key]['result_configuration_uid'] = $existingConfig->uid;
                $examArray['examData'][$key]['is_best'] = $existingConfig->is_best;
                $examArray['examData'][$key]['num_of_best'] = $existingConfig->num_of_best;
                $examArray['examData'][$key]['full_mark'] = $existingConfig->full_mark;
                $examArray['examData'][$key]['percent'] = $existingConfig->percent;
            }

            // Apply global optional subject and separately pass settings
            $examArray['examData'][$key]['is_optional_subject'] = $globalOptionalSubject;
            $examArray['examData'][$key]['is_separately_pass'] = $globalSeparatelyPass;
        }

        return $this->successResponseWithData($examArray, '', Response::HTTP_OK);
    }

    public function generateSubjectWiseResult(Request $request)
    {
        $request->validate(rules: [
            'class_id' => 'required',
            'section_id' => 'required',
            'exam_category_id' => 'required',
            'subject_id' => 'required|exists:subjects,uid',
        ]);

        $classId = $request->class_id;
        $sectionId = $request->section_id;
        $subjectId = $request->subject_id;
        $examCategoryId = $request->exam_category_id;

        $exam_type = $this->examConfigureService->getById($examCategoryId);
        $students = Student::where('class', $classId)->where('section', $sectionId)->get();
        $marks = MarkDistribution::with('exam')
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            // ->where('exam_category_id', $examCategoryId)
            ->when($exam_type->exam_type !== 'annual_exam', function ($query) use ($examCategoryId) {
                return $query->where('exam_category_id', $examCategoryId);
            })
            ->where('year', date('Y'))
            ->get()
            ->groupBy('student_id');

        $totalClass = Attendance::where('class_id', $classId)
                        ->where('section_id', $sectionId)
                        ->where('period', 1)
                        // ->whereYear('date', 2025)
                        ->groupBy('student_id')
                        ->count();

        $attendances = Attendance::where('class_id', $classId)
                    ->where('section_id', $sectionId)
                    ->where('period', 1)
                    ->where('status', 'Present')
                    ->orWhere('status', 'Late')
                    // ->whereYear('date', 2025)
                    ->get()
                    ->groupBy('student_id');

        $resultConfigurations = ResultConfiguration::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('exam_category_id', $examCategoryId)
            ->where('year', date('Y'))
            ->get()
            ->keyBy('exam_type');

         if(count($resultConfigurations) == 0) {
            return response()->json(['status' => "false", 'message' => "দুঃখিত। রেজাল্ট কনফিগার করা হয়নি, আগে রেজাল্ট কনফিগার করে নিন।", 'code' => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
         }

        $attendanceConfig = AttendanceConfigure::where('class_id', $classId)
            ->where('section_id', $sectionId)
            // ->where('year', date('Y'))
            // ->where('subject_id', $subjectId)
            ->first();

        $rules = $attendanceConfig ? json_decode($attendanceConfig->rules, true) : [];

        $results = [
            'resultData' => [],
            'resultConfigurations' => []
        ];

        foreach ($students as $student) {
            $totalPresent = isset($attendances[$student->uid])
                            ? $attendances[$student->uid]->count()
                            : 0;

            $converAttendance = $totalClass > 0 ? ($totalPresent*100)/$totalClass : 0;

            // dd($rules);
            $attendanceMarks = 0;
            foreach ($rules as $rule) {
                if ($converAttendance >= $rule['from'] && $converAttendance < $rule['to']) {
                    $attendanceMarks = $rule['value'];
                    break;
                }
            }

            $percentAttendance = ($attendanceMarks * $resultConfigurations['attendance']->percent) / 100;


            $findResult = Result::where([
                'branch_id'  => $request->branch_id,
                'class_id'   => $classId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'student_id' => $student->uid,
                'exam_category_id' => $examCategoryId,
                'year' => date('Y')
            ])->first();

            $studentResult = [
                'student_id'        => $student->uid,
                'roll'              => $student->roll,
                'student_name'      => $student->student_name_en,
                'class'             => $student->class,
                'section'           => $student->section,
                'mcq_mark'          => null,
                'written_mark'      => null,
                'practical_mark'    => null,
                'total_marks'       => null,
                'totalExamTakenMark'=> null,
                'weighted_result'   => null,
                'converted_weighted_result' => null,
                'result_uid'        => $findResult?->uid,
                'attendance'        => $findResult?->attendance ?? $percentAttendance,
                // 'attendance'        => $percentAttendance,
                // 'attendance'        => $attendanceMarks,
                'behavior'          => $findResult?->behavior,
                'is_submitted'      => $findResult?->is_submitted ?? 0,
                'obtain_full_mark'  => number_format($findResult?->full_mark, 2),
                'is_present'  => 1,
                'result_for' => $exam_type->exam_type,
            ];

            $studentMarks = $marks->get($student->uid) ?? collect();
            foreach ($resultConfigurations as $examType => $config) {
                $examMarks = $studentMarks->where('exam_type', $examType);
                $studentResult['mark_details'][$examType] = $examMarks;

                $results['resultConfigurations'][$examType] = [
                    'percent' => $config->percent ?? 0,
                    'is_best' => $config->is_best ?? null,
                    'num_of_best' => $config->num_of_best ?? null,
                ];

                if ($examMarks->isEmpty()) continue;

                $mcq_mark = $config->is_best && $config->num_of_best
                    ? $examMarks->sortByDesc('mcq_mark')
                        ->take($config->num_of_best)
                        ->avg('mcq_mark')
                    : $examMarks->avg('mcq_mark');

                $written_mark = $config->is_best && $config->num_of_best
                    ? $examMarks->sortByDesc('written_mark')
                        ->take($config->num_of_best)
                        ->avg('written_mark')
                    : $examMarks->avg('written_mark');

                $practical_mark = $config->is_best && $config->num_of_best
                    ? $examMarks->sortByDesc('practical_mark')
                        ->take($config->num_of_best)
                        ->avg('practical_mark')
                    : $examMarks->avg('practical_mark');

                $convertedScore = $config->is_best && $config->num_of_best
                    ? $examMarks->sortByDesc('converted_full_mark')
                        ->take($config->num_of_best)
                        ->avg('converted_full_mark')
                    : $examMarks->avg('converted_full_mark');

                $score = $config->is_best && $config->num_of_best
                    ? $examMarks->sortByDesc('obtain_full_mark')
                        ->take($config->num_of_best)
                        ->avg('obtain_full_mark')
                    : $examMarks->avg('obtain_full_mark');

                $weightedScore = ($score * $config->percent) / 100;
                $convertedWeightedScore = ($convertedScore * $config->percent) / 100;

                // exam type wise brakdown mark
                $studentResult[$examType]['mcq_mark'] = number_format($mcq_mark, 2);
                $studentResult[$examType]['written_mark'] = number_format($written_mark, 2);
                $studentResult[$examType]['practical_mark'] = number_format($practical_mark, 2);

                // exam type wise score
                $studentResult[$examType]['weighted_result'] = number_format($convertedWeightedScore, 2);

                $studentResult['converted_weighted_result'] += number_format($convertedWeightedScore, 2);

                // parcent wise converted score
                if($examType == 'term_exam'|| $exam_type == 'final_exam'){
                    $studentResult['weighted_result'] += number_format($score, 2);
                    $studentResult['totalExamTakenMark'] += $examMarks[0]->exam_full_mark;
                }else{
                    $studentResult['weighted_result'] += number_format($convertedWeightedScore, 2);
                    $studentResult['totalExamTakenMark'] += $config->percent;
                }
                //total Exam Score
                $studentResult['total_marks'] += number_format($score, 2);

                if($exam_type->exam_type == 'term_exam'){
                    if($examType == 'term_exam'){
                        $studentResult['is_present'] = $examMarks->where('exam_category_id', $exam_type->uid)->first()->status;
                    }
                }elseif($exam_type->exam_type == 'final_exam'){
                    if($examType == 'final_exam'){
                        $studentResult['is_present'] = $examMarks->where('exam_category_id', $exam_type->uid)->first()->status;
                    }
                }
            }

            $results['resultData'][] = $studentResult;
        }

        if(array_key_exists('from_app', $request->all()) && $request['from_app'] == 1){
            $responseArray = json_decode(json_encode($results), true);

            foreach ($responseArray['resultData'] as &$result) {
                if (isset($result['mark_details'])) {
                    foreach ($result['mark_details'] as &$category) {
                        $category = array_values($category);
                    }
                }
            }
            $responseArray['resultConfigurations'] = array_values($responseArray['resultConfigurations']);

            return response()->json([
                'status' => 'success',
                'data' => $responseArray,
            ], 200);
        }else{
            return response()->json([
                'status' => 'success',
                'data' => $results,
            ], 200);
        }
    }



    public function transformResponse($response)
    {
        if (!isset($response['status']) || $response['status'] !== 'success') {
            return $response; // Return as is if status is not success
        }

        $data = $response['data'] ?? [];

        // Convert floating-point numbers to full precision
        $data['resultData'] = array_map(function ($student) {
            $student['mcq_mark'] = floatval($student['mcq_mark']);
            $student['written_mark'] = floatval($student['written_mark']);
            $student['practical_mark'] = floatval($student['practical_mark']);
            $student['total_marks'] = floatval($student['total_marks']);
            $student['weighted_result'] = floatval($student['weighted_result']);

            // Convert mark_details object values to arrays
            if (isset($student['mark_details']) && is_array($student['mark_details'])) {
                foreach ($student['mark_details'] as $key => $value) {
                    $student['mark_details'][$key] = array_values((array) $value);
                }
            }

            return $student;
        }, $data['resultData'] ?? []);

        // Convert resultConfigurations object to an array
        if (isset($data['resultConfigurations']) && is_array($data['resultConfigurations'])) {
            $data['resultConfigurations'] = array_map(function ($key, $config) {
                return ['type' => $key] + $config;
            }, array_keys($data['resultConfigurations']), $data['resultConfigurations']);
        }

        return [
            'status' => 'success',
            'data' => $data,
        ];
    }

    public function subjectWiseResultStore(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'branch_id'     => 'required',
                'class_id'      => 'required',
                'subject_id'    => 'required',
                'exam_category_id' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
                // return $this->error($validation->errors()->first(), 400, []);
            }

            if (!array_key_exists('resultData', $request->all()) || count($request->resultData) == 0) return $this->errorResponse('কমপক্ষে একজন ছাত্র/ছাত্রী ফলাফল ইনপুট দিন', Response::HTTP_NOT_ACCEPTABLE);

            $status = 'তৈরি';
            $result = [];

            $exam_type = $this->examConfigureService->getById($request->exam_category_id);
            $highest_mark = max(array_column($request->resultData, 'full_mark'));

            // Use values directly from request (best practice - no database dependency)
            $isOptionalSubject = $request->is_optional_subject ?? false;
            $isSeparatelyPass = $request->is_separately_pass ?? false;

            foreach ($request->resultData as $key => $data) {
                // if (!array_key_exists('attendance', $data) || empty($data['attendance'])) return $this->errorResponse('Attendance Is Required', Response::HTTP_NOT_ACCEPTABLE);


                $full_mark = ($data['mark'] ? $data['mark'] : 0) + ($data['attendance'] ? $data['attendance'] : 0) + ($data['behavior'] ? $data['behavior'] : 0);

                if ($isSeparatelyPass == 1 && $data['is_present']== 1) {
                    $mcqMark = $data['mcq_mark'] ?? 0;
                    $practicalMark = $data['practical_mark'] ?? 0;
                    $writtenMark = $data['written_mark'] ?? 0;
                    $attendance = $data['attendance'] ?? 0;
                    $behavior = $data['behavior'] ?? 0;
                    $theoryMark = $writtenMark + $attendance + $behavior +
                                (array_key_exists('class_test_mark', $data) ? $data['class_test_mark'] : 0) +
                                (array_key_exists('weekly_test_mark', $data) ? $data['weekly_test_mark'] : 0) +
                                (array_key_exists('bi_weekly_test_mark', $data) ? $data['bi_weekly_test_mark'] : 0) +
                                (array_key_exists('monthly_test_mark', $data) ? $data['monthly_test_mark'] : 0) +
                                (array_key_exists('assignment_mark', $data) ? $data['assignment_mark'] : 0);

                    $mcqPass = ($exam_type->mcq_mark > 0) ? (($mcqMark * 100) / $exam_type->mcq_mark) >= 33 : true;
                    $practicalPass = ($exam_type->practical_mark > 0) ? (($practicalMark * 100) / $exam_type->practical_mark) >= 33 : true;
                    $theoryPass = ($exam_type->written_mark > 0) ? (($theoryMark * 100) / $exam_type->written_mark) >= 33 : true;

                    if ($mcqPass && $practicalPass && $theoryPass) {
                        $resultStatus = 'Pass';
                    } else {
                        $resultStatus = 'Fail';
                    }
                } else {
                    $resultStatus = $data['is_present']== 1 ? ((($full_mark * 100)/ $exam_type->exam_full_mark) >= 33 ? 'Pass' : 'Fail') : 'Absent';
                }

                $payload = [
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch_id'         => $request->branch_id,
                    'class_id'          => $request->class_id,
                    'subject_id'        => $request->subject_id,
                    'section_id'        => $request->section_id,
                    'exam_category_id'  => $request->exam_category_id,
                    'is_submitted'      => $data['is_submitted'] ?? 0, //0 mean temp and 1 mean final
                    'student_id'        => $data['student_id'],
                    'exam_type'         => $exam_type->exam_type ?? $data['exam_type'],  //final_result or term_result
                    'mcq_mark'          => $data['mcq_mark'] ?? null,
                    'written_mark'      => $data['written_mark'] ?? null,
                    'practical_mark'    => $data['practical_mark'] ?? null,
                    'mark'              => $data['mark'] ?? null,
                    'attendance'        => $data['attendance'] ?? null,
                    'behavior'          => $data['behavior'] ?? null,
                    'full_mark'         => $full_mark,
                    'exam_taken_mark'   => $exam_type->exam_full_mark ?? 100,
                    'converted_full_mark'=> ($full_mark * 100)/ $exam_type->exam_full_mark,
                    'highest_mark'      => $highest_mark,
                    'session'           => $data['session'] ?? date('Y'),
                    'year'              => date('Y'),
                    'is_optional_subject' => $isOptionalSubject,
                    'is_separately_pass'  => $isSeparatelyPass,
                    'result_status'     => $resultStatus,
                    'grad_point'        => $resultStatus == 'Pass' ? $this->gradPointConvert(($full_mark * 100)/ $exam_type->exam_full_mark)['point'] : 0.00,
                    'grade'             => $resultStatus != 'Absent' ? ($resultStatus == 'Pass' ? $this->gradPointConvert(($full_mark * 100)/ $exam_type->exam_full_mark)['grade'] : 'F') : 'Absent',
                    'is_present'        => $data['is_present'],

                    'class_test_mark'     => array_key_exists('class_test_mark', $data) ? $data['class_test_mark'] : 0,
                    'weekly_test_mark'    => array_key_exists('weekly_test_mark', $data) ? $data['weekly_test_mark'] : 0,
                    'bi_weekly_test_mark' => array_key_exists('bi_weekly_test_mark', $data) ? $data['bi_weekly_test_mark'] : 0,
                    'monthly_test_mark'   => array_key_exists('monthly_test_mark', $data) ? $data['monthly_test_mark'] : 0,
                    'assignment_mark'     => array_key_exists('assignment_mark', $data) ? $data['assignment_mark'] : 0,

                ];

                $findResult = Result::where([
                    'branch_id'     => $payload['branch_id'],
                    'class_id'      => $payload['class_id'],
                    'section_id'    => $payload['section_id'],
                    'subject_id'    => $payload['subject_id'],
                    'student_id'    => $payload['student_id'],
                    'exam_category_id' => $payload['exam_category_id'],
                    'year'          => date('Y')
                ])->first();

                if (isset($data['result_uid']) && !empty($data['result_uid'])) {
                    $status = 'আপডেট';
                    $payload['uid'] = $data['result_uid'];
                    $result[] = $this->resultConfigurationService->resultUpdate($payload);
                }else if ($findResult) {
                    $status = 'আপডেট';
                    $payload['uid'] = $findResult->uid;
                    $result[] = $this->resultConfigurationService->resultUpdate($payload);
                }else {
                    $status = 'তৈরি';
                    $result[] = $this->resultConfigurationService->resultCreate($payload);
                }
            }
            $message = 'ফলাফল সফলভাবে ' . $status . ' করা হয়েছে।';
            return $this->successResponseWithData($result, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    private function gradPointConvert($percentage)
    {
        if ($percentage >= 80) {
            return ['point' => 5.00, 'grade' => 'A+'];
        } elseif ($percentage >= 70) {
            return ['point' => 4.00, 'grade' => 'A'];
        } elseif ($percentage >= 60) {
            return ['point' => 3.50, 'grade' => 'A-'];
        } elseif ($percentage >= 50) {
            return ['point' => 3.00, 'grade' => 'B'];
        } elseif ($percentage >= 40) {
            return ['point' => 2.00, 'grade' => 'C'];
        } elseif ($percentage >= 33) {
            return ['point' => 1.00, 'grade' => 'D'];
        } else {
            return ['point' => 0.00, 'grade' => 'F'];
        }
    }


    public function tabulationSheet(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'student_id'  => 'required',
            ]);

            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            }

            $result = $this->resultConfigurationService->findResult($request->student_id);

            if (count($result) > 0) {
                return $this->successResponse($result, Response::HTTP_OK);
            } else {
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }



        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }
    public function sectionWiseTabulationSheet(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'class_id'   => 'required',
                'section_id' => 'required',
                'exam_category_id' => 'required',
            ]);

            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            }

            $exam_type = $this->examConfigureService->getById($request->exam_category_id);

            $students = Student::where(['class' => $request->class_id])->orderBy('roll')->get();
            if(count($students) == 0) return $this->errorResponse('Student not found!', Response::HTTP_NOT_FOUND);

            $studentUIDs = $students->pluck('uid')->toArray();

            $allResults = $this->getAllResult($studentUIDs,$exam_type->exam_type);

            $studentWiseResult = $allResults->groupBy('student_id');

            // $getAttendance = $this->getAttendance($request->class_id, $request->section_id, date('Y'));

            //all mark get for annual exam calculation
            if($exam_type->exam_type == 'final_exam'){
                $marks =  $this->getMarkDistribution($request->class_id, $request->section_id, 'annual_exam', $request->exam_category_id );
            }else{
                $marks = [];
            }

            foreach ($students as $key => $student) {
                //student result from result database
                $studentAllResult = $studentWiseResult[$student->uid] ?? collect();

                $studentResult = $studentAllResult->filter(function($item) use ($student) {
                    if (!in_array(strtolower($item['subject_name_en']), ['islam', 'hindu','boddho', 'kristo' ])) {
                        return true;
                    }
                    if (strtolower($student->religion) == 'islam' && strtolower($item['subject_name_en']) == 'islam') {
                        return true;
                    }
                    if (strtolower($student->religion) == 'hindu' && strtolower($item['subject_name_en']) == 'hindu') {
                        return true;
                    }
                    if (strtolower($student->religion) == 'boddho' && strtolower($item['subject_name_en']) == 'boddho') {
                        return true;
                    }
                    if (strtolower($student->religion) == 'kristo' && strtolower($item['subject_name_en']) == 'kristo') {
                        return true;
                    }

                    return false;
                });
                // if (count($studentResult)==0) {
                //     return $this->errorResponse("এই শ্রেণির সব শিক্ষার্থীর রেজাল্ট এখনও তৈরি হয়নি। অনুগ্রহ করে রেজাল্ট জেনারেট করুন।", Response::HTTP_NOT_FOUND);
                // }

                $mark = collect($studentResult->where('exam_type', $exam_type->exam_type))->where('is_optional_subject', '!=', 1)->sum('mark');
                $attendance = collect($studentResult->where('exam_type', $exam_type->exam_type))->sum('attendance');
                $behavior = collect($studentResult->where('exam_type', $exam_type->exam_type))->sum('behavior');

                $totalFail = collect($studentResult->where('exam_type', $exam_type->exam_type))->where('is_optional_subject', '!=', 1)->where('result_status', 'Fail')->count();
                $totalAbsent = collect($studentResult->where('exam_type', $exam_type->exam_type))->where('is_present','!=', 1)->where('is_optional_subject', '!=', 1)->count();

                $is_fail = $totalFail > 0 ? 1 : 0;
                $is_present = $totalAbsent == 0 ? 1 : 0;

                $total_mark = $mark + $attendance ?? 0 + $behavior ?? 0;
                $total_grade_point = collect($studentResult->where('exam_type', $exam_type->exam_type))->where('is_optional_subject', '!=', 1)->sum('grad_point');

                // Initialize student mark data
                $studentMark = ['exam_name' => [], 'exam_percent' => []];
                
                // Get and process result configurations for subject marks calculation
                $studentMark = $this->calculateStudentMarks($request, $studentResult, $exam_type);

                // <<<<<<<<<<<<<<<<<< Only For FInal Exam >>>>>>>>>>>>>>>>>>> //
                if($exam_type->exam_type == 'final_exam'){
                    $annual_mark = collect($studentResult->where('exam_type', 'annual_exam'))->sum('mark');
                    $annual_attendance = collect($studentResult->where('exam_type', 'annual_exam'))->sum('attendance');
                    $annual_behavior = collect($studentResult->where('exam_type', 'annual_exam'))->sum('behavior');
                    $annual_is_fail = collect($studentResult->where('exam_type', 'annual_exam'))->contains(function ($item) {
                        return $item['mark'] > 0 && $item['mark'] < 33;
                    });

                    $annual_total_mark = $annual_mark + $annual_attendance ?? 0 + $annual_behavior ?? 0;

                    $studentMarks = $marks->get($student->uid) ?? collect();

                    $exam_category_id = ($studentWiseResult[$student->uid] ?? collect())
                        ->where('exam_type', 'annual_exam')
                        ->pluck('exam_category_id')
                        ->unique()
                        ->values();

                    // Process annual exam configurations
                    $studentMark = $this->processAnnualExamConfigurations(
                        $request, 
                        $exam_category_id, 
                        $studentMarks, 
                        $studentMark
                    );

                    $studentMarkSummury = [
                        'annual_mark' => $annual_mark,
                        'annual_attendance' => $annual_attendance,
                        'annual_behavior' => $annual_behavior,
                        'annual_is_fail' => $annual_is_fail,
                        'annual_total_mark' => $annual_total_mark
                    ];

                }else{
                    // For non-final exams, studentMark is already initialized above with other_percent data
                    $studentMarkSummury=[];
                }
                // <<<<<<<<<<<<<<<<<< Only For FInal Exam >>>>>>>>>>>>>>>>>>> //

                $results[] = [
                    'branch_id'         => $student->branch,
                    'section'           => $student->section,
                    'class'             => $student->class,
                    'student_id'        => $student->id,
                    'student_unique_id' => $student->student_unique_id,
                    'uid'               => $student->uid,
                    'roll'              => $student->roll,
                    'image'             => $student->image ? $this->imageToBase64($student->image) : null,
                    'student_name'      => $student->student_name_en ?? $student->student_name_bn,
                    'total_marks'       => $total_mark,
                    'total_grade_points'=> $total_grade_point,
                    'grade_point'       => count($studentResult->where('is_optional_subject', '!=', 1)) > 0 ? $total_grade_point / count($studentResult->where('is_optional_subject', '!=', 1)) : 0,
                    'is_fail'           => $is_fail,
                    'is_present'        => $is_present,
                    'total_absent'      => $totalAbsent,
                    'total_fail'        => $totalFail,
                    'total_fail_absent' => $totalFail + $totalAbsent,
                    'subjects'          => $studentResult,
                    'exam_name'         => $exam_type->exam_type == 'final_exam' ? 'Annual Exam & Yearly Result' : 'Half Yearly Exam',
                    'exam_type'         => $exam_type->exam_type,
                    'year'              => $studentResult[0]['year'] ?? date('Y'),
                    'detailsResultData' => $studentMark,
                    'studentMarkSummury' => $studentMarkSummury
                ];

            }

            $classPositionedResults = collect($results)
                ->sortBy([
                    ['is_present', 'desc'],
                    ['total_fail_absent', 'asc'],
                    ['total_grade_points', 'desc'],
                    ['total_marks', 'desc'],
                    ['roll', 'asc'],
                ])
                ->values()
                ->map(function ($item, $index) {
                    if ($item['is_fail']) {
                        $item['merit_position'] = 'Fail';
                    }else if($item['is_present']== 0){
                        $item['merit_position'] = 'Absent';
                    }else {
                        $item['merit_position'] = $index + 1;
                    }
                    return $item;
                });

            $classWiseMap = $classPositionedResults->keyBy('student_id');

            $positionedResults = collect($results)
                ->where('section' , $request->section_id)
                ->sortBy([
                    ['is_present', 'desc'],
                    ['total_fail_absent', 'asc'],
                    ['total_grade_points', 'desc'],
                    ['total_marks', 'desc'],
                    ['roll', 'asc'],
                ])
                ->values()
                ->map(function ($item, $index) use ($classWiseMap) {
                    $item['class_position'] = $classWiseMap[$item['student_id']]['merit_position'] ?? null;
                    $item['merit_position'] = $item['is_fail'] ? 'Fail' : ($item['is_present'] == 0 ? 'Absent' : $index + 1);
                    return $item;
                });

            // <<<<<<<<<<<<<<<<<< Only For FInal Exam >>>>>>>>>>>>>>>>>>> //
            if($exam_type->exam_type == 'final_exam'){

                $annualClassPositionedResults = collect($results)
                    ->sortBy([
                        ['is_fail', 'asc'],
                        ['studentMarkSummury.annual_total_mark', 'desc'],
                        ['roll', 'asc'],
                    ])
                    ->values()
                    ->map(function ($item, $index) {
                        if ($item['studentMarkSummury']['annual_is_fail']) {
                            $item['annual_merit_position'] = 'Fail';
                        }else if($item['studentMarkSummury']['annual_total_mark']== 0){
                            $item['annual_merit_position'] = 'Absent';
                        }else {
                            $item['annual_merit_position'] = $index + 1;
                        }
                        return $item;
                    });

                $annualClassWiseMap = $annualClassPositionedResults->keyBy('student_id');

                $annualPositionedResults = collect($results)
                    ->where('section' , $request->section_id)
                    ->sortBy([
                        ['is_fail', 'asc'],
                        ['studentMarkSummury.annual_total_mark', 'desc'],
                        ['roll', 'asc'],
                    ])
                    ->values()
                    ->map(function ($item, $index) use ($annualClassWiseMap) {
                        $item['annual_class_position'] = $annualClassWiseMap[$item['student_id']]['annual_merit_position'] ?? null;
                        $item['annual_merit_position'] = $item['studentMarkSummury']['annual_is_fail'] ? 'Fail' : ($item['studentMarkSummury']['annual_total_mark'] == 0 ? 'Absent' : $index + 1);
                        return $item;
                    });
            }
            // <<<<<<<<<<<<<<<<<< Only For FInal Exam >>>>>>>>>>>>>>>>>>> //

            foreach ($positionedResults as $row) {
                $storeData = [
                    'uid'           => hexdec(uniqid()),
                    'eiin'          => app('sso-auth')->user()->eiin,
                    'student_id'    => $row['uid'],
                    'branch_id'     => $row['branch_id'],
                    'class_id'      => $request->class_id,
                    'section_id'    => $request->section_id,
                    'exam_type'     => $exam_type->exam_type, // e.g. final_exam
                    'position'      => is_numeric($row['merit_position']) ? $row['merit_position'] : null,
                    'class_position'=> is_numeric($row['class_position']) ? $row['class_position'] : null,
                    'is_fail'       => $row['is_fail'],
                    'total_marks'   => $row['total_marks'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                    'year'          => date('Y'),
                ];

                MeritList::updateOrInsert([
                    'student_id'    => $row['uid'],
                    'class_id'      => $request->class_id,
                    'section_id'    => $request->section_id,
                    'exam_type'     => $exam_type->exam_type,
                    'year'          => date('Y'),
                ], $storeData);

            }


            // <<<<<<<<<<<<<<<<<< Only For FInal Exam >>>>>>>>>>>>>>>>>>> //
            if($exam_type->exam_type == 'final_exam'){
                foreach ($annualPositionedResults as $row) {
                    $storeData = [
                      'uid'           => hexdec(uniqid()),
                      'eiin'          => app('sso-auth')->user()->eiin,
                      'student_id'    => $row['uid'],
                      'branch_id'     => $row['branch_id'],
                      'class_id'      => $request->class_id,
                      'section_id'    => $request->section_id,
                      'exam_type'     => 'annual_exam', // e.g. final_exam
                      'position'      => is_numeric($row['annual_merit_position']) ? $row['annual_merit_position'] : null,
                      'class_position'=> is_numeric($row['annual_class_position']) ? $row['annual_class_position'] : null,
                      'is_fail'       => $row['studentMarkSummury']['annual_is_fail'],
                      'total_marks'   => $row['studentMarkSummury']['annual_total_mark'],
                      'created_at'    => now(),
                      'updated_at'    => now(),
                      'year'          => date('Y'),
                  ];

                  MeritList::updateOrInsert([
                      'student_id' => $row['uid'],
                      'class_id'   => $request->class_id,
                      'section_id' => $request->section_id,
                      'exam_type'  => 'annual_exam',
                      'year'       => date('Y'),
                  ], $storeData);
                }
            }
            // <<<<<<<<<<<<<<<<<< Only For FInal Exam >>>>>>>>>>>>>>>>>>> //

            $response = [
                'positionedResults' => $positionedResults,
                'classPositionedResults' => $classPositionedResults,
            ];

            return $this->successResponse($response, Response::HTTP_OK);

        } catch (Exception $e) {
            return $this->errorResponse([$e->getMessage(), $e->getLine()], Response::HTTP_NOT_FOUND);
            // return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }


    private function imageToBase64($path)
    {
        try {
            // স্পষ্টভাবে ডিস্ক উল্লেখ করুন
            if (!Storage::disk('public')->exists($path)) {
                logger("File not found: " . $path);
                return null;
            }

            $file = Storage::disk('public')->get($path);
            $mimeType = Storage::disk('public')->mimeType($path);

            // MIME টাইপ ব্যবহার করে আরও সঠিকভাবে
            return 'data:' . $mimeType . ';base64,' . base64_encode($file);
        } catch (\Exception $e) {
            logger("Image to Base64 Error: " . $e->getMessage());
            return null;
        }
    }

    private function getAllResult($studentUIDs,$exam_type){
        return Result::whereIn('student_id', $studentUIDs)
        ->join('subjects', 'results.subject_id', '=', 'subjects.uid') // Join with subjects table
        ->leftJoin('combine_subjects', 'combine_subjects.uid', '=', 'subjects.combine_subject_id') // Join with subjects table
        ->select('results.*', 'subjects.subject_code', 'subjects.subject_name_en', 'subjects.subject_name_bn', 'subjects.is_combine', 'subjects.combine_subject_id', 'combine_subjects.combine_name_en', 'combine_subjects.combine_name_bn') // Include subject_code
        ->where(function ($query) use ($exam_type) {
            if ($exam_type === 'final_exam') {
                $query->whereIn('results.exam_type', ['final_exam', 'annual_exam']);
            } else {
                $query->where('results.exam_type', $exam_type);
            }
        })
        ->orderBy('subjects.subject_code', 'asc') // Sort by subject_code in DB
        ->get();

        //  return Result::whereIn('student_id', $studentUIDs)
        //         ->with(['subject'])
        //         ->where(column: function ($query) use ($exam_type) {
        //             if ($exam_type === 'final_exam') {
        //                 $query->whereIn('exam_type', ['final_exam', 'annual_exam']);
        //             } else {
        //                 $query->where('exam_type', $exam_type);
        //             }
        //         })
        //         ->get();
    }

    private function getExamConfigure($exam_type, $classId, $sectionId){
        return ExamConfigure::where(column: function ($query) use ($exam_type) {
                if ($exam_type === 'final_exam') {
                    $query->whereIn('exam_type', ['final_exam', 'annual_exam']);
                } else {
                    $query->where('exam_type', $exam_type);
                }
            })
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('year', date('Y'))
            ->get();
    }

    private function getMarkDistribution($classId, $sectionId, $exam_type, $examCategoryId, $subjectId=null ){
        return MarkDistribution::with('exam')
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->when($subjectId !== null, function ($query) use ($subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when($exam_type !== 'annual_exam', function ($query) use ($examCategoryId) {
                return $query->where('exam_category_id', $examCategoryId);
            })
            ->where('year', date('Y'))
            ->get()
            ->groupBy('student_id');
    }

    private function getAttendance($classId, $sectionId, $year){

        $totalClassDays = Attendance::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('period', 1)
            ->whereYear('date', $year)
            ->selectRaw('COUNT(DISTINCT DATE(date)) as total')
            ->value('total');

        $presentDays = Attendance::select('student_id', DB::raw('COUNT(DISTINCT DATE(date)) as present_days'))
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('period', 1)
            ->whereIn('status', ['Present', 'Late'])
            ->whereYear('date', $year)
            ->groupBy('student_id')
            ->pluck('present_days', 'student_id');

        return [
            'totalClassDays' => $totalClassDays,
            'presentDays' => $presentDays,
        ];
    }

    public function syncMarkDistribution(Request $request)
    {
        try {
            $examConfigures = ExamConfigure::where('eiin', app('sso-auth')->user()->eiin)->get();
            $allExamConfigures = $examConfigures->whereNotIn('exam_type', ['term_exam', 'final_exam']);

            foreach($allExamConfigures as $examConfigure){
                $categoryId = $examConfigures->where('exam_type', 'term_exam')->where('subject_code', $examConfigure->subject_code)->first()->uid;
                $examConfigure->update([
                    'exam_category_id' => $categoryId,
                    'year' => date('Y'),
                ]);
            }

           $markDistribution = MarkDistribution::with('exam')->get();

            if ($markDistribution->isEmpty()) {
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }
            foreach($markDistribution as $key => $mark){
                $mark->update([
                    'subject_id' => $mark->exam->subject_code ?? null,
                    'exam_category_id' => $mark->exam->exam_category_id ?? null,
                    'year' => date('Y'),
                ]);
            }

            return $this->successResponse($markDistribution, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Calculate student marks and percentages for subjects
     */
    private function calculateStudentMarks($request, $studentResult, $exam_type)
    {
        $subjectIds = $studentResult->pluck('subject_id')->unique();
        $resultConfigurations = $this->getResultConfigurations($request, $subjectIds);
        
        $studentMark = ['exam_name' => [], 'exam_percent' => []];
        
        foreach ($resultConfigurations as $subjectId => $examTypeConfigs) {
            foreach ($examTypeConfigs as $config) {
                // Set exam type percentage
                $studentMark['exam_percent'][$subjectId][$config->exam_type . '_percent'] = $config->percent ?? 0;
                
                // Get student result for this subject and exam type
                $studentSubjectResult = $studentResult
                    ->where('subject_id', $subjectId)
                    ->where('exam_type', $exam_type->exam_type)
                    ->first();
                
                if ($studentSubjectResult) {
                    $baseScore = $this->getBaseScore($config, $studentSubjectResult, $studentResult, $subjectId);
                    $weightedScore = ($baseScore * ($config->percent ?? 0)) / 100;
                    $studentMark['exam_name'][$subjectId][$config->exam_type] = number_format($weightedScore, 2);
                }
            }
            
            // Calculate combined other values from existing data
            $this->calculateOtherMarks($studentMark, $subjectId);
        }
        
        return $studentMark;
    }

    /**
     * Process annual exam configurations
     */
    private function processAnnualExamConfigurations($request, $exam_category_id, $studentMarks, $studentMark)
    {
        $resultConfigurations = ResultConfiguration::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('year', date('Y'))
            ->whereNotNull('percent')
            ->where(function ($query) use ($exam_category_id) {
                $query->whereIn('exam_category_id', $exam_category_id)
                      ->orWhereIn('exam_type', ['attendance', 'behavior']);
            })
            ->get()
            ->groupBy('subject_id')
            ->map(function ($subjectConfigs) {
                return $subjectConfigs->groupBy('exam_type')
                    ->map(function ($examTypeConfigs) {
                        return $examTypeConfigs->sortByDesc('id')->first();
                    });
            });

        foreach ($resultConfigurations as $subjectId => $examTypeConfigs) {
            foreach ($examTypeConfigs as $config) {
                $examMarks = $studentMarks
                    ->where('subject_id', $config->subject_id)
                    ->where('exam_type', $config->exam_type);

                if ($examMarks->isEmpty()) continue;

                $score = $this->calculateExamScore($config, $examMarks);
                $weightedScore = ($score * $config->percent) / 100;

                $studentMark['exam_name'][$config->subject_id][$config->exam_type] = number_format($weightedScore, 2);
                $studentMark['exam_percent'][$config->subject_id][$config->exam_type. '_percent'] = $config->percent;
            }
            
            // Calculate combined other values from existing data
            $this->calculateOtherMarks($studentMark, $subjectId);
        }
        
        return $studentMark;
    }

    /**
     * Get result configurations with optimized query
     */
    private function getResultConfigurations($request, $subjectIds)
    {
        return ResultConfiguration::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereIn('subject_id', $subjectIds)
            ->where('year', date('Y'))
            ->whereNotNull('percent')
            ->where(function ($query) use ($request) {
                $query->where('exam_category_id', $request->exam_category_id)
                      ->orWhereIn('exam_type', ['attendance', 'behavior']);
            })
            ->get()
            ->groupBy('subject_id')
            ->map(function ($subjectConfigs) {
                return $subjectConfigs->groupBy('exam_type')
                    ->map(function ($examTypeConfigs) {
                        return $examTypeConfigs->sortByDesc('id')->first();
                    });
            });
    }

    /**
     * Get base score for different exam types
     */
    private function getBaseScore($config, $studentSubjectResult, $studentResult, $subjectId)
    {
        switch ($config->exam_type) {
            case 'term_exam':
            case 'final_exam':
                return $studentSubjectResult['mark'] ?? 0;
            
            case 'class_test':
                return $studentSubjectResult['class_test_mark'] ?? 0;
            
            case 'monthly_test':
                return $studentSubjectResult['monthly_test_mark'] ?? 0;
            
            case 'attendance':
            case 'behavior':
                return $this->getAttendanceBehaviorScore($config->exam_type, $studentSubjectResult, $studentResult, $subjectId);
            
            default:
                return 0;
        }
    }

    /**
     * Get attendance or behavior score from current or annual exam
     */
    private function getAttendanceBehaviorScore($examType, $studentSubjectResult, $studentResult, $subjectId)
    {
        $currentScore = $studentSubjectResult[$examType] ?? 0;
        
        // Check annual exam results for better score
        $annualExamResult = $studentResult
            ->where('subject_id', $subjectId)
            ->where('exam_type', 'annual_exam')
            ->first();
        
        $annualScore = $annualExamResult ? ($annualExamResult[$examType] ?? 0) : 0;
        
        return max($currentScore, $annualScore);
    }

    /**
     * Calculate exam score considering best marks configuration
     */
    private function calculateExamScore($config, $examMarks)
    {
        if ($config->is_best && $config->num_of_best) {
            return $examMarks->sortByDesc('obtain_full_mark')
                ->take($config->num_of_best)
                ->avg('obtain_full_mark');
        }
        
        return $examMarks->avg('obtain_full_mark');
    }

    /**
     * Calculate combined other_percent and other marks from existing data
     */
    private function calculateOtherMarks(&$studentMark, $subjectId)
    {
        $attendanceMarks = $this->getFloatValue($studentMark, 'exam_name', $subjectId, 'attendance');
        $behaviorMarks = $this->getFloatValue($studentMark, 'exam_name', $subjectId, 'behavior');
        $attendancePercent = $this->getIntValue($studentMark, 'exam_percent', $subjectId, 'attendance_percent');
        $behaviorPercent = $this->getIntValue($studentMark, 'exam_percent', $subjectId, 'behavior_percent');
        
        $studentMark['exam_percent'][$subjectId]['other_percent'] = $attendancePercent + $behaviorPercent;
        $studentMark['exam_name'][$subjectId]['other'] = number_format($attendanceMarks + $behaviorMarks, 2);
    }

    /**
     * Safely get float value from nested array
     */
    private function getFloatValue($array, $key1, $key2, $key3)
    {
        return isset($array[$key1][$key2][$key3]) ? floatval($array[$key1][$key2][$key3]) : 0;
    }

    /**
     * Safely get integer value from nested array
     */
    private function getIntValue($array, $key1, $key2, $key3)
    {
        return isset($array[$key1][$key2][$key3]) ? intval($array[$key1][$key2][$key3]) : 0;
    }

}

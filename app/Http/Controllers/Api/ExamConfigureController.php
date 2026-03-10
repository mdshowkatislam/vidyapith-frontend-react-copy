<?php

namespace App\Http\Controllers\Api;

use App\Helper\TeacherInfo;
use Exception;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ValidtorMapper;
use App\Services\ExamConfigureService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ExamConfigure\ExamConfigureStoreRequest;
use App\Http\Requests\ExamConfigure\ExamConfigureUpdateRequest;
use App\Models\Assignment;
use App\Models\Bi;
use App\Models\BiWeeklyTest;
use App\Models\ClassRoom;
use App\Models\ClassTest;
use App\Models\ExamConfigure;
use App\Models\FinalExam;
use App\Models\MarkDistribution;
use App\Models\MonthlyTest;
use App\Models\SubjectTeacher;
use App\Models\TermExam;
use App\Models\WeeklyTest;

use function PHPUnit\Framework\isNull;

class ExamConfigureController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $examConfigureService;

    public function __construct(ExamConfigureService $examConfigureService)
    {
        $this->examConfigureService = $examConfigureService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $examConfigureList = $this->examConfigureService->getByEiinId($eiinId);
            return $this->successResponse($examConfigureList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamConfigureStoreRequest $request)
    {
        try {
            if (!array_key_exists('section_id', $request->all()) || count($request->section_id) == 0) {
                return $this->errorResponse('Select section id please', Response::HTTP_NOT_ACCEPTABLE);
            }

            $exam = [];

            foreach ($request->section_id as $section) {
                if (is_array($request->subject_code)) {
                    foreach ($request->subject_code as $index => $subject) {
                        $payload = [
                            'eiin'              => app('sso-auth')->user()->eiin,
                            'branch_id'         => $request->branch_id,
                            'shift_id'          => $request->shift_id,
                            'version_id'        => $request->version_id,
                            'class_id'          => $request->class_id,
                            'section_id'        => $section,
                            'exam_category_id'  => $request->exam_category_id ?? null,
                            'subject_code'      => is_array($subject) ? $subject['value'] : $subject,
                            'exam_no'           => is_array($request->exam_no) ? $request->exam_no[$index] ?? null : $request->exam_no,
                            'exam_type'         => $request->exam_type,
                            'exam_name'         => $request->exam_name,
                            'mcq_mark'          => $request->mcq_mark,
                            'written_mark'      => $request->written_mark,
                            'practical_mark'    => $request->practical_mark,
                            'exam_full_mark'    => $request->exam_full_mark,
                            'exam_date'         => $request->exam_date,
                            'exam_time'         => $request->exam_time,
                            'exam_details_info' => $request->exam_details_info,
                            'status'            => $request->status ?? 1,
                            'year'              => date('Y'),
                        ];

                        $alreadyExist = $this->examConfigureService->alreadyExist($payload);
                        if (count($alreadyExist) > 0) {
                            return $this->errorResponse('বিষয়বস্তুর নাম ইতিমধ্যে বিদ্যমান', Response::HTTP_FOUND);
                        }

                        $exam[] = $this->examConfigureService->create($payload);
                        
                        if($request->exam_type == 'final_exam'){
                            $payload['exam_type'] = 'annual_exam';
                            $this->examConfigureService->create($payload);
                        }

                    }

                } else {
                    $payload = [
                        'eiin'              => app('sso-auth')->user()->eiin,
                        'branch_id'         => $request->branch_id,
                        'shift_id'          => $request->shift_id,
                        'version_id'        => $request->version_id,
                        'class_id'          => $request->class_id,
                        'section_id'        => $section,
                        'exam_category_id'  => $request->exam_category_id ?? null,
                        'subject_code'      => $request->subject_code,
                        'exam_no'           => $request->exam_no,
                        'exam_type'         => $request->exam_type,
                        'exam_name'         => $request->exam_name,
                        'mcq_mark'          => $request->mcq_mark,
                        'written_mark'      => $request->written_mark,
                        'practical_mark'    => $request->practical_mark,
                        'exam_full_mark'    => $request->exam_full_mark,
                        'exam_date'         => $request->exam_date,
                        'exam_time'         => $request->exam_time,
                        'exam_details_info' => $request->exam_details_info,
                        'status'            => $request->status ?? 1,
                        'year'              => date('Y'),
                    ];

                    $alreadyExist = $this->examConfigureService->alreadyExist($payload);
                    if (count($alreadyExist) > 0) {
                        return $this->errorResponse('বিষয়বস্তুর নাম ইতিমধ্যে বিদ্যমান', Response::HTTP_FOUND);
                    }

                    $exam[] = $this->examConfigureService->create($payload);

                    if($request->exam_type == 'final_exam'){
                        $payload['exam_type'] = 'annual_exam';
                        $this->examConfigureService->create($payload);
                    }
                    
                }
            }

            $message = 'পরীক্ষা সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($exam, $message, Response::HTTP_OK);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getById($uid)
    {
        try {
            $branch = $this->examConfigureService->getById($uid);
            if($branch){
                return $this->successResponse($branch, Response::HTTP_OK);
            }
            else{
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExamConfigureUpdateRequest $request)
    {
        try {
            $exam = [];
            foreach ($request->section_id as $key => $data) {
                $payload = [
                    'uid'               => $request->uid,
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch_id'         => $request->branch_id,
                    'shift_id'          => $request->shift_id,
                    'version_id'        => $request->version_id,
                    'class_id'          => $request->class_id,
                    'section_id'        => $data,
                    'exam_category_id'  => $request->exam_category_id ?? null,
                    'subject_code'      => $request->subject_code,
                    'exam_type'         => $request->exam_type,
                    'exam_no'           => $request->exam_no,
                    'exam_name'         => $request->exam_name,
                    'mcq_mark'          => $request->mcq_mark,
                    'written_mark'      => $request->written_mark,
                    'practical_mark'    => $request->practical_mark,
                    'exam_full_mark'    => $request->exam_full_mark,
                    'exam_date'         => $request->exam_date,
                    'exam_time'         => $request->exam_time,
                    'exam_details_info' => $request->exam_details_info,
                    'status'            => $request->status ?? 1,
                    'year'              => date('Y'),
                ];

                $exam[] = $this->examConfigureService->update($payload);
            }
            $message = 'পরীক্ষা সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($exam, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'পরীক্ষা আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mark = MarkDistribution::where('exam_id', $id)->count();
        if ($mark > 0) {
            return $this->errorResponse('এই পরীক্ষার মার্ক ডিস্ট্রিবিউশন বিদ্যমান।', Response::HTTP_FORBIDDEN);
        }else {
            $this->examConfigureService->delete($id);
        }
        return response()->json(['status' => 'success', 'message' => 'পরীক্ষা তথ্যটি মুছে ফেলা হয়েছে।']);
    }

    public function categoryWiseExam(Request $request){

        $validation = Validator::make($request->all(), [
            'exam_category'  => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
        }

       $eiinId = getAuthInfo()['eiin'];
              $user_type_id = getAuthInfo()['user_type_id'];
      
            if($user_type_id == 1){
                $teacher = TeacherInfo::teacherInfo();
                if($teacher['teacher_type'] == 'subject_teacher'){
                    $subject_id = $teacher['data']->pluck('subject_uid')->unique();
                    $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                    $section_id = ClassRoom::whereIn('uid', $class_room_uid)->pluck('section_id')->unique();
                    $exam = ExamConfigure::where([
                            'eiin' => $eiinId,
                            'exam_type' => $request->exam_category,
                            'status' => 1,
                        ])->whereIn('section_id', $section_id)
                          ->whereIn('subject_code', $subject_id)->get();

                }else if($teacher['teacher_type'] == 'class_teacher'){
                    $uid = $teacher['data']->pluck('uid')->unique();
                    $section_id = $teacher['data']->pluck('section_id')->unique();
                    $subject_id = SubjectTeacher::whereIn('class_room_uid', $uid)->pluck('subject_id')->unique();
                    $exam = ExamConfigure::where([
                            'eiin' => $eiinId,
                            'exam_type' => $request->exam_category,
                            'status' => 1,
                        ])->whereIn('section_id', $section_id)
                          ->whereIn('subject_code', $subject_id)->get();
                }
            }else{
                $exam = ExamConfigure::where([
                            'exam_configures.eiin' => $eiinId,
                            'exam_type' => $request->exam_category,
                            'status' => 1,
                        ])
                        ->select('exam_configures.*')
                        ->join('class_names', 'class_names.uid', '=', 'exam_configures.class_id')
                        ->join('subjects', 'subjects.uid', '=', 'exam_configures.subject_code')
                        ->orderBy('class_names.id', 'asc')
                        ->orderBy('subjects.subject_code', 'asc')
                        ->orderBy('exam_configures.id', 'desc')
                        ->get();

            }

        return $this->successResponseWithData($exam, '', Response::HTTP_OK);
    }

}


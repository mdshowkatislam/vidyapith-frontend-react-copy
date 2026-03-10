<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ValidtorMapper;
use App\Services\ClassTestService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ClassTest\ClassTestStoreRequest;
use App\Http\Requests\ClassTest\ClassTestUpdateRequest;
use App\Models\Assignment;
use App\Models\BiWeeklyTest;
use App\Models\ClassTest;
use App\Models\FinalExam;
use App\Models\MonthlyTest;
use App\Models\TermExam;
use App\Models\WeeklyTest;

use function PHPUnit\Framework\isNull;

class ClassTestController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $classTestService;

    public function __construct(ClassTestService $classTestService)
    {
        $this->classTestService = $classTestService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $classTestList = $this->classTestService->getByEiinId($eiinId);
            return $this->successResponse($classTestList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassTestStoreRequest $request)
    {
        try {
            if(!array_key_exists('section_id', $request->all()) || count($request->section_id) == 0) return $this->errorResponse('Select section id please', Response::HTTP_NOT_ACCEPTABLE);

            $class_test = [];
            foreach ($request->section_id as $key => $data) {
                $payload = [
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch_id'         => $request->branch_id,
                    'shift_id'          => $request->shift_id,
                    'version_id'        => $request->version_id,
                    'class_id'          => $request->class_id,
                    'section_id'        => $data,
                    'subject_code'      => $request->subject_code,
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
                ];

                $alreadyExist = $this->classTestService->alreadyExist($payload);

                if(count($alreadyExist)>0) return $this->errorResponse('বিষয়বস্তুর নাম ইতিমধ্যে বিদ্যমান', Response::HTTP_FOUND);

                $class_test[] = $this->classTestService->create($payload);
            }
            $message = 'শ্রেণি পরীক্ষা সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($class_test, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'শ্রেণি পরীক্ষা তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($uid)
    {
        try {
            $branch = $this->classTestService->getById($uid);
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
    public function update(ClassTestUpdateRequest $request)
    {
        try {
            $class_test = [];
            foreach ($request->section_id as $key => $data) {
                $payload = [
                    'uid'               => $request->uid,
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch_id'         => $request->branch_id,
                    'shift_id'          => $request->shift_id,
                    'version_id'        => $request->version_id,
                    'class_id'          => $request->class_id,
                    'section_id'        => $data,
                    'subject_code'      => $request->subject_code,
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
                ];

                $class_test[] = $this->classTestService->update($payload);
            }
            $message = 'শ্রেণি পরীক্ষা সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($class_test, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'শ্রেণি পরীক্ষা আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->classTestService->delete($id);
        return response()->json(['status' => 'success', 'message' => 'শ্রেণি পরীক্ষা তথ্যটি মুছে ফেলা হয়েছে।']);
    }

    public function categoryWiseExam(Request $request){

        $validation = Validator::make($request->all(), [
            'exam_category'  => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
        }

        $eiinId = app('sso-auth')->user()->eiin;
        
        // if($request->exam_category == 'class_test'){
        //     $testData = ClassTest::where([
        //                             'eiin' => $eiinId,
        //                             'status' => 1,
        //                         ])->get();

        //     return $this->successResponseWithData($testData, '', Response::HTTP_OK);
        // }else if($request->exam_category == 'weekly_test'){
        //     $testData = WeeklyTest::where([
        //         'eiin' => $eiinId,
        //         'status' => 1,
        //     ])->get();

        //     return $this->successResponseWithData($testData, '', Response::HTTP_OK);
        // }else if($request->exam_category == 'bi_weekly_test'){
        //     $testData = BiWeeklyTest::where([
        //         'eiin' => $eiinId,
        //         'status' => 1,
        //     ])->get();

        //     return $this->successResponseWithData($testData, '', Response::HTTP_OK);
        // }else if($request->exam_category == 'monthly_test'){
        //     $testData = MonthlyTest::where([
        //         'eiin' => $eiinId,
        //         'status' => 1,
        //     ])->get();

        //     return $this->successResponseWithData($testData, '', Response::HTTP_OK);
        // }else if($request->exam_category == 'assignment'){
        //     $testData = Assignment::where([
        //         'eiin' => $eiinId,
        //         'status' => 1,
        //     ])->get();

        //     return $this->successResponseWithData($testData, '', Response::HTTP_OK);
        // }else if($request->exam_category == 'term_exam'){
        //     $testData = TermExam::where([
        //         'eiin' => $eiinId,
        //         'status' => 1,
        //     ])->get();

        //     return $this->successResponseWithData($testData, '', Response::HTTP_OK);
        // }else if($request->exam_category == 'final_exam'){
        //     $testData = FinalExam::where([
        //         'eiin' => $eiinId,
        //         'status' => 1,
        //     ])->get();

        //     return $this->successResponseWithData($testData, '', Response::HTTP_OK);
        // }
        // else{
        //     $message = 'Not Found Any Exam For This Exam Type';
        //     return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        // }

        $testData = ClassTest::where([
                    'eiin' => $eiinId,
                    'status' => 1,
                ])->get();

        return $this->successResponseWithData($testData, '', Response::HTTP_OK);
    }
}

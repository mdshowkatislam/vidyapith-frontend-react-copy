<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\MarkDistribution\MarkDistributionStoreRequest;
use App\Http\Requests\MarkDistribution\MarkDistributionUpdateRequest;
use App\Models\Assignment;
use App\Models\BiWeeklyTest;
use App\Models\ClassTest;
use App\Models\ExamConfigure;
use App\Models\FinalExam;
use App\Models\MarkDistribution;
use App\Models\MonthlyTest;
use App\Models\TermExam;
use App\Models\WeeklyTest;
use App\Services\ExamConfigureService;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\MarkDistributionService;
use Exception;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNan;
use function PHPUnit\Framework\isNull;

class MarkDistributionController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $markDistributionService;

    public function __construct(MarkDistributionService $markDistributionService)
    {
        $this->markDistributionService = $markDistributionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $classTestList = $this->markDistributionService->getByEiinId($eiinId);
            return $this->successResponse($classTestList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarkDistributionStoreRequest $request)
    {
        try {
            if(!array_key_exists('studentData', $request->all()) || count($request->studentData) == 0) return $this->errorResponse('কমপক্ষে একজন ছাত্র/ছাত্রী নাম্বার ইনপুট দিন', Response::HTTP_NOT_ACCEPTABLE);

            $status = 'তৈরি';
            $markDistribution = [];
            foreach ($request->studentData as $key => $data) {
               
                if($request->examFullmark < ((array_key_exists('mark_mcq' , $data) ? $data['mark_mcq'] : 0) + (array_key_exists('mark_written' , $data) ? $data['mark_written'] : 0 )+ (array_key_exists('practical' , $data) ? $data['practical'] : 0)))
                    return $this->errorResponse('পূর্ণ নম্বরের চেয়ে বেশি নম্বর ইনপুট দিতে পারবেন না', Response::HTTP_FOUND);

                $examType = $request->exam_type;
                $examId = $request->exam_id;
                
                $findExam = ExamConfigure::where('uid', $examId)->orWhere('id', $examId)->first();
                
                $fullMark = (array_key_exists('mark_mcq' , $data)? $data['mark_mcq'] : 0) + (array_key_exists('mark_written' , $data)? $data['mark_written'] : 0) + (array_key_exists('practical' , $data)? $data['practical'] : 0);
                
            
                $convertedMark = ($fullMark * 100)/$findExam->exam_full_mark;

                $payload = [
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'class_id'          => $request->class_id,
                    'section_id'        => $request->section_id,
                    'subject_id'        => $findExam->subject_code,
                    'exam_category_id'  => $findExam->exam_category_id ? $findExam->exam_category_id : $findExam->uid ,
                    'exam_type'         => $request->exam_type,
                    'exam_id'           => $request->exam_id,
                    'exam_full_mark'    => $examType == 'assignment' ? $findExam->assignment_full_mark : $findExam->exam_full_mark,
                    'is_submitted'      => $request->is_submitted ?? 0, //0 mean temp and 1 mean final
                    'student_id'        => $data['uid'],
                    'mcq_mark'          => array_key_exists('mark_mcq' , $data)? $data['mark_mcq'] : null,
                    'written_mark'      => array_key_exists('mark_written' , $data)? $data['mark_written'] : null,
                    'practical_mark'    => array_key_exists('practical' , $data)? $data['practical'] : null,
                    'obtain_full_mark'  => $fullMark,
                    'converted_full_mark'=> $convertedMark,
                    'status'            => (array_key_exists('mark_written' , $data)) || (array_key_exists('mark_mcq' , $data)) || (array_key_exists('practical' , $data)) ? 1 : 0,
                    'remark'            => array_key_exists('remark' , $data)? $data['remark'] : null,
                    'year'              => date('Y'),
                ];

                $alreadyExists = $this->markDistributionService->alreadyExists($payload);
                
                if (isset($data['mark_distribution_uid']) && !empty($data['mark_distribution_uid'])) {
                    $status = 'আপডেট';
                    $payload['uid'] = $data['mark_distribution_uid'];
                    $markDistribution[] = $this->markDistributionService->update($payload);
                }else if ($alreadyExists) {
                    $status = 'আপডেট';
                    $payload['uid'] = $alreadyExists->uid;
                    $markDistribution[] = $this->markDistributionService->update($payload);
                }
                else{
                    $status = 'তৈরি';
                    $markDistribution[] = $this->markDistributionService->create($payload);
                }
            }
            $message = 'মার্ক ডিস্ট্রিবিউশন সফলভাবে '. $status .' করা হয়েছে।';
            return $this->successResponseWithData($markDistribution, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'মার্ক ডিস্ট্রিবিউশন তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($uid)
    {
        try {
            $branch = $this->markDistributionService->getById($uid);
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
    public function update(MarkDistributionUpdateRequest $request)
    {
        try {
            $markDistribution = [];
            foreach ($request->studentData as $key => $data) {
                $payload = [
                    'uid'               => $data['uid'],
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'class_id'          => $request->class_id,
                    'section_id'        => $request->section_id,
                    'exam_type'         => $request->exam_type,
                    'exam_id'           => $request->exam_id,
                    'student_id'        => $data['uid'],
                    'mcq_mark'          => $data['mark_mcq'] ?? null,
                    'written_mark'      => $data['mark_written'] ?? null,
                    'practical_mark'    => $data['practical'] ?? null,
                    'status'            => (empty($data['mark_mcq']) && empty($data['mark_written']) && empty($data['practical'])) ? 0 : 1,
                    'remark'            => $data['remark'] ?? null,
                ];

                $markDistribution = $this->markDistributionService->update($payload);
            }
            $message = 'মার্ক ডিস্ট্রিবিউশন সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($markDistribution, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'মার্ক ডিস্ট্রিবিউশন আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarkDistributionStoreRequest $request)
    {
        $id = [
            'eiin'              => app('sso-auth')->user()->eiin,
            'class_id'          => $request->class_id,
            'section_id'        => $request->section_id,
            'exam_type'         => $request->exam_type,
            'exam_id'           => $request->exam_id,
        ];

        $this->markDistributionService->delete($id);
        return response()->json(['status' => 'success', 'message' => 'মার্ক ডিস্ট্রিবিউশন তথ্যটি মুছে ফেলা হয়েছে।']);
    }


    public function markDistributionEditModeOn(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'branch_id' => 'required|integer',
                'version_id' => 'required|integer',
                'shift_id' => 'required|integer',
                'class_id' => 'required|integer',
                'section_id' => 'required|integer',
                'exam_type' => 'required|string',
                'exam_id' => 'required|integer',
                'edit_mode' => 'required|integer',
                'mark_dis_result_gen' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($this->getErrorMessage($validator), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $exam = ExamConfigure::on('db_read')->select('uid',  'subject_code', 'exam_category_id')->where('uid', $request->exam_id)->first();
            $data = [];
            if($request->mark_dis_result_gen =='mark_distribution'){
                $mark = MarkDistribution::where([
                            'class_id'          => $request->class_id,
                            'section_id'        => $request->section_id,
                            'subject_id'        => $exam->subject_code,
                            'exam_type'         => $request->exam_type,
                            'exam_category_id'  => $exam->exam_category_id ?? $exam->uid,
                            'exam_id'           => $request->exam_id,
                            'year'              => date('Y'),
                        ])->update(['is_submitted' => $request->edit_mode ?? 0]);
                $data = $mark;
            }elseif($request->mark_dis_result_gen =='result_genreration'){
                $result = Result::where([
                                'branch_id'         => $request->branch_id,
                                'class_id'          => $request->class_id,
                                'section_id'        => $request->section_id,
                                'subject_id'        => $exam->subject_code,
                                'exam_category_id'  => $exam->exam_category_id ?? $exam->uid,
                                'year'              => date('Y')
                            ])->update(['is_submitted' => $request->edit_mode ?? 0]);
                $data = $result;
            }

            $message = 'সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($data, $message, Response::HTTP_OK);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

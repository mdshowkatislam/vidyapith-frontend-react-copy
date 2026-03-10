<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiReview;
use App\Services\BiEvaluationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use Exception;

class BiEvalutionV2Controller extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $biEvaluationService;

    public function __construct(BiEvaluationService $biEvaluationService)
    {
        $this->biEvaluationService = $biEvaluationService;
    }

    public function store(Request $request)
    {
        try {
            $evaluation_data = $request->all();
            $this->biEvaluationService->create($evaluation_data);

            $message = 'মূল্যায়ন সফলভাবে সংরক্ষণ করা হয়েছে।';
            return $this->successResponse($message, Response::HTTP_OK);
        } catch (Exception $exc) {
            // return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
            $message = 'মূল্যায়ন সংরক্ষণ করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getBiEvaluationByBi(Request $request)
    {
        try {
            $request_data = $request->all();
            $data['evaluation'] = $this->biEvaluationService->getByBi($request_data);

            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function storeBiReview(Request $request)
    {
        try {
            $exist_review = BiReview::where('evaluate_type', $request->evaluate_type)
                ->where('subject_uid', $request->subject_uid)
                ->where('teacher_uid', $request->teacher_uid)
                ->where('class_room_uid', $request->class_room_uid)
                ->where('is_approved', 0)
                ->first();
            if ($exist_review) {
                return $this->errorResponse('এই BI এর জন্য ইতিমধ্যে পুনঃমূল্যায়নের আবেদন করা হয়েছে।', Response::HTTP_NOT_FOUND);
            }
            $data = new BiReview();
            $data->evaluate_type = $request->evaluate_type;
            $data->subject_uid = @$request->subject_uid;
            $data->teacher_uid = $request->teacher_uid;
            $data->class_room_uid = @$request->class_room_uid;
            $data->remark = @$request->remark;
            $data->session = @$request->session ?? date('Y');
            $data->save();
            // return $this->successResponse($data, Response::HTTP_OK);
            $message = 'পুনঃমূল্যায়নের আবেদনটি সংরক্ষণ করা হয়েছে।';
            return $this->successResponseWithData($data, $message, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}

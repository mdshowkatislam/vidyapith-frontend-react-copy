<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiEvaluation;
use App\Models\BiReview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use Exception;

class BiEvalutionController extends Controller
{
    use ApiResponser, ValidtorMapper;

    public function store(Request $request)
    {
        try {
            $evaluation_data = $request->all();
            foreach ($evaluation_data as $list) {
                $exist = BiEvaluation::on('db_evaluation')->where('student_uid', $list['student_uid'])
                    ->where('subject_uid', $list['subject_uid'])
                    ->where('bi_uid', $list['bi_uid'])
                    ->where('evaluate_type', $list['evaluate_type'])
                    ->first();

                if ($exist) {
                    $data = $exist;
                } else {
                    $data = new BiEvaluation();
                    $data->setConnection('db_evaluation');
                }
                $data->evaluate_type = $list['evaluate_type'];
                $data->bi_uid = $list['bi_uid'];
                $data->weight_uid = $list['weight_uid'];
                $data->student_uid = $list['student_uid'];
                $data->teacher_uid = $list['teacher_uid'];
                $data->subject_uid = $list['subject_uid'];
                $data->class_room_uid = @$list['class_room_uid'];
                $data->submit_status = $list['submit_status'];
                $data->is_approved = $list['is_approved'];
                $data->remark = $list['remark'];

                $data->save();
            }
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function getBiEvaluationByBi(Request $request)
    {
        try {
            $data['evaluation'] = BiEvaluation::on('db_evaluation')
                // ->select('evaluate_type', 'bi_uid', 'subject_uid', 'weight_uid', 'teacher_uid', 'student_uid', 'remark', 'submit_status')
                ->where('class_room_uid', $request->class_room_uid)
                ->where('subject_uid', $request->subject_uid)
                ->where('student_uid', $request->student_uid)
                ->where('evaluate_type', $request->evaluate_type)
                ->get();
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
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}

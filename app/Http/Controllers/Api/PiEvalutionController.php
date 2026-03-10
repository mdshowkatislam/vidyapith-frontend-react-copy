<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\PiBiReview;
use App\Models\PiEvaluation;
use App\Models\PiReview;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use Exception;

class PiEvalutionController extends Controller
{
    use ApiResponser, ValidtorMapper;

    public function store(Request $request)
    {
        try {
            $evaluation_data = $request->all();
            foreach ($evaluation_data as $list) {
                $exist = PiEvaluation::on('db_evaluation')->where('student_uid', $list['student_uid'])
                    ->where('pi_uid', $list['pi_uid'])
                    ->where('competence_uid', $list['competence_uid'])
                    ->where('oviggota_uid', $list['oviggota_uid'])
                    ->where('evaluate_type', $list['evaluate_type'])
                    ->first();

                if ($exist) {
                    $data = $exist;
                } else {
                    $data = new PiEvaluation();
                    $data->setConnection('db_evaluation');
                }
                $data->evaluate_type = $list['evaluate_type'];
                $data->competence_uid = $list['competence_uid'];
                $data->pi_uid = ($list['pi_uid'] == 1782361456301501 ? 1780295997137366 : $list['pi_uid']);
                $data->oviggota_uid = @$list['oviggota_uid'] != 0 ? @$list['oviggota_uid'] : NULL;
                $data->subject_uid = @$list['subject_uid'];
                $data->weight_uid = $list['weight_uid'];
                $data->student_uid = $list['student_uid'];
                $data->teacher_uid = $list['teacher_uid'];
                $data->class_room_uid = @$list['class_room_uid'];
                $data->submit_status = $list['submit_status'];
                $data->is_approved = $list['is_approved'];
                $data->remark = $list['remark'];
                $data->session = @$list['session'] ?? date('Y');
                $data->save();
            }
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function getPiEvaluationByPi(Request $request)
    {
        try {
            $data['evaluation'] = PiEvaluation::on('db_evaluation')
                // ->select('evaluate_type', 'oviggota_uid', 'pi_uid', 'weight_uid', 'teacher_uid', 'student_uid', 'remark', 'submit_status')
                ->where('class_room_uid', $request->class_room_uid)
                ->where('pi_uid', $request->pi_uid)
                ->where('evaluate_type', $request->evaluate_type)
                // ->where('oviggota_uid', @$request->oviggota_uid)
                ->when($request->has('oviggota_uid'), function ($query) use ($request) {
                    return $query->where('oviggota_uid', @$request->oviggota_uid);
                })
                ->get();
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function storePiReview(Request $request)
    {
        try {
            $exist_review = PiReview::where('evaluate_type', $request->evaluate_type)
                ->where('pi_uid', $request->pi_uid)
                ->where('teacher_uid', $request->teacher_uid)
                ->where('class_room_uid', $request->class_room_uid)
                ->where('is_approved', 0)
                ->first();
            if ($exist_review) {
                return $this->errorResponse('এই PI এর জন্য ইতিমধ্যে পুনঃমূল্যায়নের আবেদন করা হয়েছে।', Response::HTTP_NOT_FOUND);
            }
            $data = new PiReview();
            $data->evaluate_type = $request->evaluate_type;
            $data->oviggota_uid = (@$request->oviggota_uid != 0 ? @$request->oviggota_uid : NULL);
            $data->pi_uid = (@$request->pi_uid == 1782361456301501 ? 1780295997137366 : @$request->pi_uid);
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

<?php

namespace App\Repositories;

use App\Models\PiEvaluation\PiEvaluation6C;
use App\Models\PiEvaluation\PiEvaluation6H;
use App\Models\PiEvaluation\PiEvaluation6Y;
use App\Models\PiEvaluation\PiEvaluation7C;
use App\Models\PiEvaluation\PiEvaluation7H;
use App\Models\PiEvaluation\PiEvaluation7Y;
use App\Models\PiEvaluation\PiEvaluation8C;
use App\Models\PiEvaluation\PiEvaluation8H;
use App\Models\PiEvaluation\PiEvaluation8Y;
use App\Models\PiEvaluation\PiEvaluation9C;
use App\Models\PiEvaluation\PiEvaluation9H;
use App\Models\PiEvaluation\PiEvaluation9Y;
use App\Repositories\Interfaces\PiEvaluationRepositoryInterface;

class PiEvaluationRepository implements PiEvaluationRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function create($evaluation_data)
    {
        $model = $this->getModel($evaluation_data[0]['evaluate_type'], $evaluation_data[0]['class_uid']);

        foreach ($evaluation_data as $list) {
            $exist = $model::on('db_evaluation')->where('student_uid', $list['student_uid'])
                ->where('pi_uid', $list['pi_uid'])
                ->where('competence_uid', $list['competence_uid'])
                ->where('oviggota_uid', $list['oviggota_uid'])
                ->where('evaluate_type', $list['evaluate_type'])
                ->first();
            if ($exist) {
                $data = $exist;
            } else {
                $data = new $model();
                $data->setConnection('db_evaluation');
            }

            $data->evaluate_type = $list['evaluate_type'];
            $data->competence_uid = $list['competence_uid'];
            $data->pi_uid = $list['pi_uid'];
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
    }

    public function getByPi($request_data)
    {
        $model = $this->getModel($request_data['evaluate_type'], $request_data['class_uid']);
        $data = $model::on('db_evaluation')
                ->where('class_room_uid', $request_data['class_room_uid'])
                ->where('pi_uid', $request_data['pi_uid'])
                ->where('evaluate_type', $request_data['evaluate_type'])
                ->when(isset($request_data['oviggota_uid']), function ($query) use ($request_data) {
                    return $query->where('oviggota_uid', @$request_data['oviggota_uid']);
                })
                ->get();
        return $data;
    }

    public function getModel($evaluate_type, $class_uid)
    {
        if ($evaluate_type == '1234567890') {
            if ($class_uid == 6) {
                $model = PiEvaluation6C::class;
            } elseif ($class_uid == 7) {
                $model = PiEvaluation7C::class;
            } elseif ($class_uid == 8) {
                $model = PiEvaluation8C::class;
            } elseif ($class_uid == 9) {
                $model = PiEvaluation9C::class;
            }
        } elseif ($evaluate_type == '1234567891') {
            if ($class_uid == 6) {
                $model = PiEvaluation6H::class;
            } elseif ($class_uid == 7) {
                $model = PiEvaluation7H::class;
            } elseif ($class_uid == 8) {
                $model = PiEvaluation8H::class;
            } elseif ($class_uid == 9) {
                $model = PiEvaluation9H::class;
            }
        } elseif ($evaluate_type == '1234567892') {
            if ($class_uid == 6) {
                $model = PiEvaluation6Y::class;
            } elseif ($class_uid == 7) {
                $model = PiEvaluation7Y::class;
            } elseif ($class_uid == 8) {
                $model = PiEvaluation8Y::class;
            } elseif ($class_uid == 9) {
                $model = PiEvaluation9Y::class;
            }
        }

        return $model;
    }
}

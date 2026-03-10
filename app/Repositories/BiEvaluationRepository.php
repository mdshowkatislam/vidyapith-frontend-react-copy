<?php

namespace App\Repositories;

use App\Models\BiEvaluation\BiEvaluation6H;
use App\Models\BiEvaluation\BiEvaluation6Y;
use App\Models\BiEvaluation\BiEvaluation7H;
use App\Models\BiEvaluation\BiEvaluation7Y;
use App\Models\BiEvaluation\BiEvaluation8H;
use App\Models\BiEvaluation\BiEvaluation8Y;
use App\Models\BiEvaluation\BiEvaluation9H;
use App\Models\BiEvaluation\BiEvaluation9Y;
use App\Repositories\Interfaces\BiEvaluationRepositoryInterface;

class BiEvaluationRepository implements BiEvaluationRepositoryInterface
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
                ->where('subject_uid', $list['subject_uid'])
                ->where('bi_uid', $list['bi_uid'])
                ->where('evaluate_type', $list['evaluate_type'])
                ->first();

            if ($exist) {
                $data = $exist;
            } else {
                $data = new $model();
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
    }

    public function getByBi($request_data)
    {
        $model = $this->getModel($request_data['evaluate_type'], $request_data['class_uid']);
        $data = $model::on('db_evaluation')
            ->where('class_room_uid', $request_data['class_room_uid'])
            ->where('subject_uid', $request_data['subject_uid'])
            ->where('student_uid', $request_data['student_uid'])
            ->where('evaluate_type', $request_data['evaluate_type'])
            ->get();
        return $data;
    }

    public function getModel($evaluate_type, $class_uid)
    {
        if ($evaluate_type == '1234567893') {
            if ($class_uid == 6) {
                $model = BiEvaluation6H::class;
            } elseif ($class_uid == 7) {
                $model = BiEvaluation7H::class;
            } elseif ($class_uid == 8) {
                $model = BiEvaluation8H::class;
            } elseif ($class_uid == 9) {
                $model = BiEvaluation9H::class;
            }
        } elseif ($evaluate_type == '1234567894') {
            if ($class_uid == 6) {
                $model = BiEvaluation6Y::class;
            } elseif ($class_uid == 7) {
                $model = BiEvaluation7Y::class;
            } elseif ($class_uid == 8) {
                $model = BiEvaluation8Y::class;
            } elseif ($class_uid == 9) {
                $model = BiEvaluation9Y::class;
            }
        }

        return $model;
    }
}

<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ResultConfigurationRepositoryInterface;
use App\Models\ResultConfiguration;
use App\Models\Result;

class ResultConfigurationRepository implements ResultConfigurationRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return ResultConfiguration::on('db_read')->with('branch')->get();
    }

    public function alreadyExist($data)
    {
        return ResultConfiguration::where([
            'branch_id'     => $data['branch_id'],
            'class_id'      => $data['class_id'],
            'subject_id'    => $data['subject_id'],
        ])->whereNull('deleted_at')->get();
    }

    public function create($data)
    {
        return ResultConfiguration::create($data);
    }

    public function update($data)
    {
        $attendance = ResultConfiguration::where('uid', $data['uid'])->first();

        if ($attendance){
            $attendance->branch_id         = $data['branch_id'];
            $attendance->class_id          = $data['class_id'];
            $attendance->section_id        = $data['section_id'];
            $attendance->subject_id        = $data['subject_id'];
            $attendance->exam_category_id  = $data['exam_category_id'] ?? null;
            $attendance->exam_type         = $data['exam_type'];
            $attendance->is_best           = $data['is_best'];
            $attendance->num_of_best       = $data['num_of_best'];
            $attendance->full_mark         = $data['full_mark'];
            $attendance->percent           = $data['percent'];
            $attendance->year              = $data['year'];
            $attendance->is_optional_subject = $data['is_optional_subject'];
            $attendance->is_separately_pass  = $data['is_separately_pass'];
            $attendance->save();
            return $attendance;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
       // return ResultConfiguration::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_no', 'exam_name', 'exam_full_mark', 'exam_date', 'exam_time', 'exam_start_time', 'exam_end_time', 'exam_details_info', 'status')->where('uid', $id)->first();

        return ResultConfiguration::on('db_read')
            ->with(['branch' => function($query) {
                $query->select('id', 'branch_name');
            }])
            ->with(['version' => function($query) {
                $query->select('id', 'version_name_bn');
            }])
            ->with(['shift' => function($query) {
                $query->select('id', 'shift_name_bn');
            }])
            ->with(['className' => function($query) {
                $query->select('id', 'class_name_en', 'class_name_bn');
            }])
            ->with(['section' => function($query) {
                $query->select('id', 'section_name');
            }])
            ->with(['subject' => function($query) {
                $query->select('id', 'subject_name_en');
            }])
            ->where('uid', $id)
            ->first();
    }

    // public function getByEiinId($eiin, $optimize = null)
    // {
    //     return ResultConfiguration::on('db_read')->with('branch')->where('eiin', $eiin)->get();
    // }

    public function getByEiinId($eiin, $optimize = null)
    {
        return ResultConfiguration::on('db_read')
            ->with(['branch' => function($query) {
                $query->select('id', 'branch_name');
            }])
            ->with(['version' => function($query) {
                $query->select('id', 'version_name_bn');
            }])
            ->with(['shift' => function($query) {
                $query->select('id', 'shift_name_bn');
            }])
            ->with(['className' => function($query) {
                $query->select('id', 'class_name_en', 'class_name_bn');
            }])
            ->with(['section' => function($query) {
                $query->select('id', 'section_name');
            }])
            ->with(['subject' => function($query) {
                $query->select('id', 'subject_name_en');
            }])
            ->where('eiin', $eiin)
            ->get();
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return ResultConfiguration::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return ResultConfiguration::where('uid', $id)->delete();
    }


    public function resultCreate($data)
    {
        return Result::create($data);
    }

    public function resultUpdate($data)
    {
        $result = Result::where('uid', $data['uid'])->first();

        if ($result){
            $result->branch_id         = $data['branch_id'];
            $result->class_id          = $data['class_id'];
            $result->subject_id        = $data['subject_id'];
            $result->is_submitted      = $data['is_submitted'];
            $result->student_id        = $data['student_id'];
            $result->exam_type         = $data['exam_type'];

            $result->mcq_mark          = $data['mcq_mark'];
            $result->written_mark      = $data['written_mark'];
            $result->practical_mark    = $data['practical_mark'];

            $result->mark              = $data['mark'];
            $result->attendance        = $data['attendance'];
            $result->behavior          = $data['behavior'];
            $result->full_mark         = $data['full_mark'];
            $result->exam_taken_mark   = $data['exam_taken_mark'];
            $result->converted_full_mark = $data['converted_full_mark'];
            $result->highest_mark      = $data['highest_mark'];
            $result->session           = $data['session'];
            $result->year              = $data['year'];
            $result->is_optional_subject = $data['is_optional_subject'];
            $result->is_separately_pass  = $data['is_separately_pass'];
            $result->result_status      = $data['result_status'];
            $result->grad_point         = $data['grad_point'];
            $result->grade              = $data['grade'];
            $result->is_present         = $data['is_present'];
            $result->class_test_mark    = $data['class_test_mark'];
            $result->weekly_test_mark   = $data['weekly_test_mark'];
            $result->bi_weekly_test_mark= $data['bi_weekly_test_mark'];
            $result->monthly_test_mark  = $data['monthly_test_mark'];
            $result->assignment_mark    = $data['assignment_mark'];

            $result->save();
            return $result;
        } else {
            return false;
        }
    }

    public function findResult($student_uid, $exam_type)
    {
        $result = Result::where('student_id', $student_uid)->where('exam_type', $exam_type)->get();
        return $result;
    }

}

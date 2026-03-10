<?php

namespace App\Repositories;
use App\Repositories\Interfaces\FinalExamRepositoryInterface;
use App\Models\FinalExam;

class FinalExamRepository implements FinalExamRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return FinalExam::on('db_read')->with('branch')->get();
    }

    public function alreadyExist($data)
    {
        return FinalExam::where([
            'branch_id'     => $data['branch_id'],
            'shift_id'      => $data['shift_id'],
            'version_id'    => $data['version_id'],
            'class_id'      => $data['class_id'],
            'section_id'    => $data['section_id'],
            'subject_code'  => $data['subject_code'],
            'exam_name'     => $data['exam_name'],
        ])->whereNull('deleted_at')->get();
    }

    public function create($data)
    {
        return FinalExam::create($data);
    }

    public function update($data)
    {
        $term_exam = FinalExam::where('uid', $data['uid'])->first();

        if ($term_exam){
            $term_exam->branch_id         = $data['branch_id'];
            $term_exam->shift_id          = $data['shift_id'];
            $term_exam->version_id        = $data['version_id'];
            $term_exam->class_id          = $data['class_id'];
            $term_exam->section_id        = $data['section_id'];
            $term_exam->subject_code      = $data['subject_code'];
            $term_exam->exam_no           = $data['exam_no'];
            $term_exam->exam_name         = $data['exam_name'];
            $term_exam->mcq_mark          = $data['mcq_mark'];
            $term_exam->written_mark      = $data['written_mark'];
            $term_exam->practical_mark    = $data['practical_mark'];
            $term_exam->exam_full_mark    = $data['exam_full_mark'];
            $term_exam->exam_date         = $data['exam_date'];
            $term_exam->exam_time         = $data['exam_time'];
            $term_exam->exam_start_time   = $data['exam_start_time'];
            $term_exam->exam_end_time     = $data['exam_end_time'];
            $term_exam->exam_details_info = $data['exam_details_info'];
            $term_exam->status            = $data['status'];
            $term_exam->save();
            return $term_exam;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
       // return FinalExam::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_no', 'exam_name', 'exam_full_mark', 'exam_date', 'exam_time', 'exam_start_time', 'exam_end_time', 'exam_details_info', 'status')->where('uid', $id)->first();

        return FinalExam::on('db_read')
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
    //     return FinalExam::on('db_read')->with('branch')->where('eiin', $eiin)->get();
    // }

    public function getByEiinId($eiin, $optimize = null)
    {
        return FinalExam::on('db_read')
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
        return FinalExam::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return FinalExam::where('uid', $id)->delete();
    }

}

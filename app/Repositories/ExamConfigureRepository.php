<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ExamConfigureRepositoryInterface;
use App\Models\ExamConfigure;

class ExamConfigureRepository implements ExamConfigureRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return ExamConfigure::on('db_read')->get();
    }

    public function alreadyExist($data)
    {
        $exam = ExamConfigure::where([
            'branch_id'     => $data['branch_id'],
            'shift_id'      => $data['shift_id'],
            'version_id'    => $data['version_id'],
            'class_id'      => $data['class_id'],
            'section_id'    => $data['section_id'],
            'subject_code'  => $data['subject_code'],
            'exam_type'     => $data['exam_type'],
            'exam_name'     => $data['exam_name'],
        ])->whereNull('deleted_at')->get();

        return $exam;
    }

    public function create($data)
    {
        return ExamConfigure::create($data);
    }

    public function update($data)
    {
        $exam = ExamConfigure::where('uid', $data['uid'])->first();

        if ($exam){
            $exam->branch_id         = $data['branch_id'];
            $exam->shift_id          = $data['shift_id'];
            $exam->version_id        = $data['version_id'];
            $exam->class_id          = $data['class_id'];
            $exam->section_id        = $data['section_id'];
            $exam->subject_code      = $data['subject_code'];
            $exam->exam_type         = $data['exam_type'];
            $exam->exam_no           = $data['exam_no'];
            $exam->exam_name         = $data['exam_name'];
            $exam->mcq_mark          = $data['mcq_mark'];
            $exam->written_mark      = $data['written_mark'];
            $exam->practical_mark    = $data['practical_mark'];
            $exam->exam_full_mark    = $data['exam_full_mark'];
            $exam->exam_date         = $data['exam_date'];
            $exam->exam_time         = $data['exam_time'];
            $exam->exam_details_info = $data['exam_details_info'];
            $exam->status            = $data['status'];
            $exam->save();
            return $exam;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return ExamConfigure::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_type', 'exam_no', 'exam_name', 'mcq_mark', 'written_mark', 'practical_mark', 'exam_full_mark', 'exam_date', 'exam_time', 'exam_details_info', 'status')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        return ExamConfigure::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_type', 'exam_no', 'exam_name', 'mcq_mark', 'written_mark', 'practical_mark', 'exam_full_mark', 'exam_date', 'exam_time', 'exam_details_info', 'status')->where('eiin', $eiin)->get();
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return ExamConfigure::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return ExamConfigure::where('uid', $id)->delete();
    }

}

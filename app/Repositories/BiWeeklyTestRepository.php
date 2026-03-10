<?php

namespace App\Repositories;
use App\Repositories\Interfaces\BiWeeklyTestRepositoryInterface;
use App\Models\BiWeeklyTest;

class BiWeeklyTestRepository implements BiWeeklyTestRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return BiWeeklyTest::on('db_read')->get();
    }

    public function alreadyExist($data)
    {
        return BiWeeklyTest::where([
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
        return BiWeeklyTest::create($data);
    }

    public function update($data)
    {
        $class_test = BiWeeklyTest::where('uid', $data['uid'])->first();

        if ($class_test){
            $class_test->branch_id         = $data['branch_id'];
            $class_test->shift_id          = $data['shift_id'];
            $class_test->version_id        = $data['version_id'];
            $class_test->class_id          = $data['class_id'];
            $class_test->section_id        = $data['section_id'];
            $class_test->subject_code      = $data['subject_code'];
            $class_test->exam_no           = $data['exam_no'];
            $class_test->exam_name         = $data['exam_name'];
            $class_test->mcq_mark          = $data['mcq_mark'];
            $class_test->written_mark      = $data['written_mark'];
            $class_test->practical_mark    = $data['practical_mark'];
            $class_test->exam_full_mark    = $data['exam_full_mark'];
            $class_test->exam_date         = $data['exam_date'];
            $class_test->exam_time         = $data['exam_time'];
            $class_test->exam_details_info = $data['exam_details_info'];
            $class_test->status            = $data['status'];
            $class_test->save();
            return $class_test;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return BiWeeklyTest::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_no', 'exam_name', 'mcq_mark', 'written_mark', 'practical_mark', 'exam_full_mark', 'exam_date', 'exam_time', 'exam_details_info', 'status')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        return BiWeeklyTest::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_no', 'exam_name', 'mcq_mark', 'written_mark', 'practical_mark', 'exam_full_mark', 'exam_date', 'exam_time', 'exam_details_info', 'status')->where('eiin', $eiin)->get();
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return BiWeeklyTest::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return BiWeeklyTest::where('uid', $id)->delete();
    }

}

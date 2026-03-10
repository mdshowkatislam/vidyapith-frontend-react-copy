<?php

namespace App\Repositories;
use App\Repositories\Interfaces\MarkDistributionRepositoryInterface;
use App\Models\MarkDistribution;

class MarkDistributionRepository implements MarkDistributionRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return MarkDistribution::on('db_read')->with('branch')->get();
    }

    public function alreadyExists($data)
    {
        return MarkDistribution::where([
            'eiin'              => $data['eiin'],
            'class_id'          => $data['class_id'],
            'section_id'        => $data['section_id'],
            'subject_id'        => $data['subject_id'],
            'exam_category_id'  => $data['exam_category_id'],
            'exam_type'         => $data['exam_type'],
            'exam_id'           => $data['exam_id'],
            'student_id'        => $data['student_id'],
            'year'              => $data['year'],
        ])->orderBy('id','desc')->first();
    }


    public function create($data)
    {
        return MarkDistribution::create($data);
    }

    public function update($data)
    {
        $mark = MarkDistribution::where('uid', $data['uid'])->first();

        if ($mark){
            $mark->class_id          = $data['class_id'];
            $mark->section_id        = $data['section_id'];
            $mark->section_id        = $data['section_id'];
            $mark->subject_id        = $data['subject_id'];
            $mark->exam_category_id  = $data['exam_category_id'];
            $mark->exam_type         = $data['exam_type'];
            $mark->exam_id           = $data['exam_id'];
            $mark->exam_full_mark    = $data['exam_full_mark'];
            $mark->is_submitted      = $data['is_submitted'] ?? 0;
            $mark->student_id        = $data['student_id'];
            $mark->mcq_mark          = $data['mcq_mark'];
            $mark->written_mark      = $data['written_mark'];
            $mark->practical_mark    = $data['practical_mark'];
            $mark->obtain_full_mark  = $data['obtain_full_mark'];
            $mark->converted_full_mark = $data['converted_full_mark'];
            $mark->status            = $data['status'];
            $mark->remark            = $data['remark'];
            $mark->year              = $data['year'];
            $mark->save();
            return $mark;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
       // return MarkDistribution::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_no', 'exam_name', 'exam_full_mark', 'exam_date', 'exam_time', 'exam_start_time', 'exam_end_time', 'exam_details_info', 'status')->where('uid', $id)->first();

        return MarkDistribution::on('db_read')
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
    //     return MarkDistribution::on('db_read')->with('branch')->where('eiin', $eiin)->get();
    // }

    public function getByEiinId($eiin, $optimize = null)
    {
        return MarkDistribution::on('db_read')
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
        return MarkDistribution::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return MarkDistribution::where( [
            'eiin'      => app('sso-auth')->user()->eiin,
            'class_id'  => $id['class_id'],
            'section_id'=> $id['section_id'],
            'exam_type' => $id['exam_type'],
            'exam_id'   => $id['exam_id'],
        ])->delete();
    }

}

<?php

namespace App\Repositories;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;
use App\Models\Assignment;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Assignment::on('db_read')->get();
    }

    public function alreadyExist($data)
    {
        return Assignment::where([
            'branch_id'       => $data['branch_id'],
            'shift_id'        => $data['shift_id'],
            'version_id'      => $data['version_id'],
            'class_id'        => $data['class_id'],
            'section_id'      => $data['section_id'],
            'subject_code'    => $data['subject_code'],
            'assignment_name' => $data['assignment_name'],
        ])->whereNull('deleted_at')->get();
    }

    public function create($data)
    {
        return Assignment::create($data);
    }

    public function update($data)
    {
        $assignment = Assignment::where('uid', $data['uid'])->first();

        if ($assignment){
            $assignment->branch_id         = $data['branch_id'];
            $assignment->shift_id          = $data['shift_id'];
            $assignment->version_id        = $data['version_id'];
            $assignment->class_id          = $data['class_id'];
            $assignment->section_id        = $data['section_id'];
            $assignment->assignment_no     = $data['assignment_no'];
            $assignment->assignment_name   = $data['assignment_name'];
            $assignment->subject_code      = $data['subject_code'];
            $assignment->mcq_mark          = $data['mcq_mark'];
            $assignment->written_mark      = $data['written_mark'];
            $assignment->practical_mark    = $data['practical_mark'];
            $assignment->assignment_full_mark       = $data['assignment_full_mark'];
            $assignment->assignment_submission_date = $data['assignment_submission_date'];
            $assignment->assignment_details_info    = $data['assignment_details_info'];
            $assignment->status            = $data['status'];
            $assignment->save();
            return $assignment;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return Assignment::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'assignment_no', 'assignment_name', 'subject_code', 'mcq_mark', 'written_mark', 'practical_mark', 'assignment_full_mark', 'assignment_submission_date', 'assignment_details_info', 'status')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        return Assignment::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'assignment_no', 'assignment_name', 'subject_code', 'mcq_mark', 'written_mark', 'practical_mark', 'assignment_full_mark', 'assignment_submission_date', 'assignment_details_info', 'status')->where('eiin', $eiin)->get();
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return Assignment::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return Assignment::where('uid', $id)->delete();
    }

}

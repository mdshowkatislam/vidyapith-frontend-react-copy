<?php

namespace App\Repositories;
use App\Repositories\Interfaces\AttendanceConfigureRepositoryInterface;
use App\Models\AttendanceConfigure;

class AttendanceConfigureRepository implements AttendanceConfigureRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return AttendanceConfigure::on('db_read')->get();
    }

    public function alreadyExist($data)
    {
        $exam = AttendanceConfigure::where([
            'branch_id'     => $data['branch_id'],
            'shift_id'      => $data['shift_id'],
            'version_id'    => $data['version_id'],
            'class_id'      => $data['class_id'],
            'section_id'    => $data['section_id'],
        ])->whereNull('deleted_at')->first();

        return $exam;
    }

    public function create($data)
    {
        if ($data['mode'] === 'normal') {
            $attendanceConfiguration = AttendanceConfigure::updateOrCreate(
                [
                    'eiin'          => $data['eiin'],
                    'branch_id'     => $data['branch_id'],
                    'shift_id'      => $data['shift_id'],
                    'version_id'    => $data['version_id'],
                    'class_id'      => $data['class_id'],
                    'section_id'    => $data['section_id'],
                ],
                ['mode' => $data['mode'], 'rules' => json_encode([['from' => 0, 'to' => 100, 'value' => 100]])]
            );
        } else {
            $attendanceConfiguration = AttendanceConfigure::updateOrCreate(
                [
                    'eiin'          => $data['eiin'],
                    'branch_id'     => $data['branch_id'],
                    'shift_id'      => $data['shift_id'],
                    'version_id'    => $data['version_id'],
                    'class_id'      => $data['class_id'],
                    'section_id'    => $data['section_id'],
                ],
                ['mode' => $data['mode'], 'rules' => json_encode($data['rules'])]
            );
        }

        return $attendanceConfiguration;
    }

    public function update($data)
    {
        $exam = AttendanceConfigure::where('uid', $data['uid'])->first();

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
        return AttendanceConfigure::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'mode', 'rules', 'status')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        return AttendanceConfigure::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'mode', 'rules', 'status')->where('eiin', $eiin)->get();
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return AttendanceConfigure::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return AttendanceConfigure::where('uid', $id)->delete();
    }

}

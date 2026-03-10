<?php

namespace App\Repositories;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use App\Models\Attendance;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Attendance::on('db_read')->with('branch')->get();
    }

    public function alreadyExist($data)
    {
        return Attendance::where([
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
        return Attendance::create($data);
    }

    public function update($data)
    {
        $attendance = Attendance::where('uid', $data['uid'])->first();

        if ($attendance){
            $attendance->branch_id         = $data['branch_id'];
            $attendance->shift_id          = $data['shift_id'];
            $attendance->version_id        = $data['version_id'];
            $attendance->class_id          = $data['class_id'];
            $attendance->section_id        = $data['section_id'];
            $attendance->student_id        = $data['student_id'];
            $attendance->period            = $data['period'];
            $attendance->date              = $data['date'];
            $attendance->entry_time        = $data['entry_time'] ?? null;
            $attendance->status            = $data['status'];
            $attendance->remark            = $data['remark'];
            $attendance->save();
            return $attendance;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
       // return Attendance::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_no', 'exam_name', 'exam_full_mark', 'exam_date', 'exam_time', 'exam_start_time', 'exam_end_time', 'exam_details_info', 'status')->where('uid', $id)->first();

        return Attendance::on('db_read')
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
    //     return Attendance::on('db_read')->with('branch')->where('eiin', $eiin)->get();
    // }

    public function getByEiinId($eiin, $optimize = null)
    {
            \Log::info('CCC1', ['eiin' => $eiin]);
        $x= Attendance::on('db_read')->with('student')
            // ->with(['student' => function($query) {
            //     $query->select('id', 'student_name_bn');
            // }])
            // ->with(['version' => function($query) {
            //     $query->select('id', 'version_name_bn');
            // }])
            // ->with(['shift' => function($query) {
            //     $query->select('id', 'shift_name_bn');
            // }])
            // ->with(['className' => function($query) {
            //     $query->select('id', 'class_name_en', 'class_name_bn');
            // }])
            // ->with(['section' => function($query) {
            //     $query->select('id', 'section_name');
            // }])
            // ->with(['student' => function($query) {
            //     $query->select('id', 'student_name_bn', 'student_name_en', 'roll');
            // }])
            ->where('eiin', $eiin)
            ->paginate(3000);
              \Log::info('CCC2', ['x' => $x]);
            return $x;
    }
    public function getBySectionId($eiin, $optimize = null, $section_id)
    {
        return Attendance::on('db_read')->with('student')
            // ->with(['student' => function($query) {
            //     $query->select('id', 'student_name_bn');
            // }])
            // ->with(['version' => function($query) {
            //     $query->select('id', 'version_name_bn');
            // }])
            // ->with(['shift' => function($query) {
            //     $query->select('id', 'shift_name_bn');
            // }])
            // ->with(['className' => function($query) {
            //     $query->select('id', 'class_name_en', 'class_name_bn');
            // }])
            // ->with(['section' => function($query) {
            //     $query->select('id', 'section_name');
            // }])
            // ->with(['student' => function($query) {
            //     $query->select('id', 'student_name_bn', 'student_name_en', 'roll');
            // }])
            ->where('eiin', $eiin)
            ->whereIn('section_id', $section_id)
            ->paginate(3000);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return Attendance::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return Attendance::where('uid', $id)->delete();
    }

}

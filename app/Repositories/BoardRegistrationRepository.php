<?php

namespace App\Repositories;

use App\Models\BoardRegistationProcess;
use App\Models\Payment;
use App\Models\Student;
use App\Repositories\Interfaces\BoardRegistrationRepositoryInterface;

class BoardRegistrationRepository implements BoardRegistrationRepositoryInterface
{
    public function updateTempCount($data)
    {
        $boardReg = BoardRegistationProcess::where('eiin', auth()->user()->eiin)
                    ->where('class', $data['class'])
                    ->where('session_year', date('Y'))
                    ->whereColumn('no_of_payment_students', '!=', 'no_of_registered_students')
                    ->first();
        $boardReg->no_of_temp_students = $boardReg->no_of_temp_students + count($data['checkedStudents']);
        $boardReg->save();
    }

    public function updateRegCount($data)
    {
        $boardReg = BoardRegistationProcess::where('eiin', auth()->user()->eiin)
                    ->where('class', $data['class'])
                    ->where('session_year', date('Y'))
                    ->whereColumn('no_of_payment_students', '!=', 'no_of_registered_students')
                    ->first();

        $boardReg->no_of_temp_students = $boardReg->no_of_temp_students - count($data['students']);
        $boardReg->no_of_registered_students = $boardReg->no_of_registered_students + count($data['students']);
        $boardReg->save();
    }

    public function assignToTemporaryList($student_uid)
    {
        $student = Student::where('uid', $student_uid)->first();

        $student->reg_status = 1;   //1=temp
        $student->scroll_num = $this->assignScrollNumber();
        $student->save();
        return $student;
    }

    public function assignScrollNumber()
    {
        $eiinId = auth()->user()->eiin;
        $student = Student::with('student_class_info.classRoom')
            ->whereHas('student_class_info.classRoom', function ($query) use ($eiinId) {
                $query->where('eiin', $eiinId);
            })
            ->whereNotNull('scroll_num')
            ->orderBy('scroll_num', 'desc')
            ->first();

        if (!$student) {
            $scroll_num = 1;
        } else {
            $scroll_num = $student->scroll_num + 1;
        }
        return $scroll_num;
    }

    public function assignToRegisteredList($student_uid)
    {
        $student = Student::with('student_class_info')->where('uid', $student_uid)->first();
        $board_reg_prefix = date('y').$student->student_class_info->classRoom->institute->board->board_code;
        $last_reg_student = Student::where('board_reg_no', 'like', $board_reg_prefix.'%')->orderBy('board_reg_no', 'desc')->first();
        if($last_reg_student){
            $student->board_reg_no = $last_reg_student->board_reg_no + 1;
        }
        else{
            $student->board_reg_no = $board_reg_prefix.'0000001';
        }
        $student->reg_status = 2;   //2=registered
        $student->save();

        return $student;
    }

    public function tempStudentList($class_id)
    {
        $eiinId = auth()->user()->eiin;
        $students = Student::with('student_class_info.classRoom', 'student_class_info.classRoom.section')
            ->whereHas('student_class_info.classRoom', function ($query) use ($eiinId, $class_id) {
                $query->where('eiin', $eiinId)->where('class_id', $class_id);
            })
            ->where('reg_status', 1)
            ->get();
        return $students;
    }

    public function registeredStudentList($class_id)
    {
        $eiinId = auth()->user()->eiin;
        $students = Student::with('student_class_info.classRoom')
            ->whereHas('student_class_info.classRoom', function ($query) use ($eiinId, $class_id) {
                $query->where('eiin', $eiinId)->where('class_id', $class_id);
            })
            ->where('reg_status', 2)
            ->get();
        return $students;
    }

    public function totalStudentCount($class_id)
    {
        $eiinId = auth()->user()->eiin;
        $student_count = Student::with('student_class_info.classRoom')
            ->whereHas('student_class_info.classRoom', function ($query) use ($eiinId, $class_id) {
                $query->where('eiin', $eiinId)->where('class_id', $class_id);
            })
            ->count();
        return $student_count;
    }

    public function tempStudentCount($class_id)
    {
        $eiinId = auth()->user()->eiin;
        $student_count = Student::with('student_class_info.classRoom')
            ->whereHas('student_class_info.classRoom', function ($query) use ($eiinId, $class_id) {
                $query->where('eiin', $eiinId)->where('class_id', $class_id);
            })
            ->where('reg_status', 1)
            ->count();
        return $student_count;
    }

    public function registeredStudentCount($class_id)
    {
        $eiinId = auth()->user()->eiin;
        $student_count = Student::with('student_class_info.classRoom')
            ->whereHas('student_class_info.classRoom', function ($query) use ($eiinId, $class_id) {
                $query->where('eiin', $eiinId)->where('class_id', $class_id);
            })
            ->where('reg_status', 2)
            ->count();
        return $student_count;
    }
}

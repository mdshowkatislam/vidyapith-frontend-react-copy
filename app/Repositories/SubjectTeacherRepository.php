<?php

namespace App\Repositories;

use App\Models\SubjectTeacher;
use App\Repositories\Interfaces\SubjectTeacherRepositoryInterface;

class SubjectTeacherRepository implements SubjectTeacherRepositoryInterface
{
    public function getAll()
    {
        return SubjectTeacher::on('db_read')->all();
    }

    public function getById($id)
    {
        return SubjectTeacher::on('db_read')->where('uid', $id)->first();;
    }

    public function create($data)
    {
        return SubjectTeacher::create($data);
    }

    public function update($id, $data)
    {
        $result = SubjectTeacher::findOrFail($id);
        $result->update($data);
    }

    public function delete($id)
    {
        $result = SubjectTeacher::findOrFail($id);
        $result->delete();
    }

    public function getByTeacherId($teacher_id, $session)
    {
        $result = SubjectTeacher::on('db_read')->with(['classRoom.class_teacher', 'classRoom.all_students'])
            ->select('uid', 'teacher_uid', 'subject_uid', 'class_room_uid')
            ->where('teacher_uid', $teacher_id)
            ->whereHas('classRoom', function ($query) use ($session) {
                $query->where('session_year', $session);
            })
            ->get();
        return $result;
    }

    public function getByTeacherUid($teacher_id, $session)
    {
        $result = SubjectTeacher::on('db_read')->with(['classRoom.class_teacher', 'classRoom.students', 'classRoom.students.student_info'])
            ->select('uid', 'teacher_uid as teacher_id', 'subject_uid as subject_id', 'class_room_uid as class_room_id', 'class_room_uid')
            ->where('teacher_uid', $teacher_id)
            ->whereHas('classRoom', function ($query) use ($session) {
                $query->where('session_year', $session);
            })
            ->get();
        return $result;
    }

    public function getOwnSubjectByTeacherId($teacher_id, $session)
    {
        $result = SubjectTeacher::on('db_read')
            ->select('subject_uid as subject_id', 'subject_uid')
            ->where('teacher_uid', $teacher_id)
            ->whereHas('classRoom', function ($query) use ($session) {
                $query->where('session_year', $session);
            })
            ->distinct()
            ->get();
        return $result;
    }

    public function getByClassRoomId($class_room_id)
    {
        $result = SubjectTeacher::on('db_read')->with('classRoom')->where('class_room_uid', $class_room_id)->get();
        return $result;
    }
}

<?php

namespace App\Services\SubjectTeacherService;

interface SubjectTeacherServiceInterface
{
    public function getAllSubjectTeachers();
    public function getSubjectTeacherById($id);
    public function createSubjectTeacher($data);
    public function updateSubjectTeacher($id, $data);
    public function deleteSubjectTeacher($id);
    public function getSubjectByTeacherId($teacher_id, $session=null);
    public function getOwnSubjectByTeacherId($teacher_id, $session=null);
    public function getSubjectByTeacherClassRoomId($class_room_id);

    public function getSubjectByTeacherUid($teacher_id, $session=null);
}

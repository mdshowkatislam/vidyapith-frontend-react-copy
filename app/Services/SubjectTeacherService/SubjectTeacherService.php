<?php

namespace App\Services\SubjectTeacherService;

use App\Repositories\Interfaces\SubjectTeacherRepositoryInterface;
use App\Services\SubjectTeacherService\SubjectTeacherServiceInterface;

class SubjectTeacherService implements SubjectTeacherServiceInterface
{
    private $subjectTeacherService;

    public function __construct(SubjectTeacherRepositoryInterface $subjectTeacherService)
    {
        $this->subjectTeacherService = $subjectTeacherService;
    }

    public function getAllSubjectTeachers()
    {
        return $this->subjectTeacherService->getAll();
    }

    public function getSubjectTeacherById($id)
    {
        return $this->subjectTeacherService->getById($id);
    }

    public function createSubjectTeacher($data)
    {
        return $this->subjectTeacherService->create($data);
    }

    public function updateSubjectTeacher($id, $data)
    {
        return $this->subjectTeacherService->update($id, $data);
    }

    public function deleteSubjectTeacher($id)
    {
        return $this->subjectTeacherService->delete($id);
    }
    public function getSubjectByTeacherId($teacher_id, $session=null)
    {
        return $this->subjectTeacherService->getByTeacherId($teacher_id, $session);
    }
    public function getSubjectByTeacherUid($teacher_id, $session=null)
    {
        return $this->subjectTeacherService->getByTeacherUid($teacher_id, $session);
    }
    public function getOwnSubjectByTeacherId($teacher_id, $session=null)
    {
        return $this->subjectTeacherService->getOwnSubjectByTeacherId($teacher_id, $session);
    }

    public function getSubjectByTeacherClassRoomId($class_room_id)
    {
        return $this->subjectTeacherService->getByClassRoomId($class_room_id);
    }

}

<?php

namespace App\Services;

use App\Repositories\StudentRepository;

class StudentService
{
    private $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function list($request)
    {
        return $this->studentRepository->list($request);
    }

    public function create($data, $class_room_uid)
    {
        return $this->studentRepository->create($data, $class_room_uid);
    }

    public function update($data, $id, $class_room_uid, $is_restore = false)
    {
        return $this->studentRepository->update($data, $id, $class_room_uid, $is_restore);
    }

    public function getById($id)
    {
        return $this->studentRepository->getById($id);
    }

    public function getByEmpId($emp_id)
    {
        return $this->studentRepository->getByEmpId($emp_id);
    }

    public function getRepository()
    {
        return $this->studentRepository;
    }

    public function getStudentInfoByUid($id)
    {
        return $this->studentRepository->getStudentInfoByUid($id);
    }

    public function getTotalStudentByEiinId($id, $is_not_paginate = null, $search = null)
    {
        return $this->studentRepository->getTotalStudentByEiinId($id, $is_not_paginate, $search);
    }

    public function getAllByEiinId($id, $is_not_paginate = null, $search = null)
    {
        return $this->studentRepository->getAllByEiinId($id, $is_not_paginate, $search);
    }

    public function getByEiinId($id, $is_not_paginate = null, $search = null, $paginate_number)
    {
        return $this->studentRepository->getByEiinId($id, $is_not_paginate, $search, $paginate_number);
    }

    public function getByClassRoomId($id, $is_not_paginate = null, $search = null, $class_room_uid, $paginate_number)
    {
        return $this->studentRepository->getByClassRoomId($id, $is_not_paginate, $search, $class_room_uid, $paginate_number);
    }

    public function getBranchByEiinId($id, $optimize = null)
    {
        return $this->studentRepository->getBranchByEiinId($id, $optimize);
    }

    public function getVersionByEiinId($branch, $id)
    {
        return $this->studentRepository->getVersionByEiinId($branch, $id);
    }

    public function getShiftByEiinId($branch, $id)
    {
        return $this->studentRepository->getShiftByEiinId($branch, $id);
    }

    public function getSectionByEiinId($branch, $class, $shift, $version, $id)
    {
        return $this->studentRepository->getSectionByEiinId($branch, $class, $shift, $version, $id);
    }

    public function getByCaId($id)
    {
        return $this->studentRepository->getByCaId($id);
    }

    public function getByUId($id)
    {
        return $this->studentRepository->getByUId($id);
    }

    public function getWithTrashedById($data, $eiin)
    {
        return $this->studentRepository->getWithTrashedById($data, $eiin);
    }

    public function getStudentListByAcademicDetails($data, $eiin, $year)
    {
        return $this->studentRepository->getStudentListByAcademicDetails($data, $eiin, $year);
    }

    public function isRollExists($class_room_uid, $roll)
    {
        return $this->studentRepository->isRollExists($class_room_uid, $roll);
    }

    public function checkRollExists($caid, $roll)
    {
        return $this->studentRepository->checkRollExists($caid, $roll);
    }

    public function authAccountCreateStudent($data)
    {
        return $this->studentRepository->authAccountCreateStudent($data);
    }

    public function authAccountCreateInstitude($data)
    {
        return $this->studentRepository->authAccountCreateInstitude($data);
    }

    public function delete($id)
    {
        return $this->studentRepository->delete($id);
    }

    public function getClassInfoByUid($id)
    {
        return $this->studentRepository->getClassInfoByUid($id);
    }

    public function upazillaTotalStudents($request)
    {
        return $this->studentRepository->upazillaTotalStudents($request);
    }

    public function foreignTotalStudents()
    {
        return $this->studentRepository->foreignTotalStudents();
    }

    public function getRelatedItemsForStudent($related_items, $id)
    {
        return $this->studentRepository->getRelatedItemsForStudent($related_items, $id);
    }

    public function changeStatus($student_uid, $status)
    {
        return $this->studentRepository->changeStatus($student_uid, $status);
    }
}

<?php

namespace App\Services;

use App\Repositories\TeacherRepository;

class TeacherService
{
    private $teacherRepository;

    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function list()
    {
        return $this->teacherRepository->list();
    }

    public function create($data)
    {
        return $this->teacherRepository->create($data);
    }

    public function update($data, $id, $is_restore = false)
    {
        return $this->teacherRepository->update($data, $id, $is_restore);
    }

    public function getById($id)
    {
        return $this->teacherRepository->getById($id);
    }

    public function getByEmpId($emp_id)
    {
        return $this->teacherRepository->getByEmpId($emp_id);
    }
    public function getByEmpIdShort($emp_id)
    { 
        return $this->teacherRepository->getByEmpIdShort($emp_id);
    }

    public function getRepository()
    {
        return $this->teacherRepository;
    }

    public function getByIdWithPaginate($id)
    {
        return $this->teacherRepository->getByIdWithPaginate($id);
    }

    public function getByClass($teacher_id)
    {
        return $this->teacherRepository->getByClass($teacher_id);
    }

    public function getWithTrashedById($id)
    {
        return $this->teacherRepository->getWithTrashedById($id);
    }

    public function getByCaId($id)
    {
        return $this->teacherRepository->getByCaId($id);
    }

    public function getByPdsOrIndex($id)
    {
        return $this->teacherRepository->getByPdsOrIndex($id);
    }

    public function getByEiinId($id, $is_not_paginate = null, $optimize = null, $search = null)
    {
        return $this->teacherRepository->getByEiinId($id, $is_not_paginate, $optimize, $search);
    }

    public function getBanbeisTeachers()
    {
        return $this->teacherRepository->getBanbeisTeachers();
    }

    public function getBanbeisTeachersById($id)
    {
        return $this->teacherRepository->getBanbeisTeachersById($id);
    }

    public function getBanbeisTeachersByEiinID($eiin, $optimize = null)
    {
        return $this->teacherRepository->getBanbeisTeachersByEiinID($eiin, $optimize);
    }

    public function getEmisTeachers()
    {
        return $this->teacherRepository->getEmisTeachers();
    }

    public function getEmisTeachersById($pdsid)
    {
        return $this->teacherRepository->getEmisTeachersById($pdsid);
    }

    public function getEmisTeachersByEiinID($eiin, $optimize = null)
    {
        return $this->teacherRepository->getEmisTeachersByEiinID($eiin, $optimize);
    }

    public function getEmisTeachersByEiinAndPdsID($eiin, $pdsid)
    {
        return $this->teacherRepository->getEmisTeachersByEiinAndPdsID($eiin, $pdsid);
    }

    public function getEmisTeacherByPdsID($pdsid)
    {
        return $this->teacherRepository->getEmisTeacherByPdsID($pdsid);
    }

    public function getBanbiesTeacherByIndexNo($index_no)
    {
        return $this->teacherRepository->getBanbiesTeacherByIndexNo($index_no);
    }

    public function authAccountCreateTeacher($data)
    {
        return $this->teacherRepository->authAccountCreateTeacher($data);
    }

    public function getInstituteByEiin($eiin, $has_eiin = null)
    {
        return $this->teacherRepository->getInstituteByEiin($eiin, $has_eiin);
    }

    public function delete($id)
    {
        return $this->teacherRepository->delete($id);
    }

    public function teachersList($request)
    {
        return $this->teacherRepository->teachersList($request);
    }

    public function searchTeacherByPDSID($request)
    {
        return $this->teacherRepository->searchTeacherByPDSID($request);
    }

    public function classTeacherCheck($teacher_uid)
    {
        return $this->teacherRepository->classTeacherCheck($teacher_uid);
    }

    public function upazillaTotalTeachers($request)
    {
        return $this->teacherRepository->upazillaTotalTeachers($request);
    }

    public function foreignTotalTeachers()
    {
        return $this->teacherRepository->foreignTotalTeachers();
    }
}

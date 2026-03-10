<?php

namespace App\Repositories\Interfaces;

interface TeacherRepositoryInterface
{
    public function list();

    public function create($data);

    public function update($data, $id);

    public function getById($id);

    public function getByCaId($id);

    public function getByEiinId($id, $is_not_paginate=null);

    public function getBanbeisTeachers();

    public function getBanbeisTeachersById($id);

    public function getBanbeisTeachersByEiinID($id);

    public function getEmisTeachers();

    public function getEmisTeachersById($pdsid);

    public function getEmisTeachersByEiinID($id);

    public function getEmisTeachersByEiinAndPdsID($id, $pdsid);

    public function authAccountCreateTeacher($data);

    public function classTeacherCheck($teacher_uid);

    public function getInstituteByEiin($eiin);

    public function delete($id);
}

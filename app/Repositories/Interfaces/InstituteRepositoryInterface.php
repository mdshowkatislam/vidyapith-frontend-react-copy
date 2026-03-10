<?php

namespace App\Repositories\Interfaces;

interface InstituteRepositoryInterface
{   
    public function list($request);

    public function create($data);

    public function getById($id);

    public function getByEiinId($id);

    public function getByUpazilaId($id);

    public function getUpazilaInstituteWithHeadMaster($upazila_id);

    public function getUpazilaTeachers($upazila_id);

    public function updateInstituteHeadMaster($data);
}

<?php

namespace App\Services;

use App\Repositories\StaffRepository;

class StaffService
{
    private $staffRepository;

    public function __construct(StaffRepository $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    public function list()
    {
        return $this->staffRepository->list();
    }

    public function create($data)
    {
        return $this->staffRepository->create($data);
    }

    public function update($data, $id, $is_restore = false)
    {
        return $this->staffRepository->update($data, $id, $is_restore);
    }

    public function getById($id)
    {
        return $this->staffRepository->getById($id);
    }

    public function getByEmpId($emp_id)
    {
        return $this->staffRepository->getByEmpId($emp_id);
    }

    public function getRepository()
    {
        return $this->staffRepository;
    }

    public function getWithTrashedById($id)
    {
        return $this->staffRepository->getWithTrashedById($id);
    }

    public function getByEiinId($id, $is_not_paginate = null, $optimize = null, $search = null)
    {
        return $this->staffRepository->getByEiinId($id, $is_not_paginate, $optimize, $search);
    }

    public function authAccountCreateStaff($data)
    {
        return $this->staffRepository->authAccountCreateStaff($data);
    }

    public function delete($id)
    {
        return $this->staffRepository->delete($id);
    }
    

    public function staffsList($request)
    {
        return $this->staffRepository->staffsList($request);
    }
}

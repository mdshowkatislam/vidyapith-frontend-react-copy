<?php

namespace App\Repositories\Interfaces;

interface StaffRepositoryInterface
{
    public function list();

    public function create($data);

    public function update($data, $id);

    public function getById($id);

    public function getByEiinId($id, $is_not_paginate=null);

    public function authAccountCreateStaff($data);

    public function delete($id);
}

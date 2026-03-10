<?php

namespace App\Repositories\Interfaces;

interface AttendanceConfigureRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByEiinId($eiin);
    public function create($data);
    public function alreadyExist($data);
}

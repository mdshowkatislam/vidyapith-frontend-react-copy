<?php

namespace App\Repositories\Interfaces;

interface AttendanceRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByEiinId($eiin);
    public function create($data);
}

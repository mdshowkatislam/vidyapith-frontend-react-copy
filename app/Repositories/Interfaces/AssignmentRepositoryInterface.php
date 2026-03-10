<?php

namespace App\Repositories\Interfaces;

interface AssignmentRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByEiinId($eiin);
    public function create($data);
}

<?php

namespace App\Repositories\Interfaces;

interface GroupRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByEiinId($eiin);
    public function create($data);
}

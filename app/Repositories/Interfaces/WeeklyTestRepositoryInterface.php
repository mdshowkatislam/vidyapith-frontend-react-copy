<?php

namespace App\Repositories\Interfaces;

interface WeeklyTestRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByEiinId($eiin);
    public function create($data);
}

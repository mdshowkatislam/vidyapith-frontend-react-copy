<?php

namespace App\Repositories\Interfaces;

interface ExamConfigureRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByEiinId($eiin);
    public function create($data);
    public function alreadyExist($data);
}

<?php

namespace App\Repositories\Interfaces;

interface ResultConfigurationRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByEiinId($eiin);
    public function create($data);
}

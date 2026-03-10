<?php

namespace App\Repositories\Interfaces;

interface InventoryItemRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function getByEiinId($eiin);
    public function create($data);
    // public function getByBranch($branch_id);
}

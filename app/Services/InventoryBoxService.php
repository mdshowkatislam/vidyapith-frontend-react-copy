<?php

namespace App\Services;

use App\Models\InventoryBox;
use App\Repositories\InventoryBoxRepository;

class InventoryBoxService
{
    private $inventoryBoxRepository;

    public function __construct(InventoryBoxRepository $inventoryBoxRepository)
    {
        $this->inventoryBoxRepository = $inventoryBoxRepository;
    }

    public function getAll()
    {
        return $this->inventoryBoxRepository->getAll();
    }

    public function create($data)
    {
        return $this->inventoryBoxRepository->create($data);
    }

    public function update($data)
    {
        return $this->inventoryBoxRepository->update($data);
    }

    public function getById($id)
    {
        return $this->inventoryBoxRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->inventoryBoxRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->inventoryBoxRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->inventoryBoxRepository->delete($id);
    }
    
    // public function getRelatedBoxsForInventoryBox($related_items, $id)
    // {
    //     return $this->inventoryBoxRepository->getRelatedBoxsForInventoryBox($related_items, $id);
    // }

    // public function getByBranch($branch_id)
    // {
    //     return $this->inventoryBoxRepository->getByBranch($branch_id);
    // }
}

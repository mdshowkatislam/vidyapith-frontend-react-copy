<?php

namespace App\Services;

use App\Models\InventoryRack;
use App\Repositories\InventoryRackRepository;

class InventoryRackService
{
    private $inventoryRackRepository;

    public function __construct(InventoryRackRepository $inventoryRackRepository)
    {
        $this->inventoryRackRepository = $inventoryRackRepository;
    }

    public function getAll()
    {
        return $this->inventoryRackRepository->getAll();
    }

    public function create($data)
    {
        return $this->inventoryRackRepository->create($data);
    }

    public function update($data)
    {
        return $this->inventoryRackRepository->update($data);
    }

    public function getById($id)
    {
        return $this->inventoryRackRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->inventoryRackRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->inventoryRackRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->inventoryRackRepository->delete($id);
    }
    
    // public function getRelatedRacksForInventoryRack($related_items, $id)
    // {
    //     return $this->inventoryRackRepository->getRelatedRacksForInventoryRack($related_items, $id);
    // }

    // public function getByBranch($branch_id)
    // {
    //     return $this->inventoryRackRepository->getByBranch($branch_id);
    // }
}

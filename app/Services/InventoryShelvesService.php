<?php

namespace App\Services;

use App\Models\InventoryShelves;
use App\Repositories\InventoryShelvesRepository;

class InventoryShelvesService
{
    private $inventoryShelvesRepository;

    public function __construct(InventoryShelvesRepository $inventoryShelvesRepository)
    {
        $this->inventoryShelvesRepository = $inventoryShelvesRepository;
    }

    public function getAll()
    {
        return $this->inventoryShelvesRepository->getAll();
    }

    public function create($data)
    {
        return $this->inventoryShelvesRepository->create($data);
    }

    public function update($data)
    {
        return $this->inventoryShelvesRepository->update($data);
    }

    public function getById($id)
    {
        return $this->inventoryShelvesRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->inventoryShelvesRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->inventoryShelvesRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->inventoryShelvesRepository->delete($id);
    }
    
    // public function getRelatedShelvessForInventoryShelves($related_items, $id)
    // {
    //     return $this->inventoryShelvesRepository->getRelatedShelvessForInventoryShelves($related_items, $id);
    // }

    // public function getByBranch($branch_id)
    // {
    //     return $this->inventoryShelvesRepository->getByBranch($branch_id);
    // }
}

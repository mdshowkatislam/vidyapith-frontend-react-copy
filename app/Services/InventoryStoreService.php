<?php

namespace App\Services;

use App\Models\InventoryStore;
use App\Repositories\InventoryStoreRepository;

class InventoryStoreService
{
    private $inventoryStoreRepository;

    public function __construct(InventoryStoreRepository $inventoryStoreRepository)
    {
        $this->inventoryStoreRepository = $inventoryStoreRepository;
    }

    public function getAll()
    {
        return $this->inventoryStoreRepository->getAll();
    }

    public function create($data)
    {
        return $this->inventoryStoreRepository->create($data);
    }

    public function update($data)
    {
        return $this->inventoryStoreRepository->update($data);
    }

    public function getById($id)
    {
        return $this->inventoryStoreRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->inventoryStoreRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->inventoryStoreRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->inventoryStoreRepository->delete($id);
    }
    
    // public function getRelatedItemsForInventoryStore($related_items, $id)
    // {
    //     return $this->inventoryStoreRepository->getRelatedItemsForInventoryStore($related_items, $id);
    // }

    // public function getByBranch($branch_id)
    // {
    //     return $this->inventoryStoreRepository->getByBranch($branch_id);
    // }
}

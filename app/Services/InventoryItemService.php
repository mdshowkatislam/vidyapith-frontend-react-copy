<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Repositories\InventoryItemRepository;

class InventoryItemService
{
    private $inventoryItemRepository;

    public function __construct(InventoryItemRepository $inventoryItemRepository)
    {
        $this->inventoryItemRepository = $inventoryItemRepository;
    }

    public function getAll()
    {
        return $this->inventoryItemRepository->getAll();
    }

    public function create($data)
    {
        return $this->inventoryItemRepository->create($data);
    }

    public function update($data)
    {
        return $this->inventoryItemRepository->update($data);
    }

    public function getById($id)
    {
        return $this->inventoryItemRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->inventoryItemRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->inventoryItemRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->inventoryItemRepository->delete($id);
    }
    
    // public function getRelatedItemsForInventoryItem($related_items, $id)
    // {
    //     return $this->inventoryItemRepository->getRelatedItemsForInventoryItem($related_items, $id);
    // }

    // public function getByBranch($branch_id)
    // {
    //     return $this->inventoryItemRepository->getByBranch($branch_id);
    // }
}

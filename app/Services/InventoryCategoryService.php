<?php

namespace App\Services;

use App\Models\InventoryCategory;
use App\Repositories\InventoryCategoryRepository;

class InventoryCategoryService
{
    private $inventoryCategoryRepository;

    public function __construct(InventoryCategoryRepository $inventoryCategoryRepository)
    {
        $this->inventoryCategoryRepository = $inventoryCategoryRepository;
    }

    public function getAll()
    {
        return $this->inventoryCategoryRepository->getAll();
    }

    public function create($data)
    {
        return $this->inventoryCategoryRepository->create($data);
    }

    public function update($data)
    {
        return $this->inventoryCategoryRepository->update($data);
    }

    public function getById($id)
    {
        return $this->inventoryCategoryRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->inventoryCategoryRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->inventoryCategoryRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->inventoryCategoryRepository->delete($id);
    }
    
    // public function getRelatedItemsForInventoryCategory($related_items, $id)
    // {
    //     return $this->inventoryCategoryRepository->getRelatedItemsForInventoryCategory($related_items, $id);
    // }

    // public function getByBranch($branch_id)
    // {
    //     return $this->inventoryCategoryRepository->getByBranch($branch_id);
    // }
}

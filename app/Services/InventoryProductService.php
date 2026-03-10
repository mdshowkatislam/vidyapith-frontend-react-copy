<?php

namespace App\Services;

use App\Models\InventoryProduct;
use App\Repositories\InventoryProductRepository;

class InventoryProductService
{
    private $inventoryProductRepository;

    public function __construct(InventoryProductRepository $inventoryProductRepository)
    {
        $this->inventoryProductRepository = $inventoryProductRepository;
    }

    public function getAll()
    {
        return $this->inventoryProductRepository->getAll();
    }

    public function create($data)
    {
        return $this->inventoryProductRepository->create($data);
    }

    public function update($data)
    {
        return $this->inventoryProductRepository->update($data);
    }

    public function getById($id)
    {
        return $this->inventoryProductRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->inventoryProductRepository->getByEiinId($eiin, $optimize);
    }
    public function generalIndex($eiin, $optimize=null)
    {
        return $this->inventoryProductRepository->generalIndex($eiin, $optimize);
    }
    public function libraryIndex($eiin, $optimize=null)
    {
        return $this->inventoryProductRepository->libraryIndex($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->inventoryProductRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->inventoryProductRepository->delete($id);
    }
    
    // public function getRelatedProductsForInventoryProduct($related_items, $id)
    // {
    //     return $this->inventoryProductRepository->getRelatedProductsForInventoryProduct($related_items, $id);
    // }

    // public function getByBranch($branch_id)
    // {
    //     return $this->inventoryProductRepository->getByBranch($branch_id);
    // }
}

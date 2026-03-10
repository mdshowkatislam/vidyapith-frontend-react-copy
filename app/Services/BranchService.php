<?php

namespace App\Services;

use App\Repositories\BranchRepository;

class BranchService
{
    private $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getAll()
    {
        return $this->branchRepository->getAll();
    }

    public function create($data)
    {
        return $this->branchRepository->create($data);
    }
    
    public function update($data)
    {
        return $this->branchRepository->update($data);
    }

    public function getById($id)
    {
        return $this->branchRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->branchRepository->getByEiinId($eiin, $optimize);
    }

    public function getByBranchId($eiin, $optimize=null, $branch_id)
    {
        return $this->branchRepository->getByBranchId($eiin, $optimize, $branch_id);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->branchRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->branchRepository->delete($id);
    }
    public function getRelatedItemsForBranch($related_items, $id)
    {
        return $this->branchRepository->getRelatedItemsForBranch($related_items, $id);
    }
}

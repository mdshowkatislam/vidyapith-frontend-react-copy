<?php

namespace App\Services;

use App\Models\Shift;
use App\Repositories\ShiftRepository;

class ShiftService
{
    private $shiftRepository;

    public function __construct(ShiftRepository $shiftRepository)
    {
        $this->shiftRepository = $shiftRepository;
    }

    public function getAll()
    {
        return $this->shiftRepository->getAll();
    }

    public function create($data)
    {
        return $this->shiftRepository->create($data);
    }

    public function update($data)
    {
        return $this->shiftRepository->update($data);
    }

    public function getById($id)
    {
        return $this->shiftRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->shiftRepository->getByEiinId($eiin, $optimize);
    }

    public function getByShiftId($eiin, $optimize=null, $shift_id)
    {
        return $this->shiftRepository->getByShiftId($eiin, $optimize, $shift_id);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->shiftRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->shiftRepository->delete($id);
    }
    
    public function getRelatedItemsForShift($related_items, $id)
    {
        return $this->shiftRepository->getRelatedItemsForShift($related_items, $id);
    }

    public function getByBranch($branch_id)
    {
        return $this->shiftRepository->getByBranch($branch_id);
    }
}

<?php

namespace App\Services;

use App\Repositories\MonthlyTestRepository;

class MonthlyTestService
{
    private $monthlyTestRepository;

    public function __construct(MonthlyTestRepository $monthlyTestRepository)
    {
        $this->monthlyTestRepository = $monthlyTestRepository;
    }

    public function getAll()
    {
        return $this->monthlyTestRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->monthlyTestRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->monthlyTestRepository->create($data);
    }

    public function update($data)
    {
        return $this->monthlyTestRepository->update($data);
    }

    public function getById($id)
    {
        return $this->monthlyTestRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->monthlyTestRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->monthlyTestRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->monthlyTestRepository->delete($id);
    }

}

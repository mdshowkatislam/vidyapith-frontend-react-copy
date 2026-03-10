<?php

namespace App\Services;

use App\Repositories\WeeklyTestRepository;

class WeeklyTestService
{

    private $weeklyTestRepository;

    public function __construct(WeeklyTestRepository $weeklyTestRepository)
    {
        $this->weeklyTestRepository = $weeklyTestRepository;
    }

    public function getAll()
    {
        return $this->weeklyTestRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->weeklyTestRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->weeklyTestRepository->create($data);
    }

    public function update($data)
    {
        return $this->weeklyTestRepository->update($data);
    }

    public function getById($id)
    {
        return $this->weeklyTestRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->weeklyTestRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->weeklyTestRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->weeklyTestRepository->delete($id);
    }

}

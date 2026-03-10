<?php

namespace App\Services;

use App\Repositories\BiWeeklyTestRepository;

class BiWeeklyTestService
{
    private $biWeeklyTestRepository;

    public function __construct(BiWeeklyTestRepository $biWeeklyTestRepository)
    {
        $this->biWeeklyTestRepository = $biWeeklyTestRepository;
    }

    public function getAll()
    {
        return $this->biWeeklyTestRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->biWeeklyTestRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->biWeeklyTestRepository->create($data);
    }

    public function update($data)
    {
        return $this->biWeeklyTestRepository->update($data);
    }

    public function getById($id)
    {
        return $this->biWeeklyTestRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->biWeeklyTestRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->biWeeklyTestRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->biWeeklyTestRepository->delete($id);
    }

}

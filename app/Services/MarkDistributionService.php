<?php

namespace App\Services;

use App\Repositories\MarkDistributionRepository;

class MarkDistributionService
{
    private $markDistributionRepository;

    public function __construct(MarkDistributionRepository $markDistributionRepository)
    {
        $this->markDistributionRepository = $markDistributionRepository;
    }

    public function getAll()
    {
        return $this->markDistributionRepository->getAll();
    }

    public function alreadyExists($data)
    {
        return $this->markDistributionRepository->alreadyExists($data);
    }

    public function create($data)
    {
        return $this->markDistributionRepository->create($data);
    }

    public function update($data)
    {
        return $this->markDistributionRepository->update($data);
    }

    public function getById($id)
    {
        return $this->markDistributionRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->markDistributionRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->markDistributionRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->markDistributionRepository->delete($id);
    }

}

<?php

namespace App\Services;

use App\Repositories\ResultConfigurationRepository;

class ResultConfigurationService
{
    private $resultConfigurationRepository;

    public function __construct(ResultConfigurationRepository $resultConfigurationRepository)
    {
        $this->resultConfigurationRepository = $resultConfigurationRepository;
    }

    public function getAll()
    {
        return $this->resultConfigurationRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->resultConfigurationRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->resultConfigurationRepository->create($data);
    }

    public function update($data)
    {
        return $this->resultConfigurationRepository->update($data);
    }

    public function getById($id)
    {
        return $this->resultConfigurationRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->resultConfigurationRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->resultConfigurationRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->resultConfigurationRepository->delete($id);
    }

    
    public function resultCreate($data)
    {
        return $this->resultConfigurationRepository->resultCreate($data);
    }

    public function resultUpdate($data)
    {
        return $this->resultConfigurationRepository->resultUpdate($data);
    }

    public function findResult($student_uid, $exam_type)
    {
        return $this->resultConfigurationRepository->findResult($student_uid, $exam_type);
    }

}

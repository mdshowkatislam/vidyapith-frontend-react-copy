<?php

namespace App\Services;

use App\Repositories\FinalExamRepository;

class FinalExamService
{
    private $finalExamRepository;

    public function __construct(FinalExamRepository $finalExamRepository)
    {
        $this->finalExamRepository = $finalExamRepository;
    }

    public function getAll()
    {
        return $this->finalExamRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->finalExamRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->finalExamRepository->create($data);
    }

    public function update($data)
    {
        return $this->finalExamRepository->update($data);
    }

    public function getById($id)
    {
        return $this->finalExamRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->finalExamRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->finalExamRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->finalExamRepository->delete($id);
    }

}

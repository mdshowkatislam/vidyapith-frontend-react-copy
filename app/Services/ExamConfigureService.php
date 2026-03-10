<?php

namespace App\Services;

use App\Repositories\ExamConfigureRepository;

class ExamConfigureService
{

    private $examConfigureRepository;

    public function __construct(ExamConfigureRepository $examConfigureRepository)
    {
        $this->examConfigureRepository = $examConfigureRepository;
    }

    public function getAll()
    {
        return $this->examConfigureRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->examConfigureRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->examConfigureRepository->create($data);
    }

    public function update($data)
    {
        return $this->examConfigureRepository->update($data);
    }

    public function getById($id)
    {
        return $this->examConfigureRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->examConfigureRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->examConfigureRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->examConfigureRepository->delete($id);
    }

}

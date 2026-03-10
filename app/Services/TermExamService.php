<?php

namespace App\Services;

use App\Repositories\TermExamRepository;

class TermExamService
{
    private $termExamRepository;

    public function __construct(TermExamRepository $termExamRepository)
    {
        $this->termExamRepository = $termExamRepository;
    }

    public function getAll()
    {
        return $this->termExamRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->termExamRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->termExamRepository->create($data);
    }

    public function update($data)
    {
        return $this->termExamRepository->update($data);
    }

    public function getById($id)
    {
        return $this->termExamRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->termExamRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->termExamRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->termExamRepository->delete($id);
    }

}

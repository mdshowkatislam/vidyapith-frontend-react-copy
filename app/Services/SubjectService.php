<?php

namespace App\Services;

use App\Repositories\SubjectRepository;

class SubjectService
{

    private $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function getAll()
    {
        return $this->subjectRepository->getAll();
    }

    public function create($data)
    {
        return $this->subjectRepository->create($data);
    }

    public function update($data)
    {
        return $this->subjectRepository->update($data);
    }

    public function getById($id)
    {
        return $this->subjectRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->subjectRepository->getByEiinId($eiin, $optimize);
    }
    public function getBySubjectId($eiin, $optimize=null, $subject_id)
    {
        return $this->subjectRepository->getBySubjectId($eiin, $optimize, $subject_id);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->subjectRepository->getByEiinIdWithPagination($eiin);
    }
    public function delete($id)
    {
        return $this->subjectRepository->delete($id);
    }

    public function getByCondition(array $conditions)
    {
        return $this->subjectRepository->getByCondition($conditions);
    }
}

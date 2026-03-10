<?php

namespace App\Services;

use App\Repositories\AssignmentRepository;

class AssignmentService
{
    private $assignmentRepository;

    public function __construct(AssignmentRepository $assignmentRepository)
    {
        $this->assignmentRepository = $assignmentRepository;
    }

    public function getAll()
    {
        return $this->assignmentRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->assignmentRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->assignmentRepository->create($data);
    }

    public function update($data)
    {
        return $this->assignmentRepository->update($data);
    }

    public function getById($id)
    {
        return $this->assignmentRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->assignmentRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->assignmentRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->assignmentRepository->delete($id);
    }

}

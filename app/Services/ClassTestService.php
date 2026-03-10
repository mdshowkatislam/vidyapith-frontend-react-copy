<?php

namespace App\Services;

use App\Repositories\ClassTestRepository;

class ClassTestService
{

    private $classTestRepository;

    public function __construct(ClassTestRepository $classTestRepository)
    {
        $this->classTestRepository = $classTestRepository;
    }

    public function getAll()
    {
        return $this->classTestRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->classTestRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->classTestRepository->create($data);
    }

    public function update($data)
    {
        return $this->classTestRepository->update($data);
    }

    public function getById($id)
    {
        return $this->classTestRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->classTestRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->classTestRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->classTestRepository->delete($id);
    }

}

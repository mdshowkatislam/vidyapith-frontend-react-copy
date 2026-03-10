<?php

namespace App\Services;

use App\Repositories\ClassWiseSubjectRepository;

class ClassWiseSubjectService
{

    private $classWiseSubjectRepository;

    public function __construct(ClassWiseSubjectRepository $classWiseSubjectRepository)
    {
        $this->classWiseSubjectRepository = $classWiseSubjectRepository;
    }

    public function getAll()
    {
        return $this->classWiseSubjectRepository->getAll();
    }

    public function create($data)
    {
        return $this->classWiseSubjectRepository->create($data);
    }

    public function update($data)
    {
        return $this->classWiseSubjectRepository->update($data);
    }

    public function getById($id)
    {
        return $this->classWiseSubjectRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->classWiseSubjectRepository->getByEiinId($eiin, $optimize);
    }

    public function getBySubjectId($eiin, $optimize=null, $class_id, $subject_id)
    {
        return $this->classWiseSubjectRepository->getBySubjectId($eiin, $optimize,$class_id, $subject_id);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->classWiseSubjectRepository->getByEiinIdWithPagination($eiin);
    }
    public function delete($class_id, $session_id)
    {
        return $this->classWiseSubjectRepository->delete($class_id, $session_id);
    }

}

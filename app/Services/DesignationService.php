<?php

namespace App\Services;

use App\Repositories\DesignationRepository;

class DesignationService
{
    private $designationRepository;

    public function __construct(DesignationRepository $designationRepository)
    {
        $this->designationRepository = $designationRepository;
    }

    public function list($optimize=null)
    {
        return $this->designationRepository->list($optimize);
    }
    public function getByUid($id,$optimize=null)
    {
        return $this->designationRepository->getByUid($id, $optimize);
    }
    public function create($data,$optimize=null)
    {
        return $this->designationRepository->create($data, $optimize);
    }
    public function update($data,$optimize=null)
    {
        return $this->designationRepository->update($data, $optimize);
    }
}

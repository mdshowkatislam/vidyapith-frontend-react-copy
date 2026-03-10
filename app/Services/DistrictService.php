<?php

namespace App\Services;

use App\Repositories\DistrictRepository;

class DistrictService
{
    private $districtRepository;

    public function __construct(DistrictRepository $districtRepository)
    {
        $this->districtRepository = $districtRepository;
    }

    public function list($optimize=null)
    {
        return $this->districtRepository->list($optimize);
    }
    
    public function getByDivision($division_id)
    {
        return $this->districtRepository->getByDivision($division_id);
    }

    public function getById($id)
    {
        return $this->districtRepository->getById($id);
    }

    public function create($data)
    {
      $this->districtRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->districtRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->districtRepository->delete($id);
    }
}

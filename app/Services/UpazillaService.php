<?php

namespace App\Services;

use App\Repositories\UpazillaRepository;

class UpazillaService
{
    private $upazillaRepository;

    public function __construct(UpazillaRepository $upazillaRepository)
    {
        $this->upazillaRepository = $upazillaRepository;
    }

    public function list($optimize=null)
    {
        return $this->upazillaRepository->list($optimize);
    }
    
    public function getByDistrict($district_id)
    {
        return $this->upazillaRepository->getByDistrict($district_id);
    }

    public function getById($id)
    { 
      

        return $this->upazillaRepository->getById($id);
    }

    public function create($data)
    {
      $this->upazillaRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->upazillaRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->upazillaRepository->delete($id);
    }
}

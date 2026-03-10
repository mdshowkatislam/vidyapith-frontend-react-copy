<?php

namespace App\Services;

use App\Repositories\LibFineConfigurationRepository;

class LibFineConfigurationService
{
    private $libFineConfigurationRepository;

    public function __construct(LibFineConfigurationRepository $libFineConfigurationRepository)
    {
        $this->libFineConfigurationRepository = $libFineConfigurationRepository;
    }

    public function getAll()
    {
        return $this->libFineConfigurationRepository->getAll();
    }

    public function create($data)
    {
        return $this->libFineConfigurationRepository->create($data);
    }

    public function update($data)
    {
        return $this->libFineConfigurationRepository->update($data);
    }

    public function getById($id)
    {
        return $this->libFineConfigurationRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->libFineConfigurationRepository->getByEiinId($eiin, $optimize);
    }

    public function delete($id)
    {
        return $this->libFineConfigurationRepository->delete($id);
    }

}

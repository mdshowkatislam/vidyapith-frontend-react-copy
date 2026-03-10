<?php

namespace App\Services;

use App\Repositories\SessionRepository;

class SessionService
{
    private $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function getAll()
    {
        return $this->sessionRepository->getAll();
    }

    public function create($data)
    {
        return $this->sessionRepository->create($data);
    }

    public function update($data)
    {
        return $this->sessionRepository->update($data);
    }

    public function getById($id)
    {
        return $this->sessionRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->sessionRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->sessionRepository->getByEiinIdWithPagination($eiin);
    }
    public function delete($id)
    {
        return $this->sessionRepository->delete($id);
    }

}

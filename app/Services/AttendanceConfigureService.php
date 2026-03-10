<?php

namespace App\Services;

use App\Repositories\AttendanceConfigureRepository;

class AttendanceConfigureService
{

    private $attendanceConfigureRepository;

    public function __construct(AttendanceConfigureRepository $attendanceConfigureRepository)
    {
        $this->attendanceConfigureRepository = $attendanceConfigureRepository;
    }

    public function getAll()
    {
        return $this->attendanceConfigureRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->attendanceConfigureRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->attendanceConfigureRepository->create($data);
    }

    public function update($data)
    {
        return $this->attendanceConfigureRepository->update($data);
    }

    public function getById($id)
    {
        return $this->attendanceConfigureRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->attendanceConfigureRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->attendanceConfigureRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->attendanceConfigureRepository->delete($id);
    }

}

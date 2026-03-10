<?php

namespace App\Services;

use App\Repositories\AttendanceRepository;

class AttendanceService
{
    private $attendanceRepository;

    public function __construct(AttendanceRepository $attendanceRepository)
    {
        $this->attendanceRepository = $attendanceRepository;
    }

    public function getAll()
    {
        return $this->attendanceRepository->getAll();
    }

    public function alreadyExist($data)
    {
        return $this->attendanceRepository->alreadyExist($data);
    }

    public function create($data)
    {
        return $this->attendanceRepository->create($data);
    }

    public function update($data)
    {
        return $this->attendanceRepository->update($data);
    }

    public function getById($id)
    {
        return $this->attendanceRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->attendanceRepository->getByEiinId($eiin, $optimize);
    }
    public function getBySectionId($eiin, $optimize=null, $section_id)
    {
        return $this->attendanceRepository->getBySectionId($eiin, $optimize, $section_id);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->attendanceRepository->getByEiinIdWithPagination($eiin);
    }

    public function delete($id)
    {
        return $this->attendanceRepository->delete($id);
    }

}

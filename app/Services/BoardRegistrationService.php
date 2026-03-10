<?php

namespace App\Services;

use App\Repositories\BoardRegistrationRepository;

class BoardRegistrationService
{
    private $boardRegistrationRepository;

    public function __construct(BoardRegistrationRepository $boardRegistrationRepository)
    {
        $this->boardRegistrationRepository = $boardRegistrationRepository;
    }

    public function updateTempCount($data)
    {
        $this->boardRegistrationRepository->updateTempCount($data);
    }

    public function updateRegCount($data)
    {
        $this->boardRegistrationRepository->updateRegCount($data);
    }

    public function assignToTemporaryList($student_uid)
    {
        return $this->boardRegistrationRepository->assignToTemporaryList($student_uid);
    }

    public function assignToRegisteredList($student_uid)
    {
        return $this->boardRegistrationRepository->assignToRegisteredList($student_uid);
    }

    public function tempStudentList($class_id)
    {
        return $this->boardRegistrationRepository->tempStudentList($class_id);
    }

    public function registeredStudentList($class_id)
    {
        return $this->boardRegistrationRepository->registeredStudentList($class_id);
    }

    public function totalStudentCount($class_id)
    {
        return $this->boardRegistrationRepository->totalStudentCount($class_id);
    }

    public function tempStudentCount($class_id)
    {
        return $this->boardRegistrationRepository->tempStudentCount($class_id);
    }

    public function registeredStudentCount($class_id)
    {
        return $this->boardRegistrationRepository->registeredStudentCount($class_id);
    }
}

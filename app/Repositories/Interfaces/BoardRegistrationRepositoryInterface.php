<?php

namespace App\Repositories\Interfaces;

interface BoardRegistrationRepositoryInterface
{
    public function updateTempCount($data);

    public function updateRegCount($data);

    public function assignToTemporaryList($student_uid);

    public function assignToRegisteredList($student_uid);

    public function tempStudentList($class_id);
    
    public function registeredStudentList($class_id);
}

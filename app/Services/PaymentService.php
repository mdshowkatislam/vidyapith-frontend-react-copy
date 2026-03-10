<?php

namespace App\Services;

use App\Repositories\PaymentRepository;

class PaymentService
{
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function create($data)
    {
        $this->paymentRepository->create($data);
    }

    public function boardRegistrationProcessCreate($data)
    {
        $this->paymentRepository->boardRegistrationProcessCreate($data);
    }

    public function isExists($eiin, $class, $year)
    {
        return $this->paymentRepository->isExists($eiin, $class, $year);
    }
    public function remainingStudent($eiin, $class, $year,$fromWhere = 0)
    {
        return $this->paymentRepository->remainingStudent($eiin, $class, $year,$fromWhere);
    }
    public function remainingStudentExists($eiin, $class, $year)
    {
        return $this->paymentRepository->remainingStudentExists($eiin, $class, $year);
    }
}

<?php

namespace App\Repositories\Interfaces;

interface PaymentRepositoryInterface
{
    public function create($data);

    public function isExists($eiin, $class, $year);
}

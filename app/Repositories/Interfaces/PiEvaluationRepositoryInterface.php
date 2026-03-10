<?php

namespace App\Repositories\Interfaces;

interface PiEvaluationRepositoryInterface
{
    public function create($data);
    public function getByPi($request_data);
}

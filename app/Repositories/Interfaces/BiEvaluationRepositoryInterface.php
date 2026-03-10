<?php

namespace App\Repositories\Interfaces;

interface BiEvaluationRepositoryInterface
{
    public function create($data);
    public function getByBi($request_data);
}

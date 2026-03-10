<?php

namespace App\Services;

use App\Repositories\BiEvaluationRepository;

class BiEvaluationService
{
    private $biEvaluationRepository;

    public function __construct(BiEvaluationRepository $biEvaluationRepository)
    {
        $this->biEvaluationRepository = $biEvaluationRepository;
    }

    public function create($data)
    {
        return $this->biEvaluationRepository->create($data);
    }

    public function getByBi($request_data)
    {
        return $this->biEvaluationRepository->getByBi($request_data);
    }

    public function getModel($evaluate_type, $class_uid)
    {
        return $this->biEvaluationRepository->getModel($evaluate_type, $class_uid);
    }
}

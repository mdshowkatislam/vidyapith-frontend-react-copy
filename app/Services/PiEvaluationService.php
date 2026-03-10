<?php

namespace App\Services;

use App\Repositories\PiEvaluationRepository;

class PiEvaluationService
{
    private $piEvaluationRepository;

    public function __construct(PiEvaluationRepository $piEvaluationRepository)
    {
        $this->piEvaluationRepository = $piEvaluationRepository;
    }

    public function create($data)
    {
        return $this->piEvaluationRepository->create($data);
    }

    public function getByPi($request_data)
    {
        return $this->piEvaluationRepository->getByPi($request_data);
    }

    public function getModel($evaluate_type, $class_uid)
    {
        return $this->piEvaluationRepository->getModel($evaluate_type, $class_uid);
    }
}

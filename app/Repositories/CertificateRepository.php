<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CertificateRepositoryInterface;
use App\Models\Certificate;

class CertificateRepository implements CertificateRepositoryInterface
{
    protected $model;

    public function __construct(Certificate $certificate)
    {
        $this->model = $certificate;
    }

    /**
     * Create a new certificate
     *
     * @param array $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * Find certificate by criteria (for duplicate checking)
     *
     * @param array $criteria
     * @return mixed
     */
    public function findByCriteria($criteria)
    {
        $query = $this->model->where('is_active', true);
        
        foreach ($criteria as $field => $value) {
            if (!empty($value)) {
                $query->where($field, $value);
            }
        }
        
        return $query->first();
    }

    /**
     * Get certificate by UID for verification
     *
     * @param string $uid
     * @return mixed
     */
    public function getByUid($uid)
    {
        return $this->model->where('uid', $uid)->first();
    }
}

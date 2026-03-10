<?php

namespace App\Repositories\Interfaces;

interface CertificateRepositoryInterface
{
    /**
     * Create a new certificate
     *
     * @param array $data
     * @return mixed
     */
    public function create($data);

    /**
     * Get certificate by UID for verification
     *
     * @param string $uid
     * @return mixed
     */
    public function getByUid($uid);
}

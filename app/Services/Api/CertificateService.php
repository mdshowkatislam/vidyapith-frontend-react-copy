<?php

namespace App\Services\Api;

use App\Repositories\CertificateRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class CertificateService
{
    private $certificateRepository;

    public function __construct(CertificateRepository $certificateRepository)
    {
        $this->certificateRepository = $certificateRepository;
    }

    /**
     * Create a new certificate
     *
     * @param array $data
     * @return mixed
     */
    public function create($data)
    {
        try {
            // Validate required fields
            $this->validateCertificateData($data);

            // Generate unique UID if not provided
            if (!isset($data['uid'])) {
                $data['uid'] = hexdec(uniqid());
            }

            // Set default values
            $data['is_active'] = $data['is_active'] ?? true;
            $data['issue_date'] = $data['issue_date'] ?? now();

            DB::beginTransaction();

            $certificate = $this->certificateRepository->create($data);

            DB::commit();

            return $certificate;
        } catch (Exception $exc) {
            DB::rollBack();
            throw new Exception("Failed to create certificate: " . $exc->getMessage());
        }
    }

    /**
     * Find existing certificate by student details
     *
     * @param array $criteria
     * @return mixed|null
     */
    public function findExisting($criteria)
    {
        try {
            return $this->certificateRepository->findByCriteria($criteria);
        } catch (Exception $exc) {
            throw new Exception("Failed to search certificate: " . $exc->getMessage());
        }
    }

    /**
     * Get certificate by UID for verification
     *
     * @param string $uid
     * @return mixed
     */
    public function getByUid($uid)
    {
        try {
            $certificate = $this->certificateRepository->getByUid($uid);
            if (!$certificate) {
                throw new Exception("Certificate not found");
            }
            return $certificate;
        } catch (Exception $exc) {
            throw new Exception("Failed to retrieve certificate: " . $exc->getMessage());
        }
    }

    /**
     * Validate certificate data
     *
     * @param array $data
     * @throws Exception
     */
    private function validateCertificateData($data)
    {
        $requiredFields = [
            'student_name',
            'roll_number',
            'class_name',
            'section_name',
            'grade_point',
            'academic_year',
            'school_name'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Field '{$field}' is required");
            }
        }

        // Validate grade point is numeric and within range
        if (isset($data['grade_point']) && (!is_numeric($data['grade_point']) || $data['grade_point'] < 0 || $data['grade_point'] > 5)) {
            throw new Exception("Grade point must be a number between 0 and 5");
        }

        // Validate merit position if provided
        if (isset($data['merit_position']) && (!is_numeric($data['merit_position']) || $data['merit_position'] < 1)) {
            throw new Exception("Merit position must be a positive number");
        }

        // Validate UID uniqueness if provided
        if (isset($data['uid']) && !empty($data['uid'])) {
            $existing = $this->certificateRepository->getByUid($data['uid']);
            if ($existing) {
                throw new Exception("Certificate UID already exists");
            }
        }
    }
}

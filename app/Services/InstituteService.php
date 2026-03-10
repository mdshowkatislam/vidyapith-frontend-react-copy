<?php

namespace App\Services;

use App\Repositories\InstituteRepository;

class InstituteService
{
    private $instituteRepository;

    public function __construct(InstituteRepository $instituteRepository)
    {
        $this->instituteRepository = $instituteRepository;
    }

    public function list($request)
    {
        return $this->instituteRepository->list($request);
    }

    public function storeInstituteHeadMaster($data)
    {
        return $this->instituteRepository->storeInstituteHeadMaster($data);
    }

    public function create($data)
    {
        return $this->instituteRepository->create($data);
    }

    public function getByInstituteId($id)
    {
        return $this->instituteRepository->getByInstId($id);
    }

    public function getByEiinId($id, $optimize=null)
    {
        return $this->instituteRepository->getByEiinId($id, $optimize);
    }
    public function getByInstituteUpazilaId($id)
    {
        return $this->instituteRepository->getByUpazilaId($id);
    }

    public function getUpazilaInstituteWithHeadMaster($upazila_id)
    {
        return $this->instituteRepository->getUpazilaInstituteWithHeadMaster($upazila_id);
    }

    public function getUpazilaTeachers($upazila_id) {
        return $this->instituteRepository->getUpazilaTeachers($upazila_id);
    }

    public function updateInstituteHeadMaster($data) {
        return $this->instituteRepository->updateInstituteHeadMaster($data);
    }

    public function updateInstituteData($data, $id)
    {
        return $this->instituteRepository->update($data, $id);
    }

    public function getById($id)
    {
        return $this->instituteRepository->getById($id);
    }

    public function getByIdWithDetails($eiin)
    {
        return $this->instituteRepository->getByIdWithDetails($eiin);
    }

    public function upazillaTotalInstitutes($request)
    {
        return $this->instituteRepository->upazillaTotalInstitutes($request);
    }
    public function foreignTotalInstitutes()
    {
        return $this->instituteRepository->foreignTotalInstitutes();
    }

    public function upazillaTotalSections($request)
    {
        return $this->instituteRepository->upazillaTotalSections($request);
    }

    public function searchInstitute($request)
    {
        return $this->instituteRepository->searchInstitute($request);
    }

    public function boards()
    {
        return $this->instituteRepository->boards();
    }
    public function getBoardByDistrictId($districtId)
    {
        return $this->instituteRepository->getBoardByDistrictId($districtId);
    }
    public function getExamPaper()
    {
        return $this->instituteRepository->getExamPaper();
    }

}

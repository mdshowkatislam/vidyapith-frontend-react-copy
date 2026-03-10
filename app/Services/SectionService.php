<?php

namespace App\Services;

use App\Repositories\SectionRepository;

class SectionService
{
    private $sectionRepository;

    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function getAll()
    {
        return $this->sectionRepository->getAll();
    }

    public function create($data)
    {
        return $this->sectionRepository->create($data);
    }
    
    public function update($data)
    {
        return $this->sectionRepository->update($data);
    }

    public function getById($id)
    {
        return $this->sectionRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->sectionRepository->getByEiinId($eiin, $optimize);
    }

    public function getBySectionId($eiin, $optimize=null, $section_id)
    {
        return $this->sectionRepository->getBySectionId($eiin, $optimize, $section_id);
    }

    public function delete($id)
    {
        return $this->sectionRepository->delete($id);
    }
    
    public function getRelatedItemsForSection($related_items, $id)
    {
        return $this->sectionRepository->getRelatedItemsForSection($related_items, $id);
    }

    public function getByclass($data)
    {
        return $this->sectionRepository->getByClass($data);
    }
}

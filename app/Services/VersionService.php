<?php

namespace App\Services;

use App\Repositories\VersionRepository;

class VersionService
{
    private $versionRepository;

    public function __construct(VersionRepository $versionRepository)
    {
        $this->versionRepository = $versionRepository;
    }

    public function getAll()
    {
        return $this->versionRepository->getAll();
    }

    public function create($data)
    {
        return $this->versionRepository->create($data);
    }
    
    public function update($data)
    {
        return $this->versionRepository->update($data);
    }

    public function getById($id)
    {
        return $this->versionRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->versionRepository->getByEiinId($eiin, $optimize);
    }

    public function getByVersionId($eiin, $optimize=null, $version_id)
    {
        return $this->versionRepository->getByVersionId($eiin, $optimize, $version_id);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->versionRepository->getByEiinIdWithPagination($eiin);
    }
    public function delete($id)
    {
        return $this->versionRepository->delete($id);
    }
    
    public function getRelatedItemsForVersion($related_items, $id)
    {
        return $this->versionRepository->getRelatedItemsForVersion($related_items, $id);
    }
    
    public function getByBranch($branch_id)
    {
        return $this->versionRepository->getByBranch($branch_id);
    }
}

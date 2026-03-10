<?php

namespace App\Services;

use App\Repositories\GroupRepository;

class GroupService
{

    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getAll()
    {
        return $this->groupRepository->getAll();
    }

    public function create($data)
    {
        return $this->groupRepository->create($data);
    }

    public function update($data)
    {
        return $this->groupRepository->update($data);
    }

    public function getById($id)
    {
        return $this->groupRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->groupRepository->getByEiinId($eiin, $optimize);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->groupRepository->getByEiinIdWithPagination($eiin);
    }
    public function delete($id)
    {
        return $this->groupRepository->delete($id);
    }

    public function getByCondition(array $conditions)
    {
        return $this->groupRepository->getByCondition($conditions);
    }
}

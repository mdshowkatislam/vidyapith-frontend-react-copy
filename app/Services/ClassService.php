<?php

namespace App\Services;

use App\Repositories\ClassRepository;

class ClassService
{

    private $classRepository;

    public function __construct(ClassRepository $classRepository)
    {
        $this->classRepository = $classRepository;
    }

    public function getAll()
    {
        return $this->classRepository->getAll();
    }

    public function create($data)
    {
        return $this->classRepository->create($data);
    }

    public function update($data)
    {
        return $this->classRepository->update($data);
    }

    public function getById($id)
    {
        return $this->classRepository->getById($id);
    }

    public function getByEiinId($eiin, $optimize=null)
    {
        return $this->classRepository->getByEiinId($eiin, $optimize);
    }

    public function getByClassId($eiin, $optimize=null, $class_id)
    {
        return $this->classRepository->getByClassId($eiin, $optimize, $class_id);
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return $this->classRepository->getByEiinIdWithPagination($eiin);
    }
    public function delete($id)
    {
        return $this->classRepository->delete($id);
    }




    // public function getAll()
    // {
    //     $client = new \GuzzleHttp\Client(['verify' => false]);
    //     $res = $client->request('GET', config('configure.class_api'));
    //     $apiData = json_decode($res->getBody()->getContents(), true);
    //     return $apiData['data'];
    // }

}

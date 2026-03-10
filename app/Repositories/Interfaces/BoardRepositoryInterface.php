<?php

namespace App\Repositories\Interfaces;

interface BoardRepositoryInterface {

    public function getAll();
    public function list();
    public function create($data);

    public function update($data, $id);

    public function delete($id);

    public function getById($id);
}



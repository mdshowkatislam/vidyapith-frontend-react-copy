<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getByCaid($id);
}

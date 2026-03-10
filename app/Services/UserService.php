<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($data)
    {
        return $this->userRepository->create($data);
    }

    public function getByCaid($id)
    {
        return $this->userRepository->getByCaid($id);
    }
    public function update($id, $data)
    {
        return $this->userRepository->update($id, $data);
    }

}

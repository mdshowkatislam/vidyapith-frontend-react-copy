<?php


namespace App\Services\Api;
use App\Repositories\BoardRepository;

class BoardService{

    private $boardRepository;

    
    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    public function getAll()
    {
        return $this->boardRepository->getAll();
    }
    
    public function create($data)
    {
       $this->boardRepository->create($data);
    }

}
<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BoardRepositoryInterface;
use App\Models\BoardToInstituteCategory;
use App\Models\Board;
use App\Models\BoardToDistrict;


class BoardRepository implements BoardRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        $boards = Board::all();
        foreach ($boards as $key => $board) {
            $boards[$key]['institute_categories'] = BoardToInstituteCategory::with('institute_category')->where('board_uid', $board->uid)->get()->toArray();
            $boards[$key]['districts'] = BoardToDistrict::with('district')->where('board_uid', $board->uid)->get()->toArray();
        }
        return $boards;
    }

    public function list($optimize = null)
    {
    }

    public function getById($id)
    {
        $boardSingle = Board::where('id', $id)->first();
        $boardSingle['institute_categories'] = BoardToInstituteCategory::with('institute_category')->where('board_uid', $boardSingle->uid)->get()->toArray();
        $boardSingle['districts'] = BoardToDistrict::with('district')->where('board_uid', $boardSingle->uid)->get()->toArray();

        return $boardSingle;
    }

    public function create($data)
    {
        dd($data);
    }

    public function update($data, $id)
    {
    }

    public function delete($id)
    {
    }
}

<?php

namespace App\Repositories;

use App\Repositories\Interfaces\DivisionRepositoryInterface;

use App\Models\Division;

class DivisionRepository implements DivisionRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function list($optimize = null)
    {
        if ($optimize) {
            return Division::on('db_read')->select('id', 'uid', 'division_name_bn', 'division_name_en', 'division_id')->get();
        } else {
            return Division::on('db_read')->get();
        }
    }

    public function getByUId($id)
    {
        return Division::on('db_read')->where('uid', $id)->first();;
    }
     public function getById($id)
    {
        
        return Division::on('db_read')->where('id', $id)->first();;
    }

    public function create($data)
    {
        $division = new Division;
        $division->name = @$data['name'];
        $division->save();
        return $division;
    }

    public function update($data, $id)
    {
        $division = Division::where('uid', $id)->first();
        $division->name = @$data['name'];
        $division->save();
        return $division;
    }

    public function delete($id)
    {
    }
}

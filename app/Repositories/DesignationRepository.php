<?php

namespace App\Repositories;

use App\Repositories\Interfaces\DesignationRepositoryInterface;

use App\Models\Designation;

class DesignationRepository implements DesignationRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function list($optimize = null)
    {
        if ($optimize) {
            return Designation::on('db_read')->select('uid', 'designation_name')->get();
        } else {
            return Designation::on('db_read')->get();
        }
    }
    public function getByUid($id,$optimize = null)
    {
        if ($optimize) {
            return Designation::on('db_read')->select('uid', 'designation_name')->where('uid', $id)->first();
        } else {
            return Designation::on('db_read')->where('uid', $id)->first();
        }
    }
    public function create($data,$optimize = null)
    {
        return Designation::on('db_write')->create($data);
    }
    public function update($data,$optimize = null)
    {
        return Designation::on('db_write')->where('uid', $data['uid'])->update($data);
    }
}

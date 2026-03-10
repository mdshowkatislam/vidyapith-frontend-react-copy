<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UpazillaRepositoryInterface;

use App\Models\Upazilla;

class UpazillaRepository implements UpazillaRepositoryInterface
{
    public function __construct()
    {
        //
    }
    public function list($optimize = null)
    {
        if ($optimize) {
            return Upazilla::on('db_read')->select('uid', 'upazila_name_bn', 'upazila_name_en', 'district_id', 'upazila_id')->get();
        } else {
            return Upazilla::on('db_read')->get();
        }
    }

    public function getByDistrict($district_id)
    {
        return Upazilla::on('db_read')->select('uid', 'id', 'upazila_name_bn', 'upazila_name_en', 'district_id', 'upazila_id')->where('district_id', $district_id)->get();
    }

    public function getByUId($id)
    {
        return Upazilla::on('db_read')->where('uid', $id)->first();;
    }
     public function getById($id)
    { 
      
        return Upazilla::on('db_read')->where('id', $id)->first();;
    }

    public function create($data)
    {
        $upazilla = new Upazilla;
        $upazilla->name = $data['name'];
        $upazilla->save();

        return $upazilla;
    }

    public function update($data, $id)
    {
        $upazilla = Upazilla::on('db_read')->where('uid', $id)->first();
        $upazilla->name = @$data['name'];
        $upazilla->save();
        return $upazilla;
    }

    public function delete($id)
    {
    }
}

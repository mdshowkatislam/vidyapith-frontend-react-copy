<?php

namespace App\Repositories;

use App\Repositories\Interfaces\DistrictRepositoryInterface;

use App\Models\District;

class DistrictRepository implements DistrictRepositoryInterface
{
    public function __construct()
    {
        //
    }
    public function list($optimize = null)
    {
        if ($optimize) {
            return District::on('db_read')->select('id', 'uid', 'district_name_bn', 'district_name_en', 'division_id', 'district_id')->get();
        } else {
            return District::on('db_read')->get();
        }
    }
    public function getByDivision($division_id)
    {
        return District::on('db_read')->select('id', 'uid', 'district_name_bn', 'district_name_en', 'division_id', 'district_id')->where('division_id', $division_id)->get();
    }

    public function getByUId($id)
    {
        return District::on('db_read')->where('uid', $id)->first();;
    }
     public function getById($id)
    {
        return District::on('db_read')->where('id', $id)->first();;
    }

    public function create($data)
    {
        $district = new District;
        $district->name = @$data['name'];
        $district->save();
        return $district;
    }

    public function update($data, $id)
    {
        $district = District::where('uid', $id)->first();
        $district->name = @$data['name'];
        $district->save();
        return $district;
    }

    public function delete($id)
    {
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $districtList = District::on('db_read')->get();
        return response($districtList, 200);
    }

    public function store(Request $request)
    {
        $nameExist = District::on('db_read')->where('name', $request->name)->first();
        if ($nameExist) {
            return response(['status' => false, 'message' => 'Already exist.'], 422);
        }
        $district = new District;
        $district->name = $request->name;

        if (!$district->save()) {
            return response(['status' => false, 'message' => 'Failed to store data.'], 422);
        }
        $response = [
            'status' => true,
            'data' => [
                'id' => $district->id,
                'name' => $district->name,
            ],
        ];
        return response($response, 200);
    }
}

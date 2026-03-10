<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Upazilla;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Division;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use App\Services\DivisionService;

use Exception;
use Illuminate\Support\Facades\Storage;

class DivisionController extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $divisionService;

    public function __construct(DivisionService $divisionService)
    {
        $this->divisionService = $divisionService;
    }

    public function index(Request $request)
    {
        try {
            $divisions = $this->divisionService->list(1);
            return $this->successResponse($divisions, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }
    public function getById($id)
    {
        try {
            $division= $this->divisionService->getById($id);
            return $this->successResponse($division, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:divisions'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($this->Validtor($validator->errors()), 422);
        }

        try {
            $division = $this->divisionService->create($request->all());

            return $this->successResponse($division, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($this->Validtor($validator->errors()), 422);
        }

        try {
            $districtExists = $this->divisionService->getByUId($id);
            if ($districtExists) {
                $district = $this->divisionService->update($request->all(), $id);
            } else {
                return $this->errorResponse("Not found", Response::HTTP_NOT_FOUND);
            }
            return $this->successResponse($district, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function show($id)
    {
        try {
            return $this->successResponse($this->divisionService->getById($id), Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }


    public function syncDivisionData(){
        
        // $filePath = 'bd-divisions.json';
        // $filePath = 'bd-districts.json';
        // $filePath = 'bd-upazilas.json';

        // if (Storage::exists($filePath)) {
        //     $jsonData = Storage::get($filePath);
        //     $data = json_decode($jsonData, true);
            
            //Division Data
            // if (isset($data['divisions'])) {
            //     foreach ($data['divisions'] as $data) {
            //         $division = new Division;
            //         $division->division_name_bn = @$data['bn_name'];
            //         $division->division_name_en = @$data['name'];
            //         $division->created_at = now();
            //         $division->updated_at = now();
            //         $division->save();
            //     }
            // }


            //District data
            // if (isset($data['districts'])) {
            //     foreach ($data['districts'] as $data) {
            //         $district = new District();
            //         $district->division_id = @$data['division_id'];
            //         $district->district_name_bn = @$data['bn_name'];
            //         $district->district_name_en = @$data['name'];
            //         $district->created_at = now();
            //         $district->updated_at = now();
            //         $district->save();
            //     }
            // }

            //Upazilas data
            // if (isset($data['upazilas'])) {
            //     foreach ($data['upazilas'] as $data) {
            //         $upazila = new Upazilla();
            //         $upazila->district_id = @$data['district_id'];
            //         $upazila->upazila_name_bn = @$data['bn_name'];
            //         $upazila->upazila_name_en = @$data['name'];
            //         $upazila->created_at = now();
            //         $upazila->updated_at = now();
            //         $upazila->save();
            //     }
            // }

        //     return response()->json($data);
        // } else {
        //     return response()->json(['error' => 'File not found'], 404);
        // }
    }
}

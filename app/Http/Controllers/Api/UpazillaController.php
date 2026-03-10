<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Upazilla;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use App\Services\UpazillaService;

use Exception;

class UpazillaController extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $upazillaService;

    public function __construct(UpazillaService $upazillaService)
    {
        $this->upazillaService = $upazillaService;
    }

    public function index(Request $request)
    {
        try {
            $upazillas = $this->upazillaService->list(1);
            return $this->successResponse($upazillas, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }
     public function getById($id)
    {
        
        try {
            $upazilla= $this->upazillaService->getById($id);
            return $this->successResponse($upazilla, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function districtWiseUpazila(Request $request)
    {
        try {
            $upazillas = $this->upazillaService->getByDistrict($request->district_id);
            return $this->successResponse($upazillas, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:upazillas'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($this->Validtor($validator->errors()), 422);
        }

        try {
            $upazilla = $this->upazillaService->create($request->all());

            return $this->successResponse($upazilla, Response::HTTP_OK);
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
            $upazillaExists = $this->upazillaService->getByUId($id);
            if ($upazillaExists) {
                $district = $this->upazillaService->update($request->all(), $id);
            } else {
                return $this->errorResponse("Not found", Response::HTTP_NOT_FOUND);
            }
            return $this->successResponse($district, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }





}

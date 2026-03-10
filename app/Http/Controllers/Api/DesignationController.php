<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Traits\ApiResponser;


use App\Services\DesignationService;

use Exception;


class DesignationController extends Controller
{
    use ApiResponser;
    private $designationService;

    public function __construct(DesignationService $designationService)
    {
        $this->designationService = $designationService;
    }

    public function index(Request $request)
    {
        try {
            $designation = $this->designationService->list(1);
            return $this->successResponse($designation, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }
    public function getById($id)
    
    { 
        Log::info("jjj");
        Log::info($id);
        try {
            $designation= $this->designationService->getByUid($id);
            return $this->successResponse($designation, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:designations'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($this->Validtor($validator->errors()), 422);
        }

        try {
            $designation = $this->designationService->create($request->all(),$optimize=null);

            return $this->successResponse($designation, Response::HTTP_OK);
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
            $designationExists = $this->designationService->getByUid($id);
            if ($designationExists) {
                $designation = $this->designationService->update($request->all(),$optimize=null);
            } else {
                return $this->errorResponse("Not found", Response::HTTP_NOT_FOUND);
            }
            return $this->successResponse($designation, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function show($id)
    {
        try {
            return $this->successResponse($this->designationService->getByUid($id), Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }


}

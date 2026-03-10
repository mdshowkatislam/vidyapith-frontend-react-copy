<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Division;
use App\Services\DivisionService;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use Exception;

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
            $divisions = $this->divisionService->list();
            return $this->successResponse($divisions, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("Not found", Response::HTTP_NOT_FOUND);
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
            return $this->errorResponse($this->Validator($validator->errors()), 422);
        }

        try {
            $divisionExists = $this->divisionService->getByUId($id);
            if ($divisionExists) {
                $division = $this->divisionService->update($request->all(), $id);
            } else {
                return $this->errorResponse("Not found", Response::HTTP_NOT_FOUND);
            }
            return $this->successResponse($division, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\InventoryCategory\InventoryCategoryStoreRequest;
use App\Http\Requests\InventoryCategory\InventoryCategoryUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\InventoryCategoryService;
use Exception;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $inventoryCategoryService;

    public function __construct(InventoryCategoryService $inventoryCategoryService)
    {
        $this->inventoryCategoryService = $inventoryCategoryService;
    }

    public function index()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryCategoryList  = $this->inventoryCategoryService->getByEiinId($eiinId);

            return $this->successResponse($inventoryCategoryList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(InventoryCategoryStoreRequest $request)
    {
        try {
            $data = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $inventoryCategory = $this->inventoryCategoryService->create($data);

            $message = 'ক্যাটাগরি সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($inventoryCategory, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'ক্যাটাগরি তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $inventoryCategoryData = $this->inventoryCategoryService->getById($id);
            return $this->successResponse($inventoryCategoryData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function update(InventoryCategoryUpdateRequest $request)
    {
        try {
            $data = [
                'uid' => $request->uid,
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $inventoryCategory = $this->inventoryCategoryService->update($data);

            $message = 'ক্যাটাগরি সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($inventoryCategory, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'ক্যাটাগরি আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $this->inventoryCategoryService->delete($id);
        return $this->successMessage('ক্যাটাগরি এর তথ্যটি মুছে ফেলা হয়েছে।');
    }
}

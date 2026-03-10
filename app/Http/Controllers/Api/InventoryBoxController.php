<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\InventoryBox\InventoryBoxStoreRequest;
use App\Http\Requests\InventoryBox\InventoryBoxUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\InventoryBoxService;
use Exception;
use Illuminate\Http\Request;

class InventoryBoxController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $inventoryBoxService;

    public function __construct(InventoryBoxService $inventoryBoxService)
    {
        $this->inventoryBoxService = $inventoryBoxService;
    }

    public function index()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryBoxList  = $this->inventoryBoxService->getByEiinId($eiinId);

            return $this->successResponse($inventoryBoxList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(InventoryBoxStoreRequest $request)
    {
        try {
            $data = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'store_id' => $request->store_id,
                'rack_id' => $request->rack_id,
                'shelves_id' => $request->shelves_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $inventoryBox = $this->inventoryBoxService->create($data);

            $message = 'রেক সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($inventoryBox, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'রেক তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $inventoryBoxData = $this->inventoryBoxService->getById($id);
            return $this->successResponse($inventoryBoxData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function update(InventoryBoxUpdateRequest $request)
    {
        try {
            $data = [
                'uid' => $request->uid,
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'store_id' => $request->store_id,
                'rack_id' => $request->rack_id,
                'shelves_id' => $request->shelves_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'rec_status' => $request->rec_status ?? 1,
            ];


            $inventoryBox = $this->inventoryBoxService->update($data);

            $message = 'রেক সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($inventoryBox, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'রেক আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $this->inventoryBoxService->delete($id);
        return $this->successMessage('রেক এর তথ্যটি মুছে ফেলা হয়েছে।');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\InventoryRack\InventoryRackStoreRequest;
use App\Http\Requests\InventoryRack\InventoryRackUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\InventoryRackService;
use Exception;
use Illuminate\Http\Request;

class InventoryRackController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $inventoryRackService;

    public function __construct(InventoryRackService $inventoryRackService)
    {
        $this->inventoryRackService = $inventoryRackService;
    }

    public function index()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryRackList  = $this->inventoryRackService->getByEiinId($eiinId);

            return $this->successResponse($inventoryRackList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(InventoryRackStoreRequest $request)
    {
        try {
            $data = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'store_id' => $request->store_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $inventoryRack = $this->inventoryRackService->create($data);

            $message = 'রেক সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($inventoryRack, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'রেক তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $inventoryRackData = $this->inventoryRackService->getById($id);
            return $this->successResponse($inventoryRackData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function update(InventoryRackUpdateRequest $request)
    {
        try {
            $data = [
                'uid' => $request->uid,
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'store_id' => $request->store_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'rec_status' => $request->rec_status ?? 1,
            ];


            $inventoryRack = $this->inventoryRackService->update($data);

            $message = 'রেক সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($inventoryRack, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'রেক আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $this->inventoryRackService->delete($id);
        return $this->successMessage('রেক এর তথ্যটি মুছে ফেলা হয়েছে।');
    }
}

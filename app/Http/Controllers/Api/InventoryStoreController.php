<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\InventoryStore\InventoryStoreStoreRequest;
use App\Http\Requests\InventoryStore\InventoryStoreUpdateRequest;
use App\Models\InventoryStore;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\InventoryStoreService;
use Exception;
use Illuminate\Http\Request;

class InventoryStoreController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $inventoryStoreService;

    public function __construct(InventoryStoreService $inventoryStoreService)
    {
        $this->inventoryStoreService = $inventoryStoreService;
    }

    public function index()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryStoreList  = $this->inventoryStoreService->getByEiinId($eiinId);

            return $this->successResponse($inventoryStoreList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(InventoryStoreStoreRequest $request)
    {
        try {
            $data = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $inventoryStore = $this->inventoryStoreService->create($data);

            $message = 'স্টোর সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($inventoryStore, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'স্টোর তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $inventoryStoreData = $this->inventoryStoreService->getById($id);
            return $this->successResponse($inventoryStoreData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function update(InventoryStoreUpdateRequest $request)
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

            $inventoryStore = $this->inventoryStoreService->update($data);

            $message = 'স্টোর সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($inventoryStore, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'স্টোর আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $data = InventoryStore::with('racks', 'shelves')->where('uid', $id)->first();

        if(count($data->racks) > 0){
            return $this->successMessage('ইতিমধ্যে এই স্টোর এর অধীনে ' . en2bn(count($data->racks)) . ' টি রেক এর তথ্য রয়েছে।');
        }

        if(count($data->shelves) > 0){
            return $this->successMessage('ইতিমধ্যে এই স্টোর এর অধীনে ' . en2bn(count($data->racks)) . ' টি শেলফ এর তথ্য রয়েছে।');
        }

        if(count($data->boxes) > 0){
            return $this->successMessage('ইতিমধ্যে এই স্টোর এর অধীনে ' . en2bn(count($data->racks)) . ' টি বাক্স এর তথ্য রয়েছে।');
        }

        // $this->inventoryStoreService->delete($id);
        return $this->successMessage('স্টোর এর তথ্যটি মুছে ফেলা হয়েছে।');
    }
}

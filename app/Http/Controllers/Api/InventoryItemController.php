<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\InventoryItem\InventoryItemStoreRequest;
use App\Http\Requests\InventoryItem\InventoryItemUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\InventoryItemService;
use Exception;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $inventoryItemService;

    public function __construct(InventoryItemService $inventoryItemService)
    {
        $this->inventoryItemService = $inventoryItemService;
    }

    public function index()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryItemList  = $this->inventoryItemService->getByEiinId($eiinId);

            return $this->successResponse($inventoryItemList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(InventoryItemStoreRequest $request)
    {
        try {
            $data = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'category_id' => $request->category_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'specification' => $request->specification,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $inventoryItem = $this->inventoryItemService->create($data);

            $message = 'আইটেম সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($inventoryItem, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'আইটেম তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $inventoryItemData = $this->inventoryItemService->getById($id);
            return $this->successResponse($inventoryItemData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function update(InventoryItemUpdateRequest $request)
    {
        try {
            $data = [
                'uid' => $request->uid,
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'category_id' => $request->category_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'specification' => $request->specification,
                'rec_status' => $request->rec_status ?? 1,
            ];


            $inventoryItem = $this->inventoryItemService->update($data);

            $message = 'আইটেম সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($inventoryItem, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'আইটেম আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $this->inventoryItemService->delete($id);
        return $this->successMessage('আইটেম এর তথ্যটি মুছে ফেলা হয়েছে।');
    }
}

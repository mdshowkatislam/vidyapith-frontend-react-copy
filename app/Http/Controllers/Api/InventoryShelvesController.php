<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\InventoryShelves\InventoryShelvesStoreRequest;
use App\Http\Requests\InventoryShelves\InventoryShelvesUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\InventoryShelvesService;
use Exception;
use Illuminate\Http\Request;

class InventoryShelvesController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $inventoryShelvesService;

    public function __construct(InventoryShelvesService $inventoryShelvesService)
    {
        $this->inventoryShelvesService = $inventoryShelvesService;
    }

    public function index()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryShelvesList  = $this->inventoryShelvesService->getByEiinId($eiinId);

            return $this->successResponse($inventoryShelvesList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(InventoryShelvesStoreRequest $request)
    {
        try {
            $data = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'store_id' => $request->store_id,
                'rack_id' => $request->rack_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $inventoryShelves = $this->inventoryShelvesService->create($data);

            $message = 'রেক সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($inventoryShelves, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'রেক তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $inventoryShelvesData = $this->inventoryShelvesService->getById($id);
            return $this->successResponse($inventoryShelvesData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function update(InventoryShelvesUpdateRequest $request)
    {
        try {
            $data = [
                'uid' => $request->uid,
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'store_id' => $request->store_id,
                'rack_id' => $request->rack_id,
                'name_en' => $request->name_en,
                'name_bn' => $request->name_bn,
                'rec_status' => $request->rec_status ?? 1,
            ];


            $inventoryShelves = $this->inventoryShelvesService->update($data);

            $message = 'রেক সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($inventoryShelves, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'রেক আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $this->inventoryShelvesService->delete($id);
        return $this->successMessage('রেক এর তথ্যটি মুছে ফেলা হয়েছে।');
    }
}

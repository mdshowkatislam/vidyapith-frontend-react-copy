<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\InventoryProduct\InventoryProductStoreRequest;
use App\Http\Requests\InventoryProduct\InventoryProductUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\InventoryProductService;
use Exception;
use Illuminate\Http\Request;

class InventoryProductController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $inventoryProductService;

    public function __construct(InventoryProductService $inventoryProductService)
    {
        $this->inventoryProductService = $inventoryProductService;
    }

    public function index()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryProductList  = $this->inventoryProductService->getByEiinId($eiinId);

            return $this->successResponse($inventoryProductList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function generalIndex()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryProductList  = $this->inventoryProductService->generalIndex($eiinId);

            return $this->successResponse($inventoryProductList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function libraryIndex()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryProductList  = $this->inventoryProductService->libraryIndex($eiinId);

            return $this->successResponse($inventoryProductList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(InventoryProductStoreRequest $request)
    {
        try {
            $data = [
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'category_id' => $request->category_id,
                'item_id' => $request->item_id,
                'unique_no' => $request->unique_no,
                'author_name' => $request->author_name ?? null,
                'edition' => $request->edition ?? null,
                'price' => $request->price ?? null,
                'quantity' => $request->quantity ?? null,
                'purchase_date' => $request->purchase_date ?? null,
                'supplier' => $request->supplier ?? null,
                'location' => $request->location ?? null,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $inventoryProduct = $this->inventoryProductService->create($data);

            $message = 'প্রোডাক্ট সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($inventoryProduct, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'প্রোডাক্ট তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $inventoryProductData = $this->inventoryProductService->getById($id);
            return $this->successResponse($inventoryProductData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function update(InventoryProductUpdateRequest $request)
    {
        try {
            $data = [
                'uid' => $request->uid,
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'category_id' => $request->category_id,
                'item_id' => $request->item_id,
                'unique_no' => $request->unique_no,
                'author_name' => $request->author_name ?? null,
                'edition' => $request->edition ?? null,
                'price' => $request->price ?? null,
                'quantity' => $request->quantity ?? null,
                'purchase_date' => $request->purchase_date ?? null,
                'supplier' => $request->supplier ?? null,
                'location' => $request->location ?? null,
                'rec_status' => $request->rec_status ?? 1,
            ];


            $inventoryProduct = $this->inventoryProductService->update($data);

            $message = 'প্রোডাক্ট সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($inventoryProduct, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'প্রোডাক্ট আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $this->inventoryProductService->delete(id: $id);
        return $this->successMessage('প্রোডাক্ট এর তথ্যটি মুছে ফেলা হয়েছে।');
    }
}

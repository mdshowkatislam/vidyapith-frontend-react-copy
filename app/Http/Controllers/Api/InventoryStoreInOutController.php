<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryStoreLog;
use App\Models\LibFineConfiguration;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\InventoryBoxService;
use App\Services\InventoryProductService;
use App\Services\InventoryStoreService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class InventoryStoreInOutController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $inventoryProductService;
    private $inventoryBoxService;
    private $inventoryStoreService;

    public function __construct(InventoryBoxService $inventoryBoxService, InventoryProductService $inventoryProductService,InventoryStoreService $inventoryStoreService)
    {
        $this->inventoryProductService = $inventoryProductService;
        $this->inventoryBoxService = $inventoryBoxService;
        $this->inventoryStoreService = $inventoryStoreService;
    }

    /*
    =======================================================
                    Store In Api
    =======================================================
    */
    public function storeInList()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryStoreInList  = InventoryStoreLog::on('db_read')->where('eiin', $eiinId)->get();
            return $this->successResponse($inventoryStoreInList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function storeIn(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'branch_id'  => 'required',
                'product_id'   => 'required',
            ]);
            if ($validation->fails()) return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);

            $inventoryProductData = $this->inventoryProductService->getById($request->product_id);
            if ($inventoryProductData->stock_in_at) return $this->errorResponse('Already Store This Product', Response::HTTP_NOT_FOUND);

            $box_id = null;
            $box_name = null;
            if(array_key_exists('box_id', $request->all()) && !empty($request->box_id)){
                $inventoryBoxData = $this->inventoryBoxService->getById($request->box_id);
                $box_id = $inventoryBoxData->uid;
                $box_name = $inventoryBoxData->name_en;
            }

            $store_id = null;
            $store_name = null;
            if(array_key_exists('store_id', $request->all()) && !empty($request->store_id)){
                $inventoryStoreData = $this->inventoryStoreService->getById($request->store_id);
                $store_id = $inventoryStoreData->uid;
                $store_name = $inventoryStoreData->name_en;
            }

            DB::beginTransaction();

            $inventoryProductData->update([
                'stock_in_at' => now(),
                'stock_in_by' => app('sso-auth')->user()->uid,
                'store_id' => $store_id,
                'store_name' => $store_name,
                'location_id' => $box_id,
                'location' => $box_name,
                'stock_out_at' => null,
                'stock_out_by' => null,
                'assign_by' => null,
                'assign_type' => null,
                'return_date' => null,
                'actual_return' => null,
            ]);

            InventoryStoreLog::create([
                'eiin' => app('sso-auth')->user()->eiin,
                'branch_id' => $request->branch_id,
                'product_id' => $request->product_id,
                'stock_in_at' => now(),
                'stock_in_by' => app('sso-auth')->user()->uid,
                'store_id' => $store_id,
                'store_name' => $store_name,
                'location_id' => $box_id,
                'location' => $box_name,
                'stock_out_at' => null,
                'stock_out_by' => null,
                'assign_by' => null,
                'assign_type' => null,
                'return_date' => null,
                'actual_return' => null,
            ]);
            DB::commit();
            return $this->successResponseWithData([], 'Successfully Store In Done', Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }


     /*
    =======================================================
                    Store Out Api
    =======================================================
    */

    public function storeOutList()
    {
        try {
            $eiinId     = app('sso-auth')->user()->eiin;
            $inventoryStoreInList  = InventoryStoreLog::on('db_read')
                                    ->where('eiin', $eiinId)
                                    ->whereNotNull('stock_out_at')
                                    ->whereNull('actual_return')
                                    ->get();
            return $this->successResponse($inventoryStoreInList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function storeOut(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'branch_id'  => 'required',
                'product_id' => 'required',
                'assign_id'  => 'required',
                'assign_type'=> 'required',
                'return_date'=> 'required',
            ]);
            if ($validation->fails()) return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);

            $inventoryProductData = $this->inventoryProductService->getById($request->product_id);
            if (!$inventoryProductData->stock_in_at) return $this->errorResponse('This Product Not Available In Store, Please Store This Product', Response::HTTP_NOT_FOUND);
            if ($inventoryProductData->stock_out_at) return $this->errorResponse('Already Store Out This Product', Response::HTTP_NOT_FOUND);
            
            DB::beginTransaction();
            $inventoryProductData->update([
                'stock_out_at' => now(),
                'stock_out_by' => app('sso-auth')->user()->uid,
                'assign_by' => $request->assign_id,
                'assign_type' => $request->assign_type,
                'return_date' => $request->return_date,
            ]);

            $inventoryStoreLog = InventoryStoreLog::where('product_id', $request->product_id)
                                    ->whereNotNull('stock_in_at')
                                    ->whereNull('stock_out_at')
                                    ->orderBy('id', 'DESC')
                                    ->first();
            
            $inventoryStoreLog->update([
                'stock_out_at' => now(),
                'stock_out_by' => app('sso-auth')->user()->uid,
                'assign_by' => $request->assign_id,
                'assign_type' => $request->assign_type,
                'return_date' => $request->return_date,
            ]);
            DB::commit();
            return $this->successResponseWithData([], 'Successfully Store Out Done', Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function storeReturn(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'branch_id'  => 'required',
                'product_id' => 'required',
                'assign_id'  => 'required',
                'assign_type'=> 'required',
                'actual_return'=> 'required',
                'is_loss'=> ['required', 'in:0,1'],
                'is_damage'=> ['required', 'in:0,1'],
            ]);
            if ($validation->fails()) return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);

            
            $inventoryProductData = $this->inventoryProductService->getById($request->product_id);
            if (!$inventoryProductData->stock_in_at) return $this->errorResponse('This Product Not Available In Store, Please Store This Product', Response::HTTP_NOT_FOUND);
            if (!$inventoryProductData->stock_out_at) return $this->errorResponse('This Product Not Stock Out From Store', Response::HTTP_NOT_FOUND);
            if ($inventoryProductData->actual_return) return $this->errorResponse('Already Return This product', Response::HTTP_NOT_FOUND);
            
            $box_id = null;
            $box_name = null;
            if(array_key_exists('box_id', $request->all()) && !empty($request->box_id)){
                $inventoryBoxData = $this->inventoryBoxService->getById($request->box_id);
                $box_id = $inventoryBoxData->uid;
                $box_name = $inventoryBoxData->name_en;
            }
            
            $store_id = null;
            $store_name = null;
            if(array_key_exists('store_id', $request->all()) && !empty($request->store_id)){
                $inventoryStoreData = $this->inventoryStoreService->getById($request->store_id);
                $store_id = $inventoryStoreData->uid;
                $store_name = $inventoryStoreData->name_en;
            }
            
            //fine calculate
            $dueDate = Carbon::parse($inventoryProductData->return_date);
            $returnDate = Carbon::parse($request->actual_return);
            $overdueDays = $dueDate->diffInDays($returnDate, false);
            $libFineConfigur = LibFineConfiguration::where('branch_id', $request->branch_id)->first();
            $fineAmount = 0;

            if($request->is_loss == 1){
                $fineAmount = $inventoryProductData->price + $libFineConfigur->loss_fine_amount;
            }else if($overdueDays > 0){
                switch ($libFineConfigur->fine_type) {
                    case 'daily':
                        $fineAmount = $overdueDays * $libFineConfigur->fine_amount; // Example: 5 Taka per day
                        break;
                    case 'weekly':
                        $weeksOverdue = ceil($overdueDays / 7);
                        $fineAmount = $weeksOverdue * $libFineConfigur->fine_amount; // Example: 30 Taka per week
                        break;
                    case 'monthly':
                        $monthsOverdue = ceil($overdueDays / 30);
                        $fineAmount = $monthsOverdue * $libFineConfigur->fine_amount; // Example: 100 Taka per month
                        break;
                    case 'fixed':
                        $fineAmount = $libFineConfigur->fine_amount; // Example: Fixed fine of 200 Taka
                        break;
                }
            }

            if($request->is_damage == 1){
                $fineAmount += $libFineConfigur->damage_fine_amount;
            }

            DB::beginTransaction();

            $inventoryProductData->update([
                'stock_in_at' => now(),
                'stock_in_by' => app('sso-auth')->user()->uid,
                'store_id' => $store_id,
                'store_name' => $store_name,
                'location_id' => $box_id,
                'location' => $box_name,
                'stock_out_at' => null,
                'stock_out_by' => null,
                'assign_by' => null,
                'assign_type' => null,
                'return_date' => null,
                'actual_return' => $request->actual_return,
            ]);

            $inventoryStoreLog = InventoryStoreLog::where('product_id', $request->product_id)
                                    ->whereNotNull('stock_in_at')
                                    ->whereNotNull('stock_out_at')
                                    ->whereNull('actual_return')
                                    ->orderBy('id', 'DESC')
                                    ->first();
        
            $inventoryStoreLog->update([
               'actual_return' => $request->actual_return,
               'fine_type' => $fineAmount > 0 ? $libFineConfigur->fine_type : null,
               'fine_amount' => $fineAmount,
               'is_loss' => $request->is_loss,
               'is_damage' => $request->is_damage,
            ]);
            
            DB::commit();
            return $this->successResponseWithData([], 'Successfully Store Return Done', Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }


        /*
    =======================================================
                    Libraryy Fine Calculate Api
    =======================================================
    */

    public function studentLibraryFine(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'student_id'  => 'required',
            ]);
            if ($validation->fails()) return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);

            $studentFine =  InventoryStoreLog::where('assign_by', $request->student_id)
                            ->where('is_paid', 0)
                            ->where('fine_amount','>', 0)
                            ->orderBy('id', 'DESC')
                            ->get();

            $unique_id = hexdec(uniqid());

            InventoryStoreLog::whereIn('id', $studentFine->pluck('id'))->update(['unique_id' => $unique_id]);

            $data = [
                'unique_id' => $unique_id,
                'fine_amount' => $studentFine->sum('fine_amount'),
            ];
            return $this->successResponseWithData($data, 'Student Library Fine Data!', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function studentLibraryFinePaid(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'unique_id'  => 'required',
                'is_paid'  => 'required',
            ]);
            if ($validation->fails()) return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);

            $studentFine =  InventoryStoreLog::where('unique_id', $request->unique_id)
                                            ->update([
                                                'is_paid' => $request->is_paid
                                            ]);

            return $this->successResponseWithData([], 'Student Library Fine Paid Successfully Done!', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }


}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\LibFineConfiguration\LibFineConfigurationStoreRequest;
use App\Models\Attendance;
use App\Models\MarkDistribution;
use App\Models\LibFine;
use App\Models\LibFineConfiguration;
use App\Models\AttendanceConfigure;
use App\Models\Student;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\LibFineConfigurationService;
use Exception;
use Illuminate\Support\Facades\Validator;

class LibFineConfigureController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $libFineConfigurationService;

    public function __construct(LibFineConfigurationService $libFineConfigurationService)
    {
        $this->libFineConfigurationService = $libFineConfigurationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $classTestList = $this->libFineConfigurationService->getByEiinId($eiinId);
            return $this->successResponse($classTestList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'fine_type'   => ['required', 'in:daily,weekly,monthly,fixed'],
                'fine_amount' => 'required',
                'damage_fine_amount' => 'required',
                'loss_fine_amount'  => 'required',
            ];
    
            if (isset($request->libFine_configuration_uid) && !empty($request->libFine_configuration_uid)) {
                // Update Condition
                $rules['branch_id'] = 'required|unique:lab_fine_configures,branch_id,' . $request->libFine_configuration_uid . ',uid';
            } else {
                // Create Condition
                $rules['branch_id'] = 'required|unique:lab_fine_configures';
            }

            $validation = Validator::make($request->all(), $rules);
    
    
            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            }
            $status = 'তৈরি';
            $libFineConfiguration = '';

            $payload = [
                'eiin'              => app('sso-auth')->user()->eiin,
                'branch_id'         => $request->branch_id,
                'fine_type'         => $request->fine_type,
                'fine_amount'       => $request->fine_amount,
                'damage_fine_amount'=> $request->damage_fine_amount,
                'loss_fine_amount'  => $request->loss_fine_amount,
            ];

            if (isset($request->libFine_configuration_uid) && !empty($request->libFine_configuration_uid)) {
                $status = 'আপডেট';
                $payload['uid'] = $request->libFine_configuration_uid;
                $libFineConfiguration = $this->libFineConfigurationService->update($payload);
            } else {
                $status = 'তৈরি';
                $libFineConfiguration = $this->libFineConfigurationService->create($payload);
            }
            $message = 'লাইব্রেরী কনফিগার সফলভাবে ' . $status . ' করা হয়েছে।';
            return $this->successResponseWithData($libFineConfiguration, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'লাইব্রেরী কনফিগার তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($uid)
    {
        try {
            $branch = $this->libFineConfigurationService->getById($uid);
            if ($branch) {
                return $this->successResponse($branch, Response::HTTP_OK);
            } else {
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->libFineConfigurationService->delete($id);
        return response()->json(['status' => 'success', 'message' => 'লাইব্রেরী কনফিগার তথ্যটি মুছে ফেলা হয়েছে।']);
    }
    
}

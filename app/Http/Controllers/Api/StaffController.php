<?php

namespace App\Http\Controllers\Api;

use App\Helper\SmsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffStoreRequest;
use App\Http\Requests\Staff\StaffUpdateRequest;
use App\Models\Designation;
use App\Models\StaffSms;
use App\Services\Api\SmsLogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use App\Services\StaffService;
use App\Services\UserService;
use App\Services\Api\AuthService;
use Illuminate\Support\Facades\Log; 
use Exception;


class StaffController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $staffService;
    private $userService;
    private $authService;
    private $smsLogService;

    public function __construct(StaffService $staffService, UserService $userService, AuthService $authService, SmsLogService $smsLogService)
    {
        $this->staffService = $staffService;
        $this->userService = $userService;
        $this->authService = $authService;
        $this->smsLogService = $smsLogService;
    }

    public function index(Request $request)
    {
        try {
            $eiinId = getAuthInfo()['eiin'];
            // $staffs = $this->staffService->list();
            $staffs = $this->staffService->getByEiinId($eiinId, null, null, $request->search);
            return $this->successResponse($staffs, Response::HTTP_OK);
        } catch (Exception $exc) {
            // return $this->errorResponse( $exc->getMessage(), Response::HTTP_NOT_FOUND);
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function getById($id)
    {
        try {
            $staff = $this->staffService->getById($id);
            if ($staff) {
                return $this->successResponse($staff, Response::HTTP_OK);
            } else {
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }
    public function getByEmpId($emp_id)
    {
        try {
            $staff = $this->staffService->getByEmpId($emp_id);
            if ($staff) {
                return $this->successResponse($staff, Response::HTTP_OK);
            } else {
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function designationList(Request $request)
    {
        try {
            $designations = Designation::on('db_read')->select('uid', 'designation_name')->get();
            return $this->successResponse($designations, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function store(StaffStoreRequest $request)
    {
        // Log::info('staffCntroller'); 
        // Log::info($request->all()); 

        $eiinId=getAuthInfo()['eiin'];
         Log::info($eiinId); 
        try {
            DB::beginTransaction();
            $authRequest = $this->authService->staff($request->all(), $eiinId);
            
            if (@$authRequest->status == true) {
                $authData = (object) $authRequest->data;
                $request['caid'] = $authData->caid;
                $request['eiin'] = $authData->eiin;

                $findByTrash = $this->staffService->getWithTrashedById($authData->caid);

                if ($findByTrash && $findByTrash->caid) {
                    $requestData = $request->all();
                    $is_disabled = null;

                    $requestData['caid'] = @$findByTrash->caid;
                    $requestData['eiin'] =  $eiinId;

                    if (!empty($findByTrash->deleted_at)) {
                        $staff_data = $this->staffService->update($requestData, $findByTrash->uid, true);
                    } else {
                        $staff_data = $this->staffService->update($requestData, $findByTrash->uid);
                    }

                    if (@$findByTrash->mobile_no != $requestData['mobile_no']) {
                        $requestData['is_sms_send'] = 3;
                    }

                    $user_account = $this->authService->accountUpdate($requestData, $findByTrash->caid, $requestData['eiin'], $is_disabled, 1);

                    if (!$user_account) {
                        $user_account = $this->authService->staff($requestData, $requestData['eiin']);
                    }
                    $requestData['user_type_id'] =  $user_account->data['user_type_id'];
                    $requestData['role'] =  $user_account->data['role'];
                    $this->userService->update($findByTrash->caid, $requestData);
                } else {
                    $request['is_foreign'] = 0;
                    $staff_data = $this->staffService->create($request->all());
                }
                DB::commit();
                $message = 'স্টাফ সফলভাবে যুক্ত করা হয়েছে।';
                return $this->successResponseWithData($staff_data, $message, Response::HTTP_OK);
            } else {
                DB::rollBack();
                $message = $authRequest->message;
                return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'স্টাফ যুক্ত করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function update(StaffUpdateRequest $request)
    { 
        Log::info('GGG');
        Log::info($request->all() );
        
        try {
            $eiinId = getAuthInfo()['eiin'];
            DB::beginTransaction();
            $findByTrash = $this->staffService->getWithTrashedById($request->uid);

            if ($findByTrash && $findByTrash->caid) {
                $requestData = $request->all();

                if ($request->is_disabled) {
                    $is_disabled = $request->is_disabled; //not sent message , msg send if null
                } else {
                    $is_disabled = null;
                }
                $requestData['caid'] = @$findByTrash->caid;
                $requestData['eiin'] = $eiinId;

                if (!empty($findByTrash->deleted_at)) {
                    $staff_data = $this->staffService->update($requestData, $findByTrash->uid, true);
                } else {
                    $staff_data = $this->staffService->update($requestData, $findByTrash->uid);
                }

                if (@$findByTrash->mobile_no != $requestData['mobile_no']) {
                    $requestData['is_sms_send'] = 3;
                }

                $user_account = $this->authService->accountUpdate($requestData, $findByTrash->caid, null, $is_disabled, 1);

                if (!$user_account) {
                    $user_account = $this->authService->staff($requestData, $requestData['eiin']);
                }
                $requestData['user_type_id'] =  $user_account->data['user_type_id'];
                $requestData['role'] =  $user_account->data['role'];
                $this->userService->update($findByTrash->caid, $requestData);
                DB::commit();

                $message = 'স্টাফের তথ্য সফলভাবে আপডেট করা হয়েছে।';
                return $this->successResponseWithData($staff_data, $message, Response::HTTP_OK);
            } else {
                DB::rollBack();
                $message = 'স্টাফের তথ্য আপডেট করা যায় নি।';
                return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $message = 'স্টাফের তথ্য আপডেট করা যায় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy(Request $request)
    {
        $this->staffService->delete($request->id);
        return $this->successMessage('স্টাফ এর তথ্যটি মুছে ফেলা হয়েছে।');
    }

    public function bulkStaffSmsSend(Request $request)
    {
        $validation = Validator::make($request->all(), [
            // 'branch_id'  => 'required',
            // 'version_id' => 'required',
            // 'shift_id'   => 'required',
            // 'class_id'   => 'required',
            // 'section_id' => 'required',

            'staffData' => 'required|array|min:1',
            'staffData.*.staff_id' => 'required',
            'staffData.*.phone_no' => 'required',
            'staffData.*.text' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            // return $this->error($validation->errors()->first(), 400, []);
        }

        if(!array_key_exists('staffData', $request->all()) || count($request->staffData) == 0) return $this->errorResponse('কমপক্ষে একজন ছাত্র/ছাত্রী হাজিরা ইনপুট দিন', Response::HTTP_NOT_ACCEPTABLE);

        $staffs = [];
        foreach ($request->staffData as $key => $data) {
            $payload = [
                'eiin'          => app('sso-auth')->user()->eiin,
                // 'branch_id'     => $request->branch_id,
                // 'version_id'    => $request->version_id,
                // 'shift_id'      => $request->shift_id,
                // 'class_id'      => $request->class_id,
                // 'section_id'    => $request->section_id,
                'staff_id'      => $data['staff_id'] ?? null,
                'phone_no'      => $data['phone_no'] ?? null,
                'text'          => $data['text'] ?? null,
            ];

            $staffs[] = StaffSms::create($payload);

            $textSend = SmsService::sendSMS($data['text'],  $data['phone_no']);
            $this->smsLogService->store(app('sso-auth')->user()->eiin, $data['phone_no'], $data['text'], $textSend, $data['staff_id']);
        }

        return $this->successResponseWithData($staffs, '', Response::HTTP_OK);
    }

}

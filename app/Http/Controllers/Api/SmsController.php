<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\SmsService;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    use ApiResponser;
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function send(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'message'  => 'required',
                'number'   => 'required',
            ]);
    
            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            }
    
            $message = $request->input('message');
            $number = $request->input('number');
    
            $result = $this->smsService->sendSMS($message, $number);
    
            return response()->json(['status' => 'success', 'message' => $result]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
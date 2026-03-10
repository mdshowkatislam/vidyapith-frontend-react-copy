<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Exception;

class PaymentController extends Controller
{
    use ApiResponser, ValidtorMapper;

    public function __construct() {}
    /**
     * Store a newly created resource
     */
    public function spgDataUpdate(Request $request)
    {
        try {
            if ($request->credential['username'] == 'noipspg' && $request->credential['password'] == 'noip321') {
                $payment = Payment::where('session_token', $request->data['session_token'])->first();
                if ($payment) {
                    $response = [
                        'status' => $payment->payment_status_code,
                        'msg' => $payment->payment_status,
                        'transactionid' => $payment->transaction_id
                    ];
                } else {
                    $response = [
                        'status' => '5555',
                        'msg' => 'Error data not Process'
                    ];
                }
                return response()->json($response);
            }
            else{
                $response = [
                    'status' => '401',
                    'msg' => 'Unauthorized !!!',
                ];
                return response()->json($response);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $response = [
                'status' => '5555',
                'msg' => 'Error data not Process',
            ];
            return response()->json($response);
        }
    }
}

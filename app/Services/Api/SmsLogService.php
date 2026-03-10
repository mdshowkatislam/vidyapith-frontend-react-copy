<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use Exception;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

use App\Helper\UtilsCookie;

class SmsLogService
{
    private $base_url;
    public function __construct()
    {
        $this->base_url = config('app.base_url') . 'sms-log-store';
    }

    public function store($eiin, $mobile, $sms_content, $response, $send_user_id=null)
    {
        try {
            $accessToken = request()->bearerToken() ?? UtilsCookie::getCookie();
          
            $payload = [
                'eiin'          => $eiin,
                'mobile'        => $mobile,
                'sms_content'   => $sms_content,
                'response'      => $response,
                'send_user_id'  => $send_user_id,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ])
                ->withOptions([
                    'verify' => false
                ])
                ->post($this->base_url, $payload);

            if (!$response->successful()) {
                $response->throw();
            }
            $result = (object) json_decode($response->getBody(), true);

            return $result;
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

}

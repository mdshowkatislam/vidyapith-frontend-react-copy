<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;
use Exception;

class UtilsUserInfo {

    public static function userInfo()
    {
        try {
            $account_info = config('app.base_url') . 'user/account-info';

            $accessToken = request()->bearerToken() ?? UtilsCookie::getCookie();
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ])
            ->withOptions([
                'verify' => false
            ])
            ->get($account_info);
                if (!$response->successful()) {
                    $response->throw();
                }
                $result = (object) json_decode($response->getBody(), true);
                return $result;
            }  catch (Exception $e) {
                return (object) [
                    'status' => false,
                    'message' => $e->getMessage()
                ];
            }
    }

}
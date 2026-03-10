<?php

namespace App\Helper;

use SoapClient;
use Exception;
use Illuminate\Support\Facades\Http;

class SmsService
{
    // protected static $soapClient;
    // protected static $username;
    // protected static $password;

    protected static $apiUrl = 'https://bulksmsbd.net/api/smsapi';
    protected static $apiKey;
    protected static $senderId;

    public static function init()
    {
        // self::$soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
        // self::$username = env('ONNOROKOM_SMS_USER', 'your_username');
        // self::$password = env('ONNOROKOM_SMS_PASSWORD', 'your_password');

        self::$apiKey = config('app.BULKSMSBD_API_KEY');
        self::$senderId = config('app.BULKSMSBD_SENDER_ID');
    }

    public static function sendSMS($message, $number)
    {   
       
        try {
            if (!self::$apiKey || !self::$senderId) {
                self::init();
            }
            $maskedApiKey = self::$apiKey ? (substr(self::$apiKey, 0, 4) . '...' . substr(self::$apiKey, -4)) : null;
            $maskedSender = self::$senderId ? (substr(self::$senderId, 0, 4) . '...' . substr(self::$senderId, -4)) : null;

            $params = [
                'api_key'  => self::$apiKey,
                'senderid' => self::$senderId,
                'number'   => $number,
                'message'  => $message,
            ];

            $response = Http::withOptions(['verify' => false])->get(self::$apiUrl, $params);

            $body = $response->body();
            $status = $response->status();
            $ok = $response->ok();
            $successful = $response->successful();
            $headers = method_exists($response, 'headers') ? $response->headers() : null;

            // Try to decode JSON response for clearer logging
            $decoded = null;
            try {
                $decoded = json_decode($body, true);
            } catch (Exception $ex) {
                $decoded = null;
            }

            return $body;
        } catch (Exception $e) {
            \Log::error('sms.exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return "Error: " . $e->getMessage();
        }
    }

    // public static function sendSMS($message, $number)
    // {
    //     try {
    //         if (!self::$soapClient) {
    //             self::init(); // Initialize if not already done
    //         }

    //         $params = [
    //             'userName'     => self::$username,
    //             'userPassword' => self::$password,
    //             'messageText'  => $message,
    //             'numberList'   => $number, 
    //             'smsType'      => "TEXT",
    //             'maskName'     => '',
    //             'campaignName' => '',
    //         ];

    //         $response = self::$soapClient->__call("OneToMany", [$params]);

    //         return $response->OneToManyResult; // API response
    //     } catch (Exception $e) {
    //         return "Error: " . $e->getMessage();
    //     }
    // }

}

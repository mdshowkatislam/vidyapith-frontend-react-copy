<?php

namespace App\Helper;

use SoapClient;
use Exception;

class SmsService
{
    protected static $soapClient;
    protected static $username;
    protected static $password;

    public static function init()
    {
        self::$soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
        self::$username = env('ONNOROKOM_SMS_USER', 'your_username');
        self::$password = env('ONNOROKOM_SMS_PASSWORD', 'your_password');
    }

    public static function sendSMS($message, $number)
    {
        try {
            if (!self::$soapClient) {
                self::init(); // Initialize if not already done
            }

            $params = [
                'userName'     => self::$username,
                'userPassword' => self::$password,
                'messageText'  => $message,
                'numberList'   => $number, 
                'smsType'      => "TEXT",
                'maskName'     => '',
                'campaignName' => '',
            ];

            $response = self::$soapClient->__call("OneToMany", [$params]);

            return $response->OneToManyResult; // API response
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

}

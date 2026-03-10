<?php

namespace App\Helper;

use Exception;
use Illuminate\Http\Request;
use File;

class MyLogs {

    public static function log($request, $table = NULL, $method = NULL) {
        try {
            date_default_timezone_set('Asia/Dhaka');
         
            $dr = storage_path() . '/logs/institute/';
            if (!file_exists($dr)) {
                File::makeDirectory($dr, 0775, true, true);
            }
            $log_file = $dr . 'log-' . date("Y-m-d") . '.txt';

            $dataToLog = array(
                ' - *******************   ' . $table . '  ' . $method . '  **************' . PHP_EOL,
                date("Y-m-d H:i:s") . PHP_EOL, //Custom text
                json_encode($request, true) . PHP_EOL,
                '*******************   end  **************' . PHP_EOL,
            );
            $data = implode(" - ", $dataToLog);
            $data .= PHP_EOL;

            file_put_contents($log_file, $data, FILE_APPEND); 
        } catch (Exception $ex) {
            print_r($ex);
        }
    }

    public static function Institute($request, $message) {
        try {
            date_default_timezone_set('Asia/Dhaka');
         
            $dr = storage_path() . '/logs/institute/';
            if (!file_exists($dr)) {
                File::makeDirectory($dr, 0775, true, true);
            }
            $log_file = $dr . 'log-' . date("Y-m-d") . '.txt';

            $dataToLog = array(
                ' - *******************   Institute & Head Teacher POST **************' . PHP_EOL,
                ' - ******************* ' . $message . '**************' . PHP_EOL,
                date("Y-m-d H:i:s") . PHP_EOL, //Custom text
                json_encode($request, true) . PHP_EOL,
                '*******************   end  **************' . PHP_EOL,
            );
            $data = implode(" - ", $dataToLog);
            $data .= PHP_EOL;

            file_put_contents($log_file, $data, FILE_APPEND); 
        } catch (Exception $ex) {
            print_r($ex);
        }
    }

    public static function HeadTeacher($request, $message) {
        try {
            date_default_timezone_set('Asia/Dhaka');
         
            $dr = storage_path() . '/logs/head-teacher/';
            if (!file_exists($dr)) {
                File::makeDirectory($dr, 0775, true, true);
            }
            $log_file = $dr . 'log-' . date("Y-m-d") . '.txt';

            $dataToLog = array(
                ' - *******************   Head Teacher POST **************' . PHP_EOL,
                ' - ******************* ' . $message . '**************' . PHP_EOL,
                date("Y-m-d H:i:s") . PHP_EOL, //Custom text
                json_encode($request, true) . PHP_EOL,
                '*******************   end  **************' . PHP_EOL,
            );
            $data = implode(" - ", $dataToLog);
            $data .= PHP_EOL;

            file_put_contents($log_file, $data, FILE_APPEND); 
        } catch (Exception $ex) {
            print_r($ex);
        }
    }

    public static function smsCountLog($request, $message) {
        try {
            date_default_timezone_set('Asia/Dhaka');
         
            $dr = storage_path() . '/logs/sms-count/';
            if (!file_exists($dr)) {
                File::makeDirectory($dr, 0775, true, true);
            }
            $log_file = $dr . 'log-' . date("Y-m-d") . '.txt';

            $dataToLog = array(
                ' - *******************   sms count POST **************' . PHP_EOL,
                ' - ******************* ' . $message . '**************' . PHP_EOL,
                date("Y-m-d H:i:s") . PHP_EOL, //Custom text
                json_encode($request, true) . PHP_EOL,
                '*******************   end  **************' . PHP_EOL,
            );
            $data = implode(" - ", $dataToLog);
            $data .= PHP_EOL;

            file_put_contents($log_file, $data, FILE_APPEND); 
        } catch (Exception $ex) {
            print_r($ex);
        }
    }
}
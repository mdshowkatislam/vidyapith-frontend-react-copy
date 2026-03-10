<?php

use App\Models\Institute;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

if (!function_exists('toRawSql')) {
    function toRawSql($query)
    {
        $format = $query->toSql();
        $replacements = $query->getBindings();
        $to_raw_sql = preg_replace_callback('/\?/', function ($matches) use (&$replacements) {
            return array_shift($replacements);
        }, $format);
        dd($to_raw_sql);
    }
}

if (!function_exists('dddd')) {
    function dddd($query)
    {
        dd($query->toArray());
    }
}

if (!function_exists('isApi')) {
    function isApi()
    {
        return Str::startsWith(request()->path(), 'api');
    }
}

if (!function_exists('trimNumeric')) {
    function trimNumeric($number)
    {
        $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "AM", "PM", "am", "pm", "Jan", "Feb", "Mar", "Apr", "May", "Jun", 'Jul', "Aug", "Sep", "Oct", "Nov", "Dec");
        $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০", "এ.এম", "পি.এম", "এ.এম", "পি.এম", "জানুয়ারী", "ফেব্রুয়ারী", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "অগাস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর", "খাল", "রেগুলেটর", "এলএলপি");

        return preg_replace("/[^0-9]+/", "", str_replace($bn, $en, $number));
    }
}

if (!function_exists('bn2en')) {
    function bn2en($number)
    {
        $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "AM", "PM", "am", "pm", "Jan", "Feb", "Mar", "Apr", "May", "Jun", 'Jul', "Aug", "Sep", "Oct", "Nov", "Dec");
        $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০", "এ.এম", "পি.এম", "এ.এম", "পি.এম", "জানুয়ারী", "ফেব্রুয়ারী", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "অগাস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর");
        return str_replace($bn, $en, $number);
    }
}

if (!function_exists('en2bn')) {
    function en2bn($number)
    {
        $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "AM", "PM", "am", "pm", "Jan", "Feb", "Mar", "Apr", "May", "Jun", 'Jul', "Aug", "Sep", "Oct", "Nov", "Dec", "Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Weekend");
        $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০", "এ.এম", "পি.এম", "এ.এম", "পি.এম", "জানুয়ারী", "ফেব্রুয়ারী", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "অগাস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর", "শনিবার", "রবিবার", "সোমবার", "মঙ্গলবার", "বুধবার", "বৃহস্পতিবার", "শুক্রবার", "সাপ্তাহিক ছুটি");
        return str_replace($en, $bn, $number);
    }
}
if (!function_exists('mobileBrowserTurnOff')) {
    function mobileBrowserTurnOff($userAgent)
    {
        if (strpos($userAgent, 'Android') !== false || strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
            return true;
            // abort_if(true, Response::HTTP_FORBIDDEN, 'You are not able to access Mobile , Please do it Desktop or Laptop');
            // $aa= response()->json(['device' => 'mobile']);
        } else {
            return false;
            // $aa= response()->json(['device' => 'web']);
        }
    }
}

if (!function_exists('getBoardData')) {
    function getBoardData()
    {
        $institute = Institute::with('board')->where('eiin', auth()->user()->eiin)->first();
        return $institute->board;
    }
}




if (!function_exists('getTestAuthentication')) {
    /**
     * Get test authentication parameters for local environment
     * 
     * @return array
     */
    function getTestAuthentication()
    {
        $eiinId = request()->get('test_eiin', env('TEST_EIIN', 134172));
        $userTypeId = (int)request()->get('test_user_type_id', env('TEST_USER_TYPE_ID', 3));

        return [
            'eiinId' => $eiinId,
            'userTypeId' => $userTypeId
        ];
    }
}

if (!function_exists('isLocalWithTestAuth')) {
    /**
     * Check if running in local environment with test authentication
     * 
     * @return bool
     */
    function isLocalWithTestAuth()
    {
        return app()->environment('local');
    }

    
if (!function_exists('getAuthInfo')) {
    /**
     * Get authentication information with fallback for local testing
     *
     * @return array
     */
    function getAuthInfo()
    {
        if (isLocalWithTestAuth()) {
            $testAuth = getTestAuthentication();
            return [
                'eiin' => $testAuth['eiinId'] ?? null,
                'user_type_id' => $testAuth['userTypeId'] ?? null,
                'user' => null
            ];
        } else {
            $authUser = app('sso-auth')->user();
            if (!$authUser) {
                if (isLocalWithTestAuth()) {
                    $testAuth = getTestAuthentication();
                    return [
                        'eiin' => $testAuth['eiinId'] ?? null,
                        'user_type_id' => $testAuth['userTypeId'] ?? null,
                        'user' => null
                    ];
                } else {
                    throw new Exception('User not authenticated', 401);
                }
            } else {
                return [
                    'eiin' => $authUser->eiin,
                    'user_type_id' => $authUser->user_type_id,
                    'user' => $authUser
                ];
            }
        }
    }
}
}

<?php

namespace App\Helper;


class UtilsApiEndpoint {

    public static function accountCreate(){

       return config('app.base_url').'user/account-create';
    }


}
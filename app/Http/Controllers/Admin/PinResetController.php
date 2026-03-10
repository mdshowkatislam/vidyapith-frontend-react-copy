<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PinResetController extends Controller
{
    public function otpPageView(){
        return view('frontend/noipunno/dashboard/reset-pin/index');
    }

     public function changeNewPin(){
         return view('frontend/noipunno/dashboard/reset-pin/change-pin');
     }
}

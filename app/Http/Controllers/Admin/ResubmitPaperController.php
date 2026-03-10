<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResubmitPaperController extends Controller
{
    public function resubmitPaper(Request $request){
// dd($request->all());
        return view('frontend/noipunno/resubmit-paper/resubmit-paper');
    }
}

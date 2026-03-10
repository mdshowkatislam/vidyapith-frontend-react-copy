<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ValidtorMapper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class CountryController extends Controller
{
    use ApiResponser, ValidtorMapper;

    public function countryList(Request $request)
    {
        $countries = DB::connection('db_read')->table('countries')->pluck('countryName', 'uid')->toArray();
        return $this->successResponse($countries, Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\InstituteCategory;
use Exception;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

class InstituteCategoryController extends Controller {
    use ApiResponser, ValidtorMapper;
    /**
    * Display a listing of the resource.
    */

    public function index() {
        $data = InstituteCategory::all();
        return $this->successResponse( $data, Response::HTTP_OK );
    }

    /**
    * Show the form for creating a new resource.
    */

    public function create() {
       
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store( Request $request ) {

        $validator = Validator::make( $request->all(), [
            'title_en' => 'required',
            'title_bn' => 'required'
        ] );

        if ( $validator->fails() ) {
            return $this->errorResponse( $this->Validtor( $validator->errors() ), 422 );
        }

        try {
            $data = new InstituteCategory();
            $data->title_en = @$request->title_en;
            $data->title_bn = @$request->title_bn;
            $data->sort_order = @$request->sort_order;
            $data->rec_status = 1;

            $data->save();

            return $this->successResponse( $data, Response::HTTP_OK );
        } catch ( Exception $exc ) {
            return $this->errorResponse( $exc->getMessage(), Response::HTTP_NOT_FOUND );
        }
    }

    /**
    * Display the specified resource.
    */

    public function show( string $id ) {
        //
    }

    /**
    * Show the form for editing the specified resource.
    */

    public function edit(Request $request, $id) {
       return response()->json($id);
    }

    /**
    * Update the specified resource in storage.
    */

    public function update( Request $request, $id) {
        try {
        $data = InstituteCategory::find($id);
        $data->update( [
            'sort_order' => $request->sort_order,
            'title_en' => $request->title_en,
            'title_bn' => $request->title_bn,
            'rec_status' => $request->rec_status,
        ] );
        return $this->successResponse( $data, Response::HTTP_OK );
    } catch ( Exception $exc ) {
        return $this->errorResponse( $exc->getMessage(), Response::HTTP_NOT_FOUND );
    }

    }

    /**
    * Remove the specified resource from storage.
    */

    public function destroy( string $id ) {
        //
    }
}

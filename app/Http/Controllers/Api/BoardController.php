<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\BoardService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Illuminate\Support\Facades\DB;
use App\Models\Board;
use App\Models\BoardToInstituteCategory;
use App\Models\BoardToDistrict;

class BoardController extends Controller
{
    use ApiResponser, ValidtorMapper;
    private $boardService;

    public function __construct(BoardService $boardService)
    {
        $this->boardService = $boardService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $boards = $this->boardService->getAll();
            return $this->successResponse($boards, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("Not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       //return response()->json($request->all());
        $validator = Validator::make( $request->all(), [
            'board_name_bn' => 'required|string',
            'board_name_en' => 'required|string',
            'board_short_name' => 'required|string',
            'board_code' => 'required|string',
            //'institute_categories_id' => 'required|array',
            //'institute_categories_id.*' => 'exists:institute_categories,uid',
            //'district_id' => 'required|array',
            //'district_id.*' => 'exists:districts,uid',
        ] );


        if ( $validator->fails() ) {
            return $this->errorResponse( $this->Validtor( $validator->errors() ), 422 );
        }
        DB::beginTransaction();
        try {

            $board = Board::create([
                'board_name_bn' => $request->board_name_bn,
                'board_name_en' => $request->board_name_en,
                'board_short_name' => $request->board_short_name,
                'board_code' => $request->board_code,
                'sort_order' => $request->sort_order,
            ]);

            foreach ($request->institute_categories_id as $categories_id) {
                $boardInstituteCategory = new BoardToInstituteCategory();
                $boardInstituteCategory->board_uid = $board->uid;
                $boardInstituteCategory->institute_category_uid = $categories_id;
                $boardInstituteCategory->rec_status = 1;
                $boardInstituteCategory->save();

            }

            foreach ($request->district_id as $district) {
                $boardInstituteCategory = new BoardToDistrict();
                $boardInstituteCategory->board_uid = $board->uid;
                $boardInstituteCategory->district_uid = $district;
                $boardInstituteCategory->rec_status = 1;
                $boardInstituteCategory->save();

            }

            DB::commit();
            return response()->json(['message' => 'Data saved successfully'], 200);

        } catch ( Exception $exc ) {
            DB::rollBack();
            return $this->errorResponse( $exc->getMessage(), Response::HTTP_NOT_FOUND );
        }

    }


    public function boardById($id)
    {
        $boardSingle = Board::where('id',$id)->first();
        $boardSingle['institute_categories'] = BoardToInstituteCategory::with('institute_category')->where('board_uid', $boardSingle->uid)->get()->toArray();
        $boardSingle['districts'] = BoardToDistrict::with('district')->where('board_uid', $boardSingle->uid)->get()->toArray();

        return $this->successResponse($boardSingle, Response::HTTP_OK);
    }

    public function edit(Request $request, $id) {
        return response()->json($id);
     }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        DB::beginTransaction();
        try {

            $boardUpdate = Board::find($id);
            $boardUpdate->board_name_bn = $request->board_name_bn;
            $boardUpdate->board_name_en = $request->board_name_en;
            $boardUpdate->board_code = $request->board_code;
            $boardUpdate->sort_order = $request->sort_order;
            $boardUpdate->update();

            $boardUpdate->institute_category()->delete();

            foreach ($request->institute_categories_id as $categories_id) {
                $boardInstituteCategory = new BoardToInstituteCategory();
                $boardInstituteCategory->board_uid = $boardUpdate->uid;
                $boardInstituteCategory->institute_category_uid = $categories_id;
                $boardInstituteCategory->rec_status = 1;
                $boardInstituteCategory->save();

            }

            foreach ($request->district_id as $district) {
                $boardInstituteCategory = new BoardToDistrict();
                $boardInstituteCategory->board_uid = $boardUpdate->uid;
                $boardInstituteCategory->district_uid = $district;
                $boardInstituteCategory->rec_status = 1;
                $boardInstituteCategory->save();

            }

            DB::commit();
            return response()->json(['message' => 'Data saved successfully'], 200);

        } catch ( Exception $exc ) {
            DB::rollBack();
            return $this->errorResponse( $exc->getMessage(), Response::HTTP_NOT_FOUND );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

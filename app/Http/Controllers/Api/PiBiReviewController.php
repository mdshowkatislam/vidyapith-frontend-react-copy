<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PiBiReview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use Exception;

class PiBiReviewController extends Controller
{
    use ApiResponser, ValidtorMapper;

    public function storePiBiReview(Request $request)
    {
        try {
            $exist_review = PiBiReview::where('subject_uid', $request->subject_uid)
                            ->where('teacher_uid', $request->teacher_uid)
                            ->where('class_room_uid', $request->class_room_uid)
                            ->where('is_approved', 0)
                            ->first();
            if($exist_review){
                return $this->errorResponse('এই বিষয়ের জন্য ইতিমধ্যে পুনঃমূল্যায়নের আবেদন করা হয়েছে।', Response::HTTP_NOT_FOUND);
            }
            $data = new PiBiReview();
            $data->subject_uid = @$request->subject_uid;
            $data->teacher_uid = $request->teacher_uid;
            $data->class_room_uid = @$request->class_room_uid;
            $data->remark = @$request->remark;
            $data->session = @$request->session ?? date('Y');
            $data->save();

            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}

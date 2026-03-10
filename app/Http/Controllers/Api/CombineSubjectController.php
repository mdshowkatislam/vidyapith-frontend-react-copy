<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CombineSubject;
use App\Models\Subject;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Exception;
use Illuminate\Http\Response;

class CombineSubjectController extends Controller
{
     use ApiResponser, ValidtorMapper;
    public function index()
    {
        return $this->successResponse(CombineSubject::all(), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'combine_name_en' => 'required|string',
                'subjects' => 'required',
            ]);

            $combine = CombineSubject::create([
                'eiin' => app('sso-auth')->user()->eiin,
                'combine_name_en' => $request->combine_name_en,
                'combine_name_bn' => $request->combine_name_bn,
                'subjects' => $request->subjects,
            ]);

            $subjects = is_array($request->subjects) 
                        ? $request->subjects 
                        : json_decode($request->subjects, true);

            Subject::whereIn('uid', $subjects)->update([
                'is_combine' => 1,
                'combine_subject_id' => $combine->uid,
            ]);

            $message = 'কম্বাইন বিষয় সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($combine, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // $message = 'বিষয় তৈরি করা সম্ভব হয় নি।';
            // return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function update(Request $request)
    {
        try {

            $request->validate([
                'combine_name_en' => 'required|string',
                'subjects' => 'required',
            ]);

            $combine = CombineSubject::findOrFail($request->id);

            Subject::where('combine_subject_id', $combine->uid)->update([
                'is_combine' => null,
                'combine_subject_id' => null,
            ]);

            $subjects = is_array($request->subjects) 
                        ? $request->subjects 
                        : json_decode($request->subjects, true);

            Subject::whereIn('uid', $subjects)->update([
                'is_combine' => 1,
                'combine_subject_id' => $combine->uid,
            ]);

            $combine->update([
                'combine_name_en' => $request->combine_name_en,
                'combine_name_bn' => $request->combine_name_bn,
                'subjects' => $request->subjects,
            ]);

            $message = 'কম্বাইন বিষয় সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($combine, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // $message = 'বিষয় তৈরি করা সম্ভব হয় নি।';
            // return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $combine = CombineSubject::findOrFail($id);
       
        $subjects = is_array($combine->subjects) 
                ? $combine->subjects 
                : json_decode($combine->subjects, true);

        Subject::whereIn('uid', $subjects)->update([
            'is_combine' => null,
            'combine_subject_id' => null,
        ]);
        
        $combine->delete(); 
        return response()->json(['status' => 'success', 'message' => 'কম্বাইন বিষয় এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }
}

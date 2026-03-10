<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ValidtorMapper;
use App\Services\AttendanceConfigureService;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceConfigure\AttendanceConfigureStoreRequest;
use App\Http\Requests\AttendanceConfigure\AttendanceConfigureUpdateRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\AttendanceConfigure;

class AttendanceConfigureController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $attendanceConfigureService;

    public function __construct(AttendanceConfigureService $attendanceConfigureService)
    {
        $this->attendanceConfigureService = $attendanceConfigureService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $attendanceConfigureList = $this->attendanceConfigureService->getByEiinId($eiinId);
            return $this->successResponse($attendanceConfigureList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function getAttConfigData(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'branch_id'  => 'required',
                'shift_id'  => 'required',
                'version_id'  => 'required',
                'class_id'  => 'required',
                'section_id'  => 'required',
            ]);
    
            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            }
           $payload =  [
                'branch_id'     => $request->branch_id,
                'shift_id'      => $request->shift_id,
                'version_id'    => $request->version_id,
                'class_id'      => $request->class_id,
                'section_id'    => $request->section_id,
           ];
            $attendanceConfigure = $this->attendanceConfigureService->alreadyExist($payload);
            return $this->successResponse($attendanceConfigure, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceConfigureStoreRequest $request)
    {
        try {
            $payload = [
                'eiin'              => app('sso-auth')->user()->eiin,
                'branch_id'         => $request->branch_id,
                'shift_id'          => $request->shift_id,
                'version_id'        => $request->version_id,
                'class_id'          => $request->class_id,
                'section_id'        => $request->section_id,
                'mode'              => $request->mode,
                'rules'             => $request->rules,
                'status'            => $request->status ?? 1,
            ];
            
            //create or update
            $attendanceConfigure = $this->attendanceConfigureService->create($payload);

            $message = 'হাজিরা কনফিগার সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($attendanceConfigure, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // $message = 'হাজিরা কনফিগার তৈরি করা সম্ভব হয় নি।';
            // return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($uid)
    {
        try {
            $branch = $this->attendanceConfigureService->getById($uid);
            if($branch){
                return $this->successResponse($branch, Response::HTTP_OK);
            }
            else{
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttendanceConfigureUpdateRequest $request)
    {
        try {
            $exam = [];
            foreach ($request->section_id as $key => $data) {
                $payload = [
                    'uid'               => $request->uid,
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch_id'         => $request->branch_id,
                    'shift_id'          => $request->shift_id,
                    'version_id'        => $request->version_id,
                    'class_id'          => $request->class_id,
                    'section_id'        => $data,
                    'subject_code'      => $request->subject_code,
                    'exam_type'         => $request->exam_type,
                    'exam_no'           => $request->exam_no,
                    'exam_name'         => $request->exam_name,
                    'mcq_mark'          => $request->mcq_mark,
                    'written_mark'      => $request->written_mark,
                    'practical_mark'    => $request->practical_mark,
                    'exam_full_mark'    => $request->exam_full_mark,
                    'exam_date'         => $request->exam_date,
                    'exam_time'         => $request->exam_time,
                    'exam_details_info' => $request->exam_details_info,
                    'status'            => $request->status ?? 1,
                ];

                $exam[] = $this->attendanceConfigureService->update($payload);
            }
            $message = 'হাজিরা কনফিগার সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($exam, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'হাজিরা কনফিগার আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->attendanceConfigureService->delete($id);
        return response()->json(['status' => 'success', 'message' => 'হাজিরা কনফিগার তথ্যটি মুছে ফেলা হয়েছে।']);
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Helper\TeacherInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\Shift\ShiftStoreRequest;
use App\Http\Requests\Shift\ShiftUpdateRequest;
use App\Models\ClassRoom;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\ShiftService;
use Exception;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

  public function index()
{
     try {
            $eiinId     = getAuthInfo()['eiin'];
             $user_type_id = getAuthInfo()['user_type_id'];
            if($user_type_id == 1){
                $teacher = TeacherInfo::teacherInfo();
                if($teacher['teacher_type'] == 'subject_teacher'){
                    $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                    $shift_id = ClassRoom::whereIn('uid', $class_room_uid)->pluck('shift_id')->unique();
                    $shiftList = $this->shiftService->getByShiftId($eiinId, null, $shift_id);
                }else if($teacher['teacher_type'] == 'class_teacher'){
                    $shift_id = $teacher['data']->pluck('shift_id')->unique();
                    $shiftList = $this->shiftService->getByShiftId($eiinId, null, $shift_id);
                }
            }else{
                $shiftList  = $this->shiftService->getByEiinId($eiinId);
            }

            return $this->successResponse($shiftList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
}

    public function store(ShiftStoreRequest $request)
    {
        try {
            $data = [
                'shift_name_en' => $request->shift_name_en,
                'shift_name_bn' => $request->shift_name_bn,
                'description' => $request->description,
                'branch_id' => $request->branch_id,
                'shift_start_time' => $request->shift_start_time,
                'shift_end_time' => $request->shift_end_time,
                'eiin' => $request->eiin,
                'rec_status' => $request->rec_status ?? 1,
            ];
            $shift = $this->shiftService->create($data);

            $message = 'শিফট সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($shift, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'শিফট তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    { 
        
        try {
            $shiftData = $this->shiftService->getById($id);
           
         
            return $this->successResponse($shiftData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function update(ShiftUpdateRequest $request)
    {
        try {
            $eiinId=getAuthInfo()['eiin'];
            $data = [
                'uid' => $request->uid,
                'shift_name_en' => $request->shift_name_en,
                'shift_name_bn' => $request->shift_name_bn,
                'shift_details' => $request->shift_details,
                'branch_id' => $request->branch_id,
                'shift_start_time' => $request->shift_start_time,
                'shift_end_time' => $request->shift_end_time,
                'eiin' => $eiinId,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $shift = $this->shiftService->update($data);

            $message = 'শিফট সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($shift, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'শিফট আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $related_items = [];
        $related_items = $this->shiftService->getRelatedItemsForShift($related_items, $id);


        if (count($related_items['section_items']) > 0) {
            $message['section_exists']  = 'ইতিমধ্যে এই শিফট এর অধীনে ' . en2bn(count($related_items['section_items'])) . ' টি সেকশন এর তথ্য রয়েছে।';
        }
        if (count($related_items['student_items']) > 0) {
            $message['student_exists'] = 'ইতিমধ্যে এই শিফট এর অধীনে ' . en2bn(count($related_items['student_items'])) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।';
        }

        if (count($related_items['subject_teachers']) > 0) {
            $message['subject_teacher_exists'] = 'ইতিমধ্যে এই শিফট এর অধীনে ' . en2bn(count($related_items['subject_teachers'])) . ' টি সেকশন এ বিষয় শিক্ষক এর তথ্য রয়েছে।';
        }
        if ((count($related_items['section_items']) > 0) || (count($related_items['student_items']) > 0) || (count($related_items['subject_teachers']) > 0)) {
            // $message  .= 'অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।';
            return response()->json(['status' => 'error', 'message' => $message]);
        }

        $this->shiftService->delete($id);
        return $this->successMessage('শিফট এর তথ্যটি মুছে ফেলা হয়েছে।');
    }

    public function branchWiseShift(Request $request)
    {
        try {
            $shiftData = $this->shiftService->getByBranch($request->branch_id);
            return $this->successResponse($shiftData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }
}

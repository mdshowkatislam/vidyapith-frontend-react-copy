<?php

namespace App\Http\Controllers\Api;

use App\Helper\TeacherInfo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\BranchStoreRequest;
use App\Http\Requests\Branch\BranchUpdateRequest;
use App\Models\ClassRoom;
use App\Services\BranchService;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;

class BranchController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index()
    {
        try {
            $eiinId = getAuthInfo()['eiin'];
            $userTypeId = getAuthInfo()['user_type_id'];
            // TEMPORARY: For local testing, always use EIIN method to avoid TeacherInfo issues
            if (app()->environment('local')) {
                $branchList = $this->branchService->getByEiinId($eiinId);
            } else {
                // Your original logic for production
                if ($userTypeId == 1) {
                    $teacher = TeacherInfo::teacherInfo();

                    if ($teacher['teacher_type'] == 'subject_teacher') {
                        $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                        $branch_id = ClassRoom::whereIn('uid', $class_room_uid)->pluck('branch_id')->unique();
                        $branchList = $this->branchService->getByBranchId($eiinId, null, $branch_id);
                    } else if ($teacher['teacher_type'] == 'class_teacher') {
                        $branch_id = $teacher['data']->pluck('branch_id')->unique();
                        $branchList = $this->branchService->getByBranchId($eiinId, null, $branch_id);
                    }
                } else {
                    $branchList = $this->branchService->getByEiinId($eiinId);
                }
            }

            return $this->successResponse($branchList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function store(BranchStoreRequest $request)
    {
        try {
            $eiinId = getAuthInfo()['eiin'];
            $data = [
                'branch_name' => $request->branch_name ?? '',
                'branch_name_en' => $request->branch_name_en ?? '',
                'branch_location' => $request->branch_location,
                'head_of_branch_id' => $request->head_of_branch_id,
                // 'eiin' => app('sso-auth')->user()->eiin,
                'eiin' => $eiinId,
                'rec_status' => $request->rec_status ?? 1,
            ];
            $branch = $this->branchService->create($data);

            $message = 'ব্রাঞ্চ সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($branch, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'ব্রাঞ্চ তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function update(BranchUpdateRequest $request)
    {
        try {
            $data = [
                'uid' => $request->uid,
                'branch_name' => $request->branch_name ?? '',
                'branch_name_en' => $request->branch_name_en ?? '',
                'branch_location' => $request->branch_location,
                'head_of_branch_id' => $request->head_of_branch_id,
                // 'eiin' => app('sso-auth')->user()->eiin,
                'eiin' => $request->eiin,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $branch = $this->branchService->update($data);

            $message = 'ব্রাঞ্চ সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($branch, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
            $message = 'ব্রাঞ্চ আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($uid)
    {
        try {
            $branch = $this->branchService->getById($uid);
            if ($branch) {
                return $this->successResponse($branch, Response::HTTP_OK);
            } else {
                return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        $related_items = [];
        $related_items = $this->branchService->getRelatedItemsForBranch($related_items, $id);

        if (count($related_items['version_items']) > 0) {
            $message['version_exists'] = 'ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . en2bn(count($related_items['version_items'])) . ' টি ভার্সন এর তথ্য রয়েছে।';
        }
        if (count($related_items['shift_items']) > 0) {
            $message['shift_exists'] = 'ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . en2bn(count($related_items['shift_items'])) . ' টি শিফট এর তথ্য রয়েছে।';
        }
        if (count($related_items['section_items']) > 0) {
            $message['section_exists'] = 'ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . en2bn(count($related_items['section_items'])) . ' টি সেকশন এর তথ্য রয়েছে।';
        }
        if (count($related_items['student_items']) > 0) {
            $message['student_exists'] = 'ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . en2bn(count($related_items['student_items'])) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।';
        }

        if (count($related_items['subject_teachers']) > 0) {
            $message['subject_teacher_exists'] = 'ইতিমধ্যে এই ব্রাঞ্চ এর অধীনে ' . en2bn(count($related_items['subject_teachers'])) . ' টি সেকশন এ বিষয় শিক্ষক এর তথ্য রয়েছে।';
        }
        if ((count($related_items['version_items']) > 0) || (count($related_items['shift_items']) > 0) || (count($related_items['section_items']) > 0) || (count($related_items['student_items']) > 0) || (count($related_items['subject_teachers']) > 0)) {
            // $message  .= 'অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।';
            return response()->json(['status' => 'error', 'message' => $message]);
        }
        $this->branchService->delete($id);

        return $this->successMessage('ব্রাঞ্চ এর তথ্যটি মুছে ফেলা হয়েছে।');
    }
}

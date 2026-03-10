<?php

namespace App\Http\Controllers\Api;

use App\Helper\TeacherInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Version\VersionStoreRequest;
use App\Http\Requests\Version\VersionUpdateRequest;
use App\Models\ClassRoom;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\VersionService;
use Exception;

class VersionController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $versionService;

    public function __construct(VersionService $versionService)
    {
        $this->versionService = $versionService;
    }

    /**
     * Display a listing of the resource.
     */
 public function index()
{
    try {
        // Use the helper function directly
        $authInfo = getAuthInfo();
        $eiinId = $authInfo['eiin'];
        $userTypeId = $authInfo['user_type_id'];

        if ($userTypeId == 1) {
            $teacher = TeacherInfo::teacherInfo();
            if($teacher['teacher_type'] == 'subject_teacher'){
                $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                $version_id = ClassRoom::whereIn('uid', $class_room_uid)->pluck('version_id')->unique();
                $versionList = $this->versionService->getByVersionId($eiinId, null, $version_id);
            } else if($teacher['teacher_type'] == 'class_teacher'){
                $version_id = $teacher['data']->pluck('version_id')->unique();
                $versionList = $this->versionService->getByVersionId($eiinId, null, $version_id);
            }
        } else {
       
            $versionList = $this->versionService->getByEiinId($eiinId);
        }
        
        return $this->successResponse($versionList, Response::HTTP_OK);
    } catch (Exception $exc) {
        return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
    }
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(VersionStoreRequest $request)
    {
           
        try {
             $authInfo = getAuthInfo();
            $eiinId = $authInfo['eiin'];
          
            $data = [
                'branch_id'         => $request->branch_id,
                'version_name_en'   => $request->version_name_en,
                'version_name_bn'   => $request->version_name_bn,
                'eiin'              => $eiinId,
                'rec_status'        => $request->rec_status ?? 1,
            ];
            $version = $this->versionService->create($data);
            $message = 'ভার্সন সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($version, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'ভার্সন তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $versionData = $this->versionService->getById($id);
            return $this->successResponse($versionData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VersionUpdateRequest $request)
    {
        try {
            $data = [
                'uid'               => $request->uid,
                'branch_id'         => $request->branch_id,
                'version_name_en'   => $request->version_name_en,
                'version_name_bn'   => $request->version_name_bn,
                'eiin'              => getAuthInfo()['eiin'],
                'rec_status'        => $request->rec_status,
            ];

            $version = $this->versionService->update($data);

            $message = 'ভার্সন সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($version, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'ভার্সন আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $related_items = [];
        $related_items = $this->versionService->getRelatedItemsForVersion($related_items, $id);

        if (count($related_items['section_items']) > 0) {
            $message['section_exists']  = 'ইতিমধ্যে এই ভার্সন এর অধীনে ' . en2bn(count($related_items['section_items'])) . ' টি সেকশন এর তথ্য রয়েছে।';
        }
        if (count($related_items['student_items']) > 0) {
            $message['student_exists'] = 'ইতিমধ্যে এই ভার্সন এর অধীনে ' . en2bn(count($related_items['student_items'])) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।';
        }
        if (count($related_items['subject_teachers']) > 0) {
            $message['subject_teacher_exists'] = 'ইতিমধ্যে এই ভার্সন এর অধীনে ' . en2bn(count($related_items['subject_teachers'])) . ' টি সেকশন এ বিষয় শিক্ষক এর তথ্য রয়েছে।';
        }

        if ((count($related_items['section_items']) > 0) || (count($related_items['student_items']) > 0) || (count($related_items['subject_teachers']) > 0)) {
            // $message  .= 'অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।';
            return response()->json(['status' => 'error', 'message' => $message]);
        }
        $this->versionService->delete($id);

        return response()->json(['status' => 'success', 'message' => 'ভার্সন এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }

    public function branchWiseVersion(Request $request)
    {
        try {
            $versionData = $this->versionService->getByBranch($request->branch_id);
            return $this->successResponse($versionData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }
}

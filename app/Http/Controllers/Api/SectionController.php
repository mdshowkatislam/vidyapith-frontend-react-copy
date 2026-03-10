<?php

namespace App\Http\Controllers\Api;

use App\Helper\TeacherInfo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Section\SectionStoreRequest;
use App\Http\Requests\Section\SectionUpdateRequest;
use App\Models\ClassRoom;
use App\Services\SectionService;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class SectionController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $sectionService;

    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    public function index()
    {
        try {
            $authInfo = getAuthInfo();
            $eiinId = $authInfo['eiin'];
            $userTypeId = $authInfo['user_type_id'];

            // user_type_id == 1 mean teacher
            if ($userTypeId == 1) {
                $teacher = TeacherInfo::teacherInfo();
                if ($teacher['teacher_type'] == 'subject_teacher') {
                    $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                    $section_id = ClassRoom::whereIn('uid', $class_room_uid)->pluck('section_id')->unique();
                    $sectionList = $this->sectionService->getBySectionId($eiinId, null, $section_id);
                } else if ($teacher['teacher_type'] == 'class_teacher') {
                    $section_id = $teacher['data']->pluck('section_id')->unique();
                    $sectionList = $this->sectionService->getBySectionId($eiinId, null, $section_id);
                }
            } else {
                $sectionList = $this->sectionService->getByEiinId($eiinId);
            }

            return $this->successResponse($sectionList, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionStoreRequest $request)
    {
        $authInfo = getAuthInfo();
            $eiinId = $authInfo['eiin'];
        try {
            $data = [
                'section_name' => $request->section_name,
                'section_name_en' => $request->section_name_en,
                'section_details' => $request->section_details,
                'branch_id' => $request->branch_id,
                'shift_id' => $request->shift_id,
                'version_id' => $request->version_id,
                'class_id' => $request->class_id,
                'eiin' => $eiinId,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $section = $this->sectionService->create($data);

            $message = 'সেকশন সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($section, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'সেকশন তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $sectionData = $this->sectionService->getById($id);

            return $this->successResponse($sectionData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionUpdateRequest $request)
    {
        try {
            $data = [
                'uid' => $request->uid,
                'section_name' => $request->section_name,
                'section_name_en' => $request->section_name_en,
                'section_details' => $request->section_details,
                'branch_id' => $request->branch_id,
                'shift_id' => $request->shift_id,
                'version_id' => $request->version_id,
                'class_id' => $request->class_id,
                'eiin' => getAuthInfo()['eiin'],
                'rec_status' => $request->rec_status ?? 1,
            ];

            $section = $this->sectionService->update($data);

            $message = 'সেকশন সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($section, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'সেকশন আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $related_items = [];
        $related_items = $this->sectionService->getRelatedItemsForSection($related_items, $id);

        if (count($related_items['student_items']) > 0) {
            $message['student_exists'] = 'ইতিমধ্যে এই সেকশন এর অধীনে ' . en2bn(count($related_items['student_items'])) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।';
        }

        if (count($related_items['subject_teachers']) > 0) {
            $message['subject_teacher_exists'] = 'ইতিমধ্যে এই সেকশন এর অধীনে ' . en2bn(count($related_items['subject_teachers'])) . ' টি সেকশন এ বিষয় শিক্ষক এর তথ্য রয়েছে।';
        }
        if ((count($related_items['student_items']) > 0) || (count($related_items['subject_teachers']) > 0)) {
            // $message  .= 'অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।';
            return response()->json(['status' => 'error', 'message' => $message]);
        }

        $this->sectionService->delete($id);
        return $this->successMessage('সেকশন এর তথ্যটি মুছে ফেলা হয়েছে।');
    }

    public function classWiseSection(Request $request)
    {
        try {
            $sectionData = $this->sectionService->getByClass($request->all());
            return $this->successResponse($sectionData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Helper\TeacherInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Requests\Classroom\ClassroomStoreRequest;
use App\Http\Requests\Classroom\ClassroomUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\ClassRoomService\ClassRoomServiceInterface;
use App\Services\SubjectService;
use Exception;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $classRoomService;
    private $subjectService;

    public function __construct(ClassRoomServiceInterface $classRoomService, SubjectService $subjectService)
    {
        $this->classRoomService = $classRoomService;
        $this->subjectService = $subjectService;
    }

    public function index()
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
               // user_type_id == 1 mean teacher
            if(app('sso-auth')->user()->user_type_id == 1){
                $teacher = TeacherInfo::teacherInfo();
                if($teacher['teacher_type'] == 'subject_teacher'){
                    $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                    return $this->errorResponse('You are not a class teacher', Response::HTTP_FORBIDDEN);
                }else if($teacher['teacher_type'] == 'class_teacher'){
                    $classroomList = $teacher['data'];
                }
            }else{
                $classroomList  = $this->classRoomService->getAllClassRoomsByEiin($eiinId, date('Y'));
            }

            return $this->successResponse($classroomList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $classroomData = $this->classRoomService->getClassRoomById($id);
            return $this->successResponse($classroomData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }

    public function Store(ClassroomStoreRequest $request)
    {
        try {
            $classRoomList = $this->classRoomService->createClassRoom($request);

            $message = 'সেকশন ভিত্তিক বিষয় শিক্ষকের তথ্য যুক্ত করা হয়েছে।';
            return $this->successResponseWithData($classRoomList, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'সেকশন ভিত্তিক বিষয় শিক্ষকের তথ্য যুক্ত করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function update(ClassroomUpdateRequest $request)
    {
        try {
            $classRoomList = $this->classRoomService->updateClassRoom($request->uid, $request);

            $message = 'সেকশন ভিত্তিক বিষয় শিক্ষকের তথ্য আপডেট করা হয়েছে।';
            return $this->successResponseWithData($classRoomList, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'সেকশন ভিত্তিক বিষয় শিক্ষকের তথ্য আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy($id)
    {
        $related_items = [];
        $related_items = $this->classRoomService->getRelatedItemsForClassroom($related_items, $id);

        if (count($related_items['student_items']) > 0) {
            $message['student_exists'] = 'ইতিমধ্যে এই সেকশন এর অধীনে ' . en2bn(count($related_items['student_items'])) . '  জন শিক্ষার্থী এর তথ্য রয়েছে।';
        }
        if (count($related_items['subject_teachers']) > 0) {
            $message['subject_teacher_exists'] = 'ইতিমধ্যে এই সেকশন এর অধীনে ' . en2bn(count($related_items['subject_teachers'])) . ' জন বিষয় শিক্ষক এর তথ্য রয়েছে।';
        }

        if ((count($related_items['student_items']) > 0) || (count($related_items['subject_teachers']) > 0)) {
            // $message  .= 'অনুগ্রহপূর্বক উপরুক্ত তথ্য হালনাগাদ করুন।';
            return response()->json(['status' => 'error', 'message' => $message]);
        }
        $response =  $this->classRoomService->deleteClassRoom($id);

        if ($response) {
            return $this->successMessage('সেকশন ভিত্তিক বিষয় শিক্ষকের তথ্যটি মুছে ফেলা হয়েছে।');
        }
    }

    public function classWiseSubject(Request $request)
    {
        try {
            $subjects = $this->subjectService->getAll($request);
            return $this->successResponse($subjects, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }
}

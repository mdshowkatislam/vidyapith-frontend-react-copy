<?php

namespace App\Http\Controllers\Api;

use App\Helper\TeacherInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Class\ClassStoreRequest;
use App\Http\Requests\Class\ClassUpdateRequest;
use App\Models\ClassRoom;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\ClassService;
use Exception;

class ClassController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $classService;

    public function __construct(ClassService $classService)
    {
        $this->classService = $classService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
           $authInfo=getAuthInfo();
           $eiinId=$authInfo['eiin'];
           $userTypeId=$authInfo['user_type_id'];
             // user_type_id == 1 mean teacher
            if($userTypeId == 1){
                $teacher = TeacherInfo::teacherInfo();
                if($teacher['teacher_type'] == 'subject_teacher'){
                    $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                    $class_id = ClassRoom::whereIn('uid', $class_room_uid)->pluck('class_id')->unique();
                    $classList = $this->classService->getByClassId($eiinId, null, $class_id);
                }else if($teacher['teacher_type'] == 'class_teacher'){
                    $class_id = $teacher['data']->pluck('class_id')->unique();
                    $classList = $this->classService->getByClassId($eiinId, null, $class_id);
                }
            }else{
                $classList = $this->classService->getByEiinId($eiinId);
            }
            return $this->successResponse($classList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassStoreRequest $request)
    {
        try {
            $data = [
                'class_name_bn'     => $request->class_name_bn,
                'class_name_en'     => $request->class_name_en,
                'eiin'              => app('sso-auth')->user()->eiin,
                'rec_status'        => $request->rec_status ?? 1,
            ];
            $class = $this->classService->create($data);
            $message = 'ক্লাস সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($class, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'ক্লাস তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $classData = $this->classService->getById($id);
            return $this->successResponse($classData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassUpdateRequest $request)
    {
        try {
            $data = [
                'uid'               => $request->uid,
                'class_name_bn'     => $request->class_name_bn,
                'class_name_en'     => $request->class_name_en,
                'eiin'              => app('sso-auth')->user()->eiin,
                'rec_status'        => $request->rec_status ?? 1,
            ];

            $class = $this->classService->update($data);

            $message = 'ক্লাস সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($class, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'ক্লাস আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->classService->delete($id);

        return response()->json(['status' => 'success', 'message' => 'ক্লাস এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }
}

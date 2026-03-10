<?php

namespace App\Http\Controllers\Api;

use App\Helper\TeacherInfo;
use Exception;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ValidtorMapper;
use App\Models\ClassWiseSubject;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\ClassWiseSubjectService;
use App\Services\SectionService;
use App\Http\Requests\ClassWiseSubject\ClassWiseSubjectStoreRequest;
use App\Http\Requests\ClassWiseSubject\ClassWiseSubjectUpdateRequest;
use App\Models\ClassRoom;
use App\Models\SubjectTeacher;

class ClassWiseSubjectController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $classWiseSubjectService;
    private $SectionService;

    public function __construct(ClassWiseSubjectService $classWiseSubjectService, SectionService $sectionService)
    {
        $this->classWiseSubjectService = $classWiseSubjectService;
        $this->SectionService = $sectionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        try {
            $eiinId = app('sso-auth')->user()->eiin;
              // user_type_id == 1 mean teacher
            if(app('sso-auth')->user()->user_type_id == 1){
                $teacher = TeacherInfo::teacherInfo();
                if($teacher['teacher_type'] == 'subject_teacher'){
                     \Log::info("WWW1");
                    $class_room_uid = $teacher['data']->pluck('class_room_uid')->unique();
                    $class_id = ClassRoom::whereIn('uid', $class_room_uid)->pluck('class_id')->unique();
                    $subject_id = $teacher['data']->pluck('subject_uid')->unique();
                    $classWiseSubjectList = $this->classWiseSubjectService->getBySubjectId($eiinId, null, $class_id, $subject_id);

                    // $classWiseSection= $this->SectionService->getByClass($eiinId, null, $class_id);
                }else if($teacher['teacher_type'] == 'class_teacher'){
                    \Log::info("WWW2");
                    $uid = $teacher['data']->pluck('uid')->unique();
                    $class_id = $teacher['data']->pluck('class_id')->unique();
                    $subject_id = SubjectTeacher::whereIn('class_room_uid', $uid)->pluck('subject_id')->unique();
                    $classWiseSubjectList = $this->classWiseSubjectService->getBySubjectId($eiinId, null,$class_id, $subject_id);
                }
            }else{
                 \Log::info("WWW3");
                $classWiseSubjectList = $this->classWiseSubjectService->getByEiinId($eiinId);
            }
            return $this->successResponse($classWiseSubjectList, Response::HTTP_OK);
        } catch (Exception $exc) {
             \Log::info("WWW4");
            
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassWiseSubjectStoreRequest $request)
    {
        \Log::info("BBB");
        \Log::info($request->all());
        // exit();
        try {
            DB::beginTransaction();
            $subject = [];
            foreach ($request->subject_id as $key => $data) {
                $payload = [
                    'class_id'   => $request->class_id,
                    'section_id'   => $request->section_id,
                    'subject_id' => $data,
                    'session_id' => $request->session_id,
                    'eiin'       => app('sso-auth')->user()->eiin,
                    'rec_status' => $request->rec_status ?? 1,
                ];

                $subject[] = $this->classWiseSubjectService->create($payload);
            }
            DB::commit();

            $message = 'বিষয় সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($subject, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'বিষয় তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $classWiseSubjectData = $this->classWiseSubjectService->getById($id);
            return $this->successResponse($classWiseSubjectData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassWiseSubjectUpdateRequest $request)
    {
        
        \Log::info("BBB2");
        \Log::info($request->all());
        try {

            ClassWiseSubject::where('class_id', $request->class_id)->delete();

            DB::beginTransaction();
            $subject = [];
            foreach ($request->subject_id as $key => $data) {
                $payload = [
                    'class_id'   => $request->class_id,
                    'subject_id' => $data,
                    'section_id'   => $request->section_id,
                    'session_id' => $request->session_id,
                    'eiin'       => app('sso-auth')->user()->eiin,
                    'rec_status' => $request->rec_status ?? 1,
                ];

                $subject[] = $this->classWiseSubjectService->create($payload);
            }
            DB::commit();

            $message = 'বিষয় সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($subject, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            
        \Log::info("BBB3");
        \Log::info($e->getMessage());
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'বিষয় আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($class_id, $session_id)
    {
        $this->classWiseSubjectService->delete($class_id, $session_id);

        return response()->json(['status' => 'success', 'message' => 'বিষয় এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }
}

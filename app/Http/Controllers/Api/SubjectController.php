<?php

namespace App\Http\Controllers\Api;

use App\Helper\TeacherInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Subject\SubjectStoreRequest;
use App\Http\Requests\Subject\SubjectUpdateRequest;
use App\Models\ClassRoom;
use App\Models\SubjectTeacher;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\SubjectService;
use Exception;

class SubjectController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eiinId = getAuthInfo()['eiin'];
              $user_type_id = getAuthInfo()['user_type_id'];
            if($user_type_id == 1){
                $teacher = TeacherInfo::teacherInfo();
                if($teacher['teacher_type'] == 'subject_teacher'){
                    $subject_id = $teacher['data']->pluck('subject_uid')->unique();
                    $subjectList = $this->subjectService->getBySubjectId($eiinId, null, $subject_id);
                }else if($teacher['teacher_type'] == 'class_teacher'){
                    $uid = $teacher['data']->pluck('uid')->unique();
                    $subject_id = SubjectTeacher::whereIn('class_room_uid', $uid)->pluck('subject_id')->unique();
                    $subjectList = $this->subjectService->getBySubjectId($eiinId, null, $subject_id);
                }
            }else{
                $subjectList = $this->subjectService->getByEiinId($eiinId);
            }
            return $this->successResponse($subjectList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectStoreRequest $request)
    {
        try {
          $existingSubject = $this->subjectService->getByCondition([
                ['subject_code', '=', $request->subject_code],
                function ($query) use ($request) {
                    $query->where('subject_name_bn', $request->subject_name_bn)
                          ->orWhere('subject_name_en', $request->subject_name_en);
                }
            ]);

            if ($existingSubject) {
                return $this->errorResponse('একই নাম এবং কোড সহ একটি বিষয় ইতিমধ্যে বিদ্যমান।', Response::HTTP_CONFLICT);
            }

            $data = [
                'subject_name_bn'   => $request->subject_name_bn,
                'subject_name_en'   => $request->subject_name_en,
                'subject_code'      => $request->subject_code,
                'session'           => $request->session,
                'eiin'              => app('sso-auth')->user()->eiin,
                'rec_status'        => $request->rec_status ?? 1,
            ];
            $subject = $this->subjectService->create($data);
            $message = 'বিষয় সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($subject, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // $message = 'বিষয় তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $subjectData = $this->subjectService->getById($id);
            return $this->successResponse($subjectData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectUpdateRequest $request)
    {
        try {
            $existingSubject = $this->subjectService->getByCondition([
                ['subject_code', '=', $request->subject_code],
                function ($query) use ($request) {
                    $query->where('subject_name_bn', $request->subject_name_bn)
                        ->orWhere('subject_name_en', $request->subject_name_en);
                },
                ['uid', '!=', $request->uid]
            ]);

            if ($existingSubject) {
                return $this->errorResponse('একই নাম এবং কোড সহ একটি বিষয় ইতিমধ্যে বিদ্যমান।', Response::HTTP_CONFLICT);
            }

            $data = [
                'uid'               => $request->uid,
                'subject_name_bn'   => $request->subject_name_bn,
                'subject_name_en'   => $request->subject_name_en,
                'subject_code'      => $request->subject_code,
                'session'           => $request->session,
                'eiin'              => app('sso-auth')->user()->eiin,
                'rec_status'        => $request->rec_status ?? 1,
            ];

            $subject = $this->subjectService->update($data);

            $message = 'বিষয় সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($subject, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'বিষয় আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->subjectService->delete($id);

        return response()->json(['status' => 'success', 'message' => 'বিষয় এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }
}

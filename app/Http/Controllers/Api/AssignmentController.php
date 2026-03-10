<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Assignment\AssignmentStoreRequest;
use App\Http\Requests\Assignment\AssignmentUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\AssignmentService;
use Exception;

class AssignmentController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            $classTestList = $this->assignmentService->getByEiinId($eiinId);
            return $this->successResponse($classTestList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssignmentStoreRequest $request)
    {
        try {
            if(!array_key_exists('section_id', $request->all()) || count($request->section_id) == 0) return $this->errorResponse('Select section id please', Response::HTTP_NOT_ACCEPTABLE);

            $assignment = [];
            foreach ($request->section_id as $key => $data) {
                $payload = [
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch_id'         => $request->branch_id,
                    'shift_id'          => $request->shift_id,
                    'version_id'        => $request->version_id,
                    'class_id'          => $request->class_id,
                    'section_id'        => $data,
                    'assignment_no'     => $request->assignment_no,
                    'assignment_name'   => $request->assignment_name,
                    'subject_code'      => $request->subject_code,
                    'mcq_mark'          => $request->mcq_mark,
                    'written_mark'      => $request->written_mark,
                    'practical_mark'    => $request->practical_mark,
                    'assignment_full_mark'       => $request->assignment_full_mark,
                    'assignment_submission_date' => $request->assignment_submission_date,
                    'assignment_details_info'    => $request->assignment_details_info,
                    'status'                     => $request->status ?? 1,
                ];

                $alreadyExist = $this->assignmentService->alreadyExist($payload);
                if(count($alreadyExist)>0) return $this->errorResponse('বিষয়বস্তুর নাম ইতিমধ্যে বিদ্যমান', Response::HTTP_FOUND);

                $assignment[] = $this->assignmentService->create($payload);
            }

            $message = 'এসাইনমেন্ট সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($assignment, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'এসাইনমেন্ট তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($uid)
    {
        try {
            $branch = $this->assignmentService->getById($uid);
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
    public function update(AssignmentUpdateRequest $request)
    {
        try {
            $assignment = [];
            foreach ($request->section_id as $key => $data) {
                $payload = [
                    'uid'               => $request->uid,
                    'eiin'              => app('sso-auth')->user()->eiin,
                    'branch_id'         => $request->branch_id,
                    'shift_id'          => $request->shift_id,
                    'version_id'        => $request->version_id,
                    'class_id'          => $request->class_id,
                    'section_id'        => $data,
                    'assignment_no'     => $request->assignment_no,
                    'assignment_name'   => $request->assignment_name,
                    'subject_code'      => $request->subject_code,
                    'mcq_mark'          => $request->mcq_mark,
                    'written_mark'      => $request->written_mark,
                    'practical_mark'    => $request->practical_mark,
                    'assignment_full_mark'       => $request->assignment_full_mark,
                    'assignment_submission_date' => $request->assignment_submission_date,
                    'assignment_details_info'    => $request->assignment_details_info,
                    'status'                     => $request->status ?? 1,
                ];
                $assignment[] = $this->assignmentService->update($payload);
            }
            $message = 'এসাইনমেন্ট সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($assignment, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'এসাইনমেন্ট আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->assignmentService->delete($id);
        return response()->json(['status' => 'success', 'message' => 'এসাইনমেন্ট তথ্যটি মুছে ফেলা হয়েছে।']);
    }
}

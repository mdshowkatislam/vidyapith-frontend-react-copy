<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiEvaluation;
use App\Models\PiEvaluation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Teacher;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;

use App\Services\SubjectService;
use App\Services\TeacherService;
use App\Services\UserService;
use App\Services\Api\AuthService;
use App\Services\SubjectTeacherService\SubjectTeacherService;

use Exception;


class TeacherController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $teacherService;
    private $subjectTeacherService;
    private $subjectService;
    private $userService;
    private $authService;

    public function __construct(TeacherService $teacherService, SubjectTeacherService $subjectTeacherService, SubjectService $subjectService, UserService $userService, AuthService $authService)
    {
        $this->teacherService = $teacherService;
        $this->subjectTeacherService = $subjectTeacherService;
        $this->subjectService = $subjectService;
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function index(Request $request)
    {
        try {
            $eiinId = app('sso-auth')->user()->eiin;
            // $teachers = $this->teacherService->list();
            $teachers = $this->teacherService->getByEiinId($eiinId, null, 1);
            return $this->successResponse($teachers, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("Not found", Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pdsid' => 'nullable',
            'name_en' => 'nullable|max:150',
            'name_bn' => 'nullable|max:150',
            'email' => 'nullable',
            'mobile_no' => 'nullable',
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'last_working_date' => 'nullable|date',
            'mpo_code' => 'nullable|numeric',
            'nid' => 'nullable|string|unique:teachers',
            'ismpo' => 'nullable|numeric',
            'teacher_type' => 'nullable',
            'access_type' => 'nullable',
            'isactive' => 'nullable|numeric',
            'designation' => 'nullable',
            'division_id' => 'nullable|numeric',
            'district_id' => 'nullable|numeric',
            'upazilla_id' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($this->Validtor($validator->errors()), 422);
        }

        try {
            if ($request->access_type) {
                $role_list = $request->access_type;
                $role_list = implode(',', $role_list);
                $request['role'] = $role_list;
            }

            $authRequest = $this->authService->teacher($request->all(), @$request->eiin);

            if (@$authRequest->status == true) {
                $authData = (object) $authRequest->data;
                $request['caid'] = $authData->caid;
                $request['eiin'] = @auth()->user()->eiin;
                // $request['eiin'] = $authData->eiin;
                $teacher = $this->teacherService->create($request->all());
                $this->userService->create($request->all());

                return $this->successResponse($teacher, Response::HTTP_OK);
            } else {
                return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
            }

            // $teacher = $this->teacherService->create($request->all());
            // return $this->successResponse($teacher, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'pdsid' => 'nullable',
            'name_en' => 'nullable|max:150',
            'name_bn' => 'nullable|max:150',
            'email' => 'nullable',
            'mobile_no' => 'nullable',
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'last_working_date' => 'nullable|date',
            'mpo_code' => 'nullable|numeric',
            'nid' => 'nullable',
            'ismpo' => 'nullable|numeric',
            'teacher_type' => 'nullable',
            'access_type' => 'nullable',
            'isactive' => 'nullable|numeric',
            'designation' => 'nullable',
            'division_id' => 'nullable|numeric',
            'district_id' => 'nullable|numeric',
            'upazilla_id' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {

            DB::beginTransaction();

            $findByTrash = $this->teacherService->getWithTrashedById($id);
            if ($findByTrash) {
                $requestData = $request->all();

                if ($request->access_type) {
                    $role_list = $request->access_type;
                    $role_list = implode(',', $role_list);
                    $requestData['role'] = $role_list;
                }
                if ($request->access_type) {
                    $is_disabled = $request->is_disabled; //not sent message , msg send if null
                } else {
                    $is_disabled = null;
                }
                $requestData['caid'] = @$findByTrash->caid;
                $requestData['eiin'] = @$findByTrash->eiin;

                if (!empty($findByTrash->deleted_at)) {
                    $this->teacherService->update($requestData, $findByTrash->uid, true);
                } else {
                    $this->teacherService->update($requestData, $findByTrash->uid);
                }

                $this->userService->update($findByTrash->caid, $requestData);
                $this->authService->accountUpdate($requestData, $findByTrash->caid, auth()->user()->eiin, $is_disabled, 1);

                DB::commit();

                return $this->successResponse("Successfully updated", Response::HTTP_OK);
            } else {
                $requestData = $request->all();

                if ($request->access_type) {
                    $role_list = $request->access_type;
                    $role_list = implode(',', $role_list);
                    $requestData['role'] = $role_list;
                }
                $myteacherJson = $requestData['myteacher'];
                $myteacher = json_decode($myteacherJson);
                $mergedData = array_merge($requestData, (array) $myteacher);
                $authRequest = $this->authService->teacher($mergedData, $mergedData['eiin']);

                if ($authRequest->data) {
                    $authData = (object) $authRequest->data;
                    $mergedData['caid'] = @$authData->caid;
                    $mergedData['eiin'] = @$authData->eiin;

                    $teacherExists = $this->teacherService->getByCaId(@$authData->caid);
                    if ($teacherExists) {
                        $this->teacherService->update($mergedData, $teacherExists->uid);
                        // dd(@$authData->caid);
                        $this->userService->update($authData->caid, $mergedData);
                    } else {
                        $this->teacherService->create($mergedData);
                        $this->userService->create($mergedData);
                    }

                    DB::commit();

                    return $this->successResponse("Successfully updated", Response::HTTP_OK);
                } else {
                    DB::rollBack();
                    return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
        }
    }


    public function OwnSubject(Request $request)
    {
        $data['user'] = app('sso-auth')->user();
        $teacher = Teacher::on('db_read')->where('caid', $data['user']->caid)->first();
        $data['subjects'] = $this->subjectTeacherService->getSubjectByTeacherId($teacher->uid)->toArray();
        foreach ($data['subjects'] as $key => $item) {
            $data['subjects'][$key]['competence'] = $this->subjectService->getCompetenceBySubject(['subject_id' => $item['subject_id']]);
            $data['subjects'][$key]['oviggota'] = $this->subjectService->getOviggotaBySubject(['subject_uid' => $item['subject_id']]);
            $data['subjects'][$key]['pi_selection'] = $this->subjectService->getPiSelectionBySubject(['session' => 2023, 'subject_uid' => $item['subject_id']]);
        }
        // foreach($data['subjects'] as $key => $item){
        //     $data['subjects'][$key]['chapters'] = $this->subjectService->getChapter(['subject_id'=>$item['subject_id']]);
        //     foreach($data['subjects'][$key]['chapters'] as $i => $list){
        //         $data['subjects'][$key]['chapters'][$i]['competence'] = $this->subjectService->getCompetenceBychapter(['chapter_id'=>$list['uid']]);
        //     }
        // }
        $data['bis'] = $this->subjectService->getAllBis();
        $data['assessments'] = $this->subjectService->getAllAssessments();
        $data['pi_attribute_weight'] = $this->subjectService->getPiWeight();

        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function PiBiEvaluationList(Request $request)
    {
        $data['user'] = app('sso-auth')->user();
        $teacher = Teacher::on('db_read')->where('caid', $data['user']->caid)->first();

        if ($request->submit_status && $request->student_uid) {
            $data['pi_evaluation_list'] = PiEvaluation::on('db_evaluation')->where('teacher_uid', $teacher->uid)
                ->where('submit_status', $request->submit_status)
                ->where('student_uid', $request->student_uid)
                ->get();
            $data['bi_evaluation_list'] = BiEvaluation::on('db_evaluation')->where('teacher_uid', $teacher->uid)
                ->where('submit_status', $request->submit_status)
                ->where('student_uid', $request->student_uid)
                ->get();
        } elseif ($request->submit_status) {
            $data['pi_evaluation_list'] = PiEvaluation::on('db_evaluation')->where('teacher_uid', $teacher->uid)
                ->where('submit_status', $request->submit_status)
                ->get();
            $data['bi_evaluation_list'] = BiEvaluation::on('db_evaluation')->where('teacher_uid', $teacher->uid)
                ->where('submit_status', $request->submit_status)
                ->get();
        } elseif ($request->student_uid) {
            $data['pi_evaluation_list'] = PiEvaluation::on('db_evaluation')->where('teacher_uid', $teacher->uid)
                ->where('student_uid', $request->student_uid)
                ->get();
            $data['bi_evaluation_list'] = BiEvaluation::on('db_evaluation')->where('teacher_uid', $teacher->uid)
                ->where('student_uid', $request->student_uid)
                ->get();
        } else {
            $data['pi_evaluation_list'] = PiEvaluation::on('db_evaluation')->where('teacher_uid', $teacher->uid)->get();
            $data['bi_evaluation_list'] = BiEvaluation::on('db_evaluation')->where('teacher_uid', $teacher->uid)->get();
        }

        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function teacherList(Request $request)
    {

        try {
            $teachers = $this->teacherService->teachersList($request);

            return $this->successResponse($teachers, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("Something went wrong!!", Response::HTTP_NOT_FOUND);
        }
    }

    public function getById($id)
    {
        try {
            $teacher = $this->teacherService->getById($id);
            if($teacher){
                return $this->successResponse($teacher, Response::HTTP_OK);
            }
            else{
                return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }


    public function searchTeacherByPDSID(Request $request)
    {
        try {
            $teacher = $this->teacherService->searchTeacherByPDSID($request);

            $teacher_name_en = @$teacher->name_en ?? @$teacher->fullname ?? @$teacher->employee_name;
            $teacher_name_bn = @$teacher->name_bn ?? @$teacher->fullname_bn ?? @$teacher->employee_name_bn;

            $data = [
                'teacher_name_en'   => $teacher_name_en,
                'teacher_name_bn'   => $teacher_name_bn,
                'findFrom'          => $teacher->findFrom ?? '',
                'deleted_at'        => $teacher->deleted_at ?? '',
                'institute_name'    => $teacher->institute_name ?? '',
                'teacher_data'      => json_encode($teacher) ?? '',
            ];

            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {

            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }


    public function upazillaTotalTeachers(Request $request)
    {
        try {
            $totalTeacher = $this->teacherService->upazillaTotalTeachers($request);
            return $this->successResponse($totalTeacher, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    public function foreignTotalTeachers()
    {
        try {
            $totalTeacher = $this->teacherService->foreignTotalTeachers();
            return $this->successResponse($totalTeacher, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}

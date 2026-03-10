<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiEvaluation;
use App\Models\ClassRoom;
use App\Models\Designation;
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


class TeacherV2Controller extends Controller
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
            $eiinId = auth()->user()->eiin;
            // $teachers = $this->teacherService->list();
            $teachers = $this->teacherService->getByEiinId($eiinId);
            return $this->successResponse($teachers, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
        }
    }
    public function designationList(Request $request)
    {
        try {
            $designations = Designation::on('db_read')->select('uid', 'designation_name')->get();
            return $this->successResponse($designations, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100',
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
            'nid' => 'nullable',
            'division_id' => 'nullable|numeric',
            'district_id' => 'nullable|numeric',
            'upazilla_id' => 'nullable|numeric',
            'image' => 'nullable|mimes:jpeg,png,jpg|max:100',
            'signature' => 'nullable|mimes:jpeg,png,jpg|max:60',
        ]);

        try {

            DB::beginTransaction();
            $teacher_data = Teacher::where('caid', $id)->orwhere('pdsid', $id)->orwhere('eiin', $id)->first();

            if ($teacher_data) {
                $requestData = $request->all();
                $requestData['caid'] = @$teacher_data->caid;
                $requestData['eiin'] = @$teacher_data->eiin;

                if (@$requestData['name_en']) {
                    $teacher_data->name_en = $requestData['name_en'];
                }
                if (@$requestData['name_bn']) {
                    $teacher_data->name_bn = $requestData['name_bn'];
                }
                if (@$requestData['email']) {
                    $teacher_data->email = $requestData['email'];
                }
                if (@$requestData['mobile_no']) {
                    $teacher_data->mobile_no = $requestData['mobile_no'];
                }
                if (@$requestData['gender']) {
                    $teacher_data->gender = $requestData['gender'];
                }
                if (@$requestData['date_of_birth']) {
                    $teacher_data->date_of_birth = $requestData['date_of_birth'];
                }
                if (@$requestData['division_id']) {
                    $teacher_data->division_id = $requestData['division_id'];
                }
                if (@$requestData['district_id']) {
                    $teacher_data->district_id = $requestData['district_id'];
                }
                if (@$requestData['upazilla_id']) {
                    $teacher_data->upazilla_id = $requestData['upazilla_id'];
                }
                if (@$requestData['nid']) {
                    $teacher_data->nid = $requestData['nid'];
                }
                if (@$requestData['blood_group']) {
                    $teacher_data->blood_group = $requestData['blood_group'];
                }
                if (@$requestData['emergency_contact']) {
                    $teacher_data->emergency_contact = $requestData['emergency_contact'];
                }
                if (@$requestData['joining_date']) {
                    $teacher_data->joining_date = $requestData['joining_date'];
                }
                if (@$requestData['designation_id']) {
                    $teacher_data->designation_id = $requestData['designation_id'];
                    $designation = Designation::where('uid', @$requestData['designation'])->first();
                    $teacher_data->designation = @$designation->designation_name;
                }

                if ($image = $request->file('image')) {
                    $directory = 'teacher/img';
                    $filename =  $teacher_data->caid . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $filePath = $image->storeAs(
                        $directory,
                        $filename,
                        's3'
                    );
                    $teacher_data['image'] = $filePath;
                }

                if ($image = $request->file('signature')) {
                    $directory = 'teacher/img/signature';
                    $filename =  $teacher_data->caid . '_sign_' . time() . '.' . $image->getClientOriginalExtension();
                    $filePath = $image->storeAs(
                        $directory,
                        $filename,
                        's3'
                    );
                    $teacher_data['signature'] = $filePath;
                }

                $teacher_data->save();
                $this->userService->update($teacher_data->caid, $requestData);
                $this->authService->accountUpdate($requestData, $teacher_data->caid, $teacher_data->eiin, 1, 1);

                DB::commit();

                return $this->successResponse($teacher_data, Response::HTTP_OK);
            } else {
                return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
        }
    }

    public function classRoomInfo(Request $request)
    {
        $user = app('sso-auth')->user();
        $teacher = Teacher::on('db_read')->where('caid', $user->caid)->first();
        $data['subjects'] = $this->subjectTeacherService->getSubjectByTeacherId($teacher->uid, date('Y'))->toArray();

        // foreach ($data['subjects'] as $key => $item) {
        //     $data['subjects'][$key]['competence'] = $this->subjectService->getCompetenceBySubject(['subject_id' => $item['subject_id']]);
        //     $data['subjects'][$key]['oviggota'] = $this->subjectService->getOviggotaBySubject(['subject_uid' => $item['subject_id']]);
        //     $data['subjects'][$key]['pi_selection'] = $this->subjectService->getPiSelectionBySubject(['session' => 2023, 'subject_uid' => $item['subject_id']]);
        // }
        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function classRoomInformation(Request $request)
    {
        $user = app('sso-auth')->user();
        $teacher = Teacher::on('db_read')->where('caid', $user->caid)->first();
        $data['subjects'] = $this->subjectTeacherService->getSubjectByTeacherUid($teacher->uid, date('Y'))->toArray();

        // foreach ($data['subjects'] as $key => $item) {
        //     $data['subjects'][$key]['competence'] = $this->subjectService->getCompetenceBySubject(['subject_id' => $item['subject_id']]);
        //     $data['subjects'][$key]['oviggota'] = $this->subjectService->getOviggotaBySubject(['subject_uid' => $item['subject_id']]);
        //     $data['subjects'][$key]['pi_selection'] = $this->subjectService->getPiSelectionBySubject(['session' => 2023, 'subject_uid' => $item['subject_id']]);
        // }
        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function ownSubject(Request $request)
    {
        $user = app('sso-auth')->user();
        $teacher = Teacher::on('db_read')->where('caid', $user->caid)->first();

        $data['subjects'] = $this->subjectTeacherService->getOwnSubjectByTeacherId($teacher->uid, date('Y'))->toArray();

        foreach ($data['subjects'] as $key => $item) {
            // $data['subjects'][$key]['subject_info'] = $this->subjectService->getSubjectInfo($item['subject_id']);
            $data['subjects'][$key]['competence'] = $this->subjectService->getCompetenceBySubject(['subject_id' => $item['subject_id']]);
            $data['subjects'][$key]['oviggota'] = $this->subjectService->getOviggotaBySubject(['subject_uid' => $item['subject_id']]);
            $data['subjects'][$key]['pi_selection'] = $this->subjectService->getPiSelectionBySubject(['session' => date('Y'), 'subject_uid' => $item['subject_id']]);
        }
        // dd($data['subjects']);
        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function classStudents(Request $request)
    {
        $user = app('sso-auth')->user();
        $teacher = Teacher::on('db_read')->select('uid')->where('caid', $user->caid)->first();

        $data['students'] = ClassRoom::on('db_read')->with('students')->where('class_teacher_id', $teacher->uid)->get();

        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function getBi(Request $request)
    {
        $data['bis'] = $this->subjectService->getAllBis();
        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
    public function getCommonInfo(Request $request)
    {
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
        $user = app('sso-auth')->user();
        $teacher = Teacher::on('db_read')->where('caid', $user->caid)->first();

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
}

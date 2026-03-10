<?php

namespace App\Http\Controllers\Api;

use App\Helper\SmsService;
use App\Helper\TeacherInfo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\TeacherStoreRequest;
use App\Http\Requests\Teacher\TeacherUpdateRequest;
use App\Models\BiEvaluation;
use App\Models\Branch;
use App\Models\ClassRoom;
use App\Models\Designation;
use App\Models\Institute;
use App\Models\PiEvaluation;
use App\Models\Shift;
use App\Models\SubjectTeacher;
use App\Models\Teacher;
use App\Models\TeacherSms;
use App\Models\Version;
use App\Services\Api\AuthService;
use App\Services\Api\SmsLogService;
use App\Services\SubjectTeacherService\SubjectTeacherService;
use App\Services\SubjectService;
use App\Services\TeacherService;
use App\Services\UserService;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class TeacherV3Controller extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $teacherService;
    private $subjectTeacherService;
    private $subjectService;
    private $userService;
    private $authService;
    private $smsLogService;

    public function __construct(TeacherService $teacherService, SubjectTeacherService $subjectTeacherService, SubjectService $subjectService, UserService $userService, AuthService $authService, SmsLogService $smsLogService)
    {
        $this->teacherService = $teacherService;
        $this->subjectTeacherService = $subjectTeacherService;
        $this->subjectService = $subjectService;
        $this->userService = $userService;
        $this->authService = $authService;
        $this->smsLogService = $smsLogService;
    }

    public function index(Request $request)
    {
        // Log::info('hi01');
        
        try {
            $eiinId = getAuthInfo()['eiin'];
           

            $user = app('sso-auth')->user();
            // Check if user is not null and user_type_id is 1
            if ($user && $user->user_type_id == 1) {
                $teacher = TeacherInfo::teacherInfo();
                if ($teacher['teacher_type'] == 'subject_teacher') {
                    $teacher_id = $teacher['data']->pluck('teacher_uid')->unique();
                    $teachers = $this->teacherService->getByIdWithPaginate($teacher_id);
                } else if ($teacher['teacher_type'] == 'class_teacher') {
                    $uid = $teacher['data']->pluck('uid')->unique();
                    $teacher_id = SubjectTeacher::whereIn('class_room_uid', $uid)->pluck('teacher_uid')->unique();
                    $teachers = $this->teacherService->getByClass($teacher_id);
                }
            } else {
                $teachers = $this->teacherService->getByEiinId($eiinId, null, null, $request->search);
            }

            return $this->successResponse($teachers, Response::HTTP_OK);
        } catch (Exception $exc) {
            // Log the exception message for debugging
            Log::error($exc->getMessage());
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function getById($id)
    {
        try {
            $teacher = $this->teacherService->getById($id);
            if ($teacher) {
                return $this->successResponse($teacher, Response::HTTP_OK);
            } else {
                return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function getByEmpId($profileId)
    {
        try {
            $teacher = $this->teacherService->getByEmpId($profileId);
            if ($teacher) {
                return $this->successResponse($teacher, Response::HTTP_OK);
            } else {
                return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function getByEmpIdShort($profileId)
    {
        try {
            $teacher = $this->teacherService->getByEmpIdShort($profileId);
            if ($teacher) {
                return $this->successResponse($teacher, Response::HTTP_OK);
            } else {
                return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function getByCaId(Request $request)
    {
        try {
            if ($request->caid) {
                $caid = $request->caid;
            } else {
                $caid = app('sso-auth')->user()->caid;
            }

            $teacher = $this->teacherService->getByCaId($caid);
            if ($teacher) {
                return $this->successResponse($teacher, Response::HTTP_OK);
            } else {
                return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function teacherExistsCheckByPdsidOrIndex(Request $request)
    {
        try {
            $teacher_exists = $this->teacherService->getByPdsOrIndex($request->pds_index);
            if ($teacher_exists) {
                $message = $teacher_exists->name_en . ' ইতিমধ্যে ' . @$teacher_exists->institute->institute_name . ' (Eiin - ' . @$teacher_exists->institute->eiin . ') এ যুক্ত রয়েছে! নতুন স্কুলে যুক্ত করার জন্য তাকে পূর্বের স্কুল থেকে অপসারণ করতে হবে।';
                return $this->errorResponse($message, Response::HTTP_OK);
            }
            $teacher_found = $this->teacherService->getEmisTeacherByPdsID($request->pds_index);
            if (!$teacher_found) {
                $teacher_found = $this->teacherService->getBanbiesTeacherByIndexNo($request->pds_index);
            }
            return $this->successResponse($teacher_found, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function designationList(Request $request)
    {
        try {
            $designations = Designation::on('db_read')->select('uid', 'designation_name')->get();
            return $this->successResponse($designations, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
    }

    public function store(TeacherStoreRequest $request)
    { 
        try {
            $eiinId = getAuthInfo()['eiin'];
            // $eiinId = app('sso-auth')->user()->eiin;
            DB::beginTransaction();
            $authRequest = $this->authService->teacher($request->all(), $eiinId);
            if (@$authRequest->status == true) {
                $authData = (object) $authRequest->data;
                $request['caid'] = $authData->caid;
                $request['eiin'] = $authData->eiin;
                $findByTrash = $this->teacherService->getWithTrashedById($authData->caid);

                if ($findByTrash && $findByTrash->caid) {
                    $requestData = $request->all();
                    // Handle image upload if present
                    if ($request->hasFile('image')) {
                        $path = $request->file('image')->store('teachers', 'public');
                        $requestData['image'] = $path;
                    }
                    $is_disabled = null;

                    $requestData['caid'] = @$findByTrash->caid;
                    $requestData['eiin'] = $eiinId;

                    if (!empty($findByTrash->deleted_at)) {
                        $teacher_data = $this->teacherService->update($requestData, $findByTrash->uid, true);
                    } else {
                        $teacher_data = $this->teacherService->update($requestData, $findByTrash->uid);
                    }

                    if (@$findByTrash->mobile_no != $requestData['mobile_no']) {
                        $requestData['is_sms_send'] = 3;
                    }
                  
                    $user_account = $this->authService->accountUpdate($requestData, $findByTrash->caid, $requestData['eiin'], $is_disabled, 1);

                    if (!$user_account) {
                   
                        $user_account = $this->authService->teacher($requestData, $requestData['eiin']);
                    }
                    $requestData['user_type_id'] = data_get($user_account, 'data.user_type_id', data_get($user_account, 'user_type_id'));
                    $requestData['role'] = data_get($user_account, 'data.role', data_get($user_account, 'role'));
                    $this->userService->update($findByTrash->caid, $requestData);
                 
                } else {
                    $request['is_foreign'] = 0;
                    $requestData = $request->all();
                    if ($request->hasFile('image')) {
                        $path = $request->file('image')->store('teachers', 'public');
                        $requestData['image'] = $path;
                    }
                    $teacher_data = $this->teacherService->create($requestData);
                }

                DB::commit();
                $message = 'শিক্ষক সফলভাবে যুক্ত করা হয়েছে।';
                return $this->successResponseWithData($teacher_data, $message, Response::HTTP_OK);
            } else {
                DB::rollBack();
                $message = $authRequest->message;
                return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
            }
        } catch (Exception $e) {
            DB::rollBack();
            // return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
            $message = 'শিক্ষক যুক্ত করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function update(TeacherUpdateRequest $request)
    {
        try {
            $eiinId = getAuthInfo()['eiin'];
            DB::beginTransaction();
            $findByTrash = $this->teacherService->getWithTrashedById($request->uid);

            if ($findByTrash && $findByTrash->caid) {
                $requestData = $request->all();
                // Handle image upload if present
                if ($request->hasFile('image')) {
                    $path = $request->file('image')->store('teachers', 'public');
                    $requestData['image'] = $path;
                }

                if ($request->is_disabled) {
                    $is_disabled = $request->is_disabled;  // not sent message , msg send if null
                } else {
                    $is_disabled = null;
                }
                $requestData['caid'] = @$findByTrash->caid;
                $requestData['eiin'] = $eiinId;

                if (!empty($findByTrash->deleted_at)) {
                    $teacher_data = $this->teacherService->update($requestData, $findByTrash->uid, true);
                } else {
                    $teacher_data = $this->teacherService->update($requestData, $findByTrash->uid);
                }

                if (@$findByTrash->mobile_no != $requestData['mobile_no']) {
                    $requestData['is_sms_send'] = 3;
                }
    
                $user_account = $this->authService->accountUpdate($requestData, $findByTrash->caid, null, $is_disabled, 1);

                if (!$user_account) {
                    Log::info('yoyoyo13');
                    $user_account = $this->authService->teacher($requestData, $requestData['eiin']);
                }
                Log::info('yoyoyo14');
                $requestData['user_type_id'] = data_get($user_account, 'data.user_type_id', data_get($user_account, 'user_type_id'));
                $requestData['role'] = data_get($user_account, 'data.role', data_get($user_account, 'role'));
                $this->userService->update($findByTrash->caid, $requestData);
                DB::commit();

                $message = 'শিক্ষকের তথ্য সফলভাবে আপডেট করা হয়েছে।';
                return $this->successResponseWithData($teacher_data, $message, Response::HTTP_OK);
            } else {
                DB::rollBack();
                $message = 'শিক্ষকের তথ্য আপডেট করা যায় নি।';
                return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
            }
        } catch (Exception $e) {
               Log::info('yoyoyo2');
               Log::info($e->getMessage());
            DB::rollBack();
            $message = 'শিক্ষকের তথ্য আপডেট করা যায় নি।';
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        }
    }

    public function destroy(Request $request)
    {
        $result = $this->teacherService->delete($request->id);

        if (is_array($result) && array_key_exists('success', $result) && $result['success'] === false) {
            return $this->errorResponse($result['message'] ?? 'শিক্ষক মুছে ফেলা যায় নি।', Response::HTTP_BAD_REQUEST);
        }

        $message = is_array($result) && isset($result['message']) ? $result['message'] : 'শিক্ষক এর তথ্যটি মুছে ফেলা হয়েছে।';
        return $this->successMessage($message);
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
            $data['pi_evaluation_list'] = PiEvaluation::on('db_evaluation')
                ->where('teacher_uid', $teacher->uid)
                ->where('submit_status', $request->submit_status)
                ->where('student_uid', $request->student_uid)
                ->get();
            $data['bi_evaluation_list'] = BiEvaluation::on('db_evaluation')
                ->where('teacher_uid', $teacher->uid)
                ->where('submit_status', $request->submit_status)
                ->where('student_uid', $request->student_uid)
                ->get();
        } elseif ($request->submit_status) {
            $data['pi_evaluation_list'] = PiEvaluation::on('db_evaluation')
                ->where('teacher_uid', $teacher->uid)
                ->where('submit_status', $request->submit_status)
                ->get();
            $data['bi_evaluation_list'] = BiEvaluation::on('db_evaluation')
                ->where('teacher_uid', $teacher->uid)
                ->where('submit_status', $request->submit_status)
                ->get();
        } elseif ($request->student_uid) {
            $data['pi_evaluation_list'] = PiEvaluation::on('db_evaluation')
                ->where('teacher_uid', $teacher->uid)
                ->where('student_uid', $request->student_uid)
                ->get();
            $data['bi_evaluation_list'] = BiEvaluation::on('db_evaluation')
                ->where('teacher_uid', $teacher->uid)
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

    public function teacherDetails(Request $request)
    {
        if ($request->caid) {
            $caid = $request->caid;
        } else {
            $caid = app('sso-auth')->user()->caid
                ?? app('sso-auth')->user()->pdsid;
        }
        $data['user'] = $this->teacherService->getByCaId($caid);
        if ($data['user']) {
            $data['institute'] = Institute::select(['uid', 'eiin', 'caid', 'division_uid', 'district_uid', 'upazila_uid', 'unions', 'institute_name', 'institute_name_bn'])->where('eiin', $data['user']->eiin)->get();
            $data['class_teachers'] = ClassRoom::select(['uid', 'class_id'])->where('class_teacher_id', $data['user']->uid)->get();
            $data['subject_teachers'] = SubjectTeacher::select(['uid', 'subject_uid', 'class_room_uid', 'status'])->where('teacher_uid', $data['user']->uid)->get();
            $data['class_room'] = ClassRoom::select(['uid', 'class_id', 'branch_id', 'shift_id', 'version_id', 'section_id', 'status'])->whereIn('uid', $data['subject_teachers']->pluck('class_room_uid')->toArray())->orWhere('class_teacher_id', $data['user']->uid)->get();
            $data['shifts'] = Shift::select(['uid', 'eiin', 'shift_name_bn', 'shift_name_en', 'shift_details'])->whereIn('uid', $data['class_room']->pluck('shift_id')->toArray())->get();
            $data['versions'] = Version::select(['uid', 'eiin', 'version_name_bn', 'version_name_en'])->whereIn('uid', $data['class_room']->pluck('version_id')->toArray())->get();
        } else {
            return $this->errorResponse('দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।', Response::HTTP_NOT_FOUND);
        }
        // foreach ($data['subjects'] as $key => $item) {
        //     $data['subjects'][$key]['subject_info'] = $this->subjectService->getSubjectInfo($item['subject_id']);
        // }

        try {
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function bulkTeacherSmsSend(Request $request)
    {
        $validation = Validator::make($request->all(), [
            // 'branch_id'  => 'required',
            // 'version_id' => 'required',
            // 'shift_id'   => 'required',
            // 'class_id'   => 'required',
            // 'section_id' => 'required',
            'teacherData' => 'required|array|min:1',
            'teacherData.*.teacher_id' => 'required',
            'teacherData.*.phone_no' => 'required',
            'teacherData.*.text' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(), Response::HTTP_NOT_FOUND);
            // return $this->error($validation->errors()->first(), 400, []);
        }

        if (!array_key_exists('teacherData', $request->all()) || count($request->teacherData) == 0)
            return $this->errorResponse('কমপক্ষে একজন ছাত্র/ছাত্রী হাজিরা ইনপুট দিন', Response::HTTP_NOT_ACCEPTABLE);

        $teachers = [];
        foreach ($request->teacherData as $key => $data) {
            $payload = [
                'eiin' => app('sso-auth')->user()->eiin,
                // 'branch_id'     => $request->branch_id,
                // 'version_id'    => $request->version_id,
                // 'shift_id'      => $request->shift_id,
                // 'class_id'      => $request->class_id,
                // 'section_id'    => $request->section_id,
                'teacher_id' => $data['teacher_id'] ?? null,
                'phone_no' => $data['phone_no'] ?? null,
                'text' => $data['text'] ?? null,
            ];

            $teachers[] = TeacherSms::create($payload);

            $textSend = SmsService::sendSMS($data['text'], $data['phone_no']);
            $this->smsLogService->store(app('sso-auth')->user()->eiin, $data['phone_no'], $data['text'], $textSend, $data['teacher_id']);
        }

        return $this->successResponseWithData($teachers, '', Response::HTTP_OK);
    }

    // public function classTeacherCheck(Request $request)
    // {
    //     try {
    //         $teacher_exists = $this->teacherService->classTeacherCheck($request->uid);

    //         return $this->successResponse($teacher_exists, Response::HTTP_OK);
    //     } catch (Exception $e) {
    //         return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
    //     }
    // }

    // public function teacherSubjectList(Request $request)
    // {
    //     try {
    //         $subjects = $this->subjectTeacherService->getOwnSubjectByTeacherId($request->uid, date('Y'))->toArray();
    //         foreach ($subjects as $key => $item) {
    //             $subjects[$key]['subject_info'] = $this->subjectService->getSubjectInfo($item['subject_id']);
    //         }

    //         return $this->successResponse($subjects, Response::HTTP_OK);
    //     } catch (Exception $e) {
    //         return $this->errorResponse("দুঃখিত। কোন তথ্য খুঁজে পাওয়া যায় নি।", Response::HTTP_NOT_FOUND);
    //     }
    // }

    // public function oviggotaPiList(Request $request)
    // {
    //     $data['subjects'] = $this->subjectService->getAll();

    //     foreach ($data['subjects'] as $key => $item) {
    //         // $data['subjects'][$key]['subject_info'] = $this->subjectService->getSubjectInfo($item['subject_id']);
    //         $data['subjects'][$key]['competence'] = $this->subjectService->getCompetenceBySubject(['subject_id' => $item['uid']]);
    //         $data['subjects'][$key]['oviggota'] = $this->subjectService->getOviggotaBySubject(['subject_uid' => $item['uid']]);
    //         $data['subjects'][$key]['pi_selection'] = $this->subjectService->getPiSelectionBySubject(['session' => date('Y'), 'subject_uid' => $item['uid']]);
    //     }
    //     try {
    //         return $this->successResponse($data, Response::HTTP_OK);
    //     } catch (Exception $exc) {
    //         return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
    //     }
    // }
}

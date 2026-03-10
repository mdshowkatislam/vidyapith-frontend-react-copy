<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Teacher;
use App\Models\User;
use App\Services\TeacherService;
use App\Services\Api\AuthService;

use App\Services\DivisionService;
use App\Services\DistrictService;
use App\Services\UpazillaService;
use App\Services\DesignationService;
use App\Services\UserService;
use Exception;

class TeacherController extends Controller
{
    private $teacherService;
    private $authService;
    private $divisionService;
    private $districtService;
    private $upazillaService;
    private $designationService;
    private $userService;

    public function __construct(
        TeacherService $teacherService,
        AuthService $authService,
        DivisionService $divisionService,
        DistrictService $districtService,
        UpazillaService $upazillaService,
        DesignationService $designationService,
        UserService $userService
    ) {
        $this->teacherService = $teacherService;
        $this->authService = $authService;
        $this->divisionService = $divisionService;
        $this->districtService = $districtService;
        $this->upazillaService = $upazillaService;
        $this->designationService = $designationService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $search = $request->search;

            $myTeachers = $this->teacherService->getByEiinId($eiinId, '', '', $search);
            return view('frontend/noipunno/teacher-add/index', compact('myTeachers', 'search'));
        } catch (Exception $e) {
            return view('errors/404');
        }
    }
    public function add(Request $request)
    {
        try {
            $eiinId = auth()->user()->eiin;
            $institute = $this->teacherService->getInstituteByEiin($eiinId, 1);
            $designations = $this->designationService->list(1);
            $divisions = $this->divisionService->list(1);
            $districts = $this->districtService->list(1);
            $upazilas = $this->upazillaService->list(1);
            $countries = Country::all();
            $teacher_found = [];
            if ($request->pds_index) {
                $teacher_exists = $this->teacherService->getByPdsOrIndex($request->pds_index);
                if ($teacher_exists) {
                    $notification = array(
                        'message' => $teacher_exists->name_en . ' ইতিমধ্যে ' . @$teacher_exists->institute->institute_name . ' (Eiin - ' . @$teacher_exists->institute->eiin . ') এ যুক্ত রয়েছে! নতুন স্কুলে যুক্ত করার জন্য তাকে পূর্বের স্কুল থেকে অপসারণ করতে হবে।',
                        'alert-swal-type' => 'error',
                    );
                    return redirect()->route('teacher.add')->with($notification);
                }
                $teacher_found = $this->teacherService->getEmisTeacherByPdsID($request->pds_index);
                if (!$teacher_found) {
                    $teacher_found = $this->teacherService->getBanbiesTeacherByIndexNo($request->pds_index);
                }
            }
            return view('frontend/noipunno/teacher-add/add', compact('institute', 'divisions', 'districts', 'upazilas', 'designations', 'teacher_found', 'countries'));
        } catch (Exception $e) {
            return view('errors/404');
        }
    }

    public function store(Request $request)
    {

        $request->validate(
            [
                'pdsid' => 'nullable',
                'name_en' => 'required|max:150',
                'name_bn' => 'nullable|max:150',
                'email' => 'nullable',
                'mobile_no' => 'required|numeric',
                'date_of_birth' => 'nullable|date',
                'joining_date' => 'nullable|date',
                'last_working_date' => 'nullable|date',
                'mpo_code' => 'nullable|numeric',
                'nid' => 'nullable|string',
                // 'nid' => 'nullable|string|unique:teachers',
                'ismpo' => 'nullable|numeric',
                // 'teacher_type' => 'required',
                // 'access_type' => 'required',
                'isactive' => 'nullable|numeric',
                'designation' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:300',
            ],
            [
                'name_en.required' => 'শিক্ষকের নাম প্রদান করুন',
                //'teacher_type.required'=> 'শিক্ষকের ধরন প্রদান করুন',
                //'access_type.required' => 'অ্যাক্সেসের ধরন প্রদান করুন',
                'designation.required' => 'শিক্ষকের পদবি প্রদান করুন',
                'mobile_no.required' => 'মোবাইল নম্বর প্রদান করুন',
            ]
        );


        if ($request->is_foreign == 1) {
            $this->validate(
                request(),
                [
                    'country_uid' => 'required',
                ],
                [
                    'country_uid.required' => 'দেশ নির্বাচন করুন',
                ]
            );
        } else {
            $this->validate(
                request(),
                [
                    'division_id' => 'required',
                    'district_id' => 'required',
                    'upazila_id' => 'required',
                ],
                [
                    'division_id.required' => 'বিভাগ নির্বাচন করুন',
                    'district_id.required' => 'জেলা নির্বাচন করুন',
                    'upazila_id.required'  => 'উপজেলা নির্বাচন করুন',
                ]
            );
        }

        try {

            DB::beginTransaction();
            if ($request->access_type) {
                $role_list = $request->access_type;
                $role_list = implode(',', $role_list);
                $request['role'] = $role_list;
            }

            $authRequest = $this->authService->teacher($request->all(), @$request->eiin);

            if (@$authRequest->status == true) {
                $authData = (object) $authRequest->data;
                $request['caid'] = $authData->caid;
                $request['eiin'] = $authData->eiin;

                $findByTrash = $this->teacherService->getWithTrashedById($authData->caid);

                if ($findByTrash && $findByTrash->caid) {
                    $requestData = $request->all();
                    if ($request->access_type) {
                        $role_list = $request->access_type;
                        $role_list = implode(',', $role_list);
                        $requestData['role'] = $role_list;
                    }
                    $is_disabled = null;

                    $requestData['caid'] = @$findByTrash->caid;
                    $requestData['eiin'] =  app('sso-auth')->user()->eiin;

                    if (!empty($findByTrash->deleted_at)) {
                        $this->teacherService->update($requestData, $findByTrash->uid, true);
                    } else {
                        $this->teacherService->update($requestData, $findByTrash->uid);
                    }

                    if (@$findByTrash->mobile_no != $requestData['mobile_no']) {
                        $requestData['is_sms_send'] = 3;
                    }

                    $user_account = $this->authService->accountUpdate($requestData, $findByTrash->caid, $requestData['eiin'], $is_disabled, 1);

                    if (!$user_account) {
                        $user_account = $this->authService->teacher($requestData, $requestData['eiin']);
                    }
                    $requestData['user_type_id'] =  $user_account->data['user_type_id'];
                    $requestData['role'] =  $user_account->data['role'];
                    $this->userService->update($findByTrash->caid, $requestData);
                } else {
                    $this->teacherService->create($request->all());
                }
                DB::commit();
                // $this->userService->create($request->all());
                $notification = array(
                    'message' => 'শিক্ষক সফলভাবে যুক্ত করা হয়েছে।',
                    'alert-type' => 'success'
                );
                return redirect()->route('teacher.index')->with($notification);
            } else {
                DB::rollBack();
                $notification = array(
                    'message' => $authRequest->message,
                    'alert-type' => 'error'
                );
                return back()->with($notification);
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function edit(Request $request, $id = null)
    {
        if ($id == null) {
            return redirect()->back()->with('error', 'Required Parameter Missing.');
        }

        $myteacher = $this->teacherService->getById($id);
        // $eiinId = auth()->user()->eiin;
        // $myTeachers = $this->teacherService->getByEiinId($eiinId);
        // $banbiesTeachers = $this->teacherService->getBanbeisTeachersByEiinID($eiinId, 1);
        // $emisTeachers = $this->teacherService->getEmisTeachersByEiinID($eiinId, 1);
        // if($request->eiin_pdsid){
        //     $emisTeachers = $emisTeachers->where('pdsid', $request->eiin_pdsid);
        // }
        $designations = $this->designationService->list(1);
        $divisions = $this->divisionService->list(1);
        $districts = $this->districtService->list(1);
        $upazilas = $this->upazillaService->list(1);
        $countries = Country::all();
        $eiinId = auth()->user()->eiin;
        $institute = $this->teacherService->getInstituteByEiin($eiinId, 1);

        return view('frontend/noipunno/teacher-add/edit', compact('myteacher', 'divisions', 'districts', 'upazilas', 'designations', 'countries'));
    }

    public function fromEmis(Request $request, $pdsid = null)
    {
        if ($pdsid == null) {
            return redirect()->back()->with('error', 'Required Parameter Missing.');
        }

        $myteacher = $this->teacherService->getEmisTeachersById($pdsid);
        $eiinId = auth()->user()->eiin;
        $myTeachers = $this->teacherService->getByEiinId($eiinId, 1);
        $banbiesTeachers = $this->teacherService->getBanbeisTeachersByEiinID($eiinId, 1);
        $emisTeachers = $this->teacherService->getEmisTeachersByEiinID($eiinId, 1);
        if ($request->eiin_pdsid) {
            $emisTeachers = $emisTeachers->where('pdsid', $request->eiin_pdsid);
        }
        $designations = $this->designationService->list(1);
        $divisions = $this->divisionService->list(1);
        $districts = $this->districtService->list(1);
        $upazilas = $this->upazillaService->list(1);
        return view('frontend/noipunno/teacher-add/edit', compact('myteacher', 'myTeachers', 'banbiesTeachers', 'emisTeachers', 'divisions', 'districts', 'upazilas', 'designations'));
    }

    public function fromBanbies(Request $request, $id = null)
    {
        if ($id == null) {
            return redirect()->back()->with('error', 'Required Parameter Missing.');
        }

        $myteacher = $this->teacherService->getBanbeisTeachersById($id);
        $eiinId = auth()->user()->eiin;
        $myTeachers = $this->teacherService->getByEiinId($eiinId, 1);
        $banbiesTeachers = $this->teacherService->getBanbeisTeachersByEiinID($eiinId, 1);
        $emisTeachers = $this->teacherService->getEmisTeachersByEiinID($eiinId, 1);
        if ($request->eiin_pdsid) {
            $emisTeachers = $emisTeachers->where('pdsid', $request->eiin_pdsid);
        }
        $designations = $this->designationService->list(1);
        $divisions = $this->divisionService->list(1);
        $districts = $this->districtService->list(1);
        $upazilas = $this->upazillaService->list(1);
        return view('frontend/noipunno/teacher-add/edit', compact('myteacher', 'myTeachers', 'banbiesTeachers', 'emisTeachers', 'divisions', 'districts', 'upazilas', 'designations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                // 'pdsid' => 'nullable',
                'name_en' => 'required|max:150',
                'name_bn' => 'nullable|max:150',
                'email' => 'nullable',
                'mobile_no' => 'required|numeric',
                'date_of_birth' => 'nullable|date',
                'joining_date' => 'nullable|date',
                'last_working_date' => 'nullable|date',
                'mpo_code' => 'nullable|numeric',
                // 'nid' => 'nullable',
                'ismpo' => 'nullable|numeric',
                'teacher_type' => 'nullable',
                // 'access_type' => 'nullable',
                'isactive' => 'nullable|numeric',
                'designation' => 'required',
                // 'division_id' => 'required',
                // 'district_id' => 'required',
                // 'upazila_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:300',
                'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100',
            ],
            [
                'name_en.required' => 'শিক্ষকের নাম প্রদান করুন',
                'designation.required' => 'শিক্ষকের পদবি প্রদান করুন',
                'mobile_no.required' => 'মোবাইল নম্বর প্রদান করুন',
                // 'division_id.required'=> 'বিভাগ নির্বাচন করুন',
                // 'district_id.required'=> 'জেলা নির্বাচন করুন',
                // 'upazila_id.required'=> 'উপজেলা নির্বাচন করুন',
            ]
        );

        if ($request->is_foreign == 1) {
            $this->validate(
                request(),
                [
                    'country_uid' => 'required',
                ],
                [
                    'country_uid.required' => 'দেশ নির্বাচন করুন',
                ]
            );
        } else {
            $this->validate(
                request(),
                [
                    'division_id' => 'required',
                    'district_id' => 'required',
                    'upazila_id' => 'required',
                ],
                [
                    'division_id.required' => 'বিভাগ নির্বাচন করুন',
                    'district_id.required' => 'জেলা নির্বাচন করুন',
                    'upazila_id.required' => 'উপজেলা নির্বাচন করুন',
                ]
            );
        }

        try {

            DB::beginTransaction();
            $findByTrash = $this->teacherService->getWithTrashedById($id);

            if ($findByTrash && $findByTrash->caid) {
                $requestData = $request->all();
                if ($request->access_type) {
                    $role_list = $request->access_type;
                    $role_list = implode(',', $role_list);
                    $requestData['role'] = $role_list;
                }
                if ($request->is_disabled) {
                    $is_disabled = $request->is_disabled; //not sent message , msg send if null
                } else {
                    $is_disabled = null;
                }

                $requestData['caid'] = @$findByTrash->caid;
                $requestData['eiin'] =  app('sso-auth')->user()->eiin;

                if (!empty($findByTrash->deleted_at)) {
                    $this->teacherService->update($requestData, $findByTrash->uid, true);
                } else {
                    $this->teacherService->update($requestData, $findByTrash->uid);
                }

                if (@$findByTrash->mobile_no != $requestData['mobile_no']) {
                    $requestData['is_sms_send'] = 3;
                }

                $user_account = $this->authService->accountUpdate($requestData, $findByTrash->caid, null, $is_disabled, 1);

                if (!$user_account) {
                    $user_account = $this->authService->teacher($requestData, $requestData['eiin']);
                }
                $requestData['user_type_id'] =  $user_account->data['user_type_id'];
                $requestData['role'] =  $user_account->data['role'];
                $this->userService->update($findByTrash->caid, $requestData);

                $notification = array(
                    'message' => 'শিক্ষকের তথ্য সফলভাবে আপডেট করা হয়েছে।',
                    'alert-type' => 'success',
                );
                DB::commit();

                return redirect()->route('teacher.index')->with($notification);
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
                    // $mergedData['eiin'] = app('sso-auth')->user()->eiin;

                    $teacherExists = $this->teacherService->getWithTrashedById(@$authData->caid);
                    // $teacherExists = $this->teacherService->getWithTrashedById(@$findByTrash->caid ? @$authData->caid: @$findByTrash->pdsid);

                    if ($teacherExists) {
                        $is_restore = $teacherExists->deleted_at ? true : false;
                        $this->teacherService->update($mergedData, $teacherExists->uid, $is_restore);
                        $this->userService->update($authData->caid, $mergedData);
                    } else {
                        $this->teacherService->create($mergedData);
                        // $this->userService->create($mergedData);
                    }
                    $notification = array(
                        'message' => 'শিক্ষকের তথ্য সফলভাবে আপডেট করা হয়েছে।',
                        'alert-type' => 'success',
                    );
                    DB::commit();
                    return redirect()->route('teacher.index')->with($notification);
                } else {
                    DB::rollBack();

                    $notification = array(
                        'message' => 'শিক্ষকের তথ্য আপডেট করা যায় নি।',
                        'alert-type' => 'error',
                    );
                    return back()->with($notification);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function getAllTeachersByPdsID(Request $request)
    {
        $eiinId = auth()->user()->eiin;
        $pdsid = $request->id;
        $emisTeachers = $this->teacherService->getEmisTeachersByEiinAndPdsID($eiinId, $pdsid);
        return response()->json($emisTeachers);
    }

    public function delete(Request $request)
    {
        $this->teacherService->delete($request->id);
        return response()->json(['status' => 'success', 'message' => 'তথ্যটি মুছে ফেলা হয়েছে।']);
    }
}

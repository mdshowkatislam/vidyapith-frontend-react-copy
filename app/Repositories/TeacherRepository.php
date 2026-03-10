<?php

namespace App\Repositories;

use App\Models\BanbeisTeacher;
use App\Models\ClassRoom;
use App\Models\Designation;
use App\Models\EmisTeacher;
use App\Models\Institute;
use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Services\AttendanceSyncService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherRepository implements TeacherRepositoryInterface
{
    protected $attendanceSyncService;

    public function __construct(AttendanceSyncService $attendanceSyncService)
    {
        $this->attendanceSyncService = $attendanceSyncService;
    }

    public function list()
    {
        return Teacher::on('db_read')->get();
    }

    public function create($data)
    {
        DB::beginTransaction();
        try {
            // Check for existing teacher to prevent duplicates
            $existingTeacher = $this->findExistingTeacher($data);
            if ($existingTeacher) {
                return $existingTeacher;
            }

            $birthday = '';
            if (isset($data['date_of_birth']) && !empty($data['date_of_birth'])) {
                $birthday = date('Y-m-d', strtotime($data['date_of_birth']));
            } elseif (isset($data['dateofbirth']) && !empty($data['dateofbirth'])) {
                $birthday = date('Y-m-d', strtotime($data['dateofbirth']));
            } else {
                $birthday = NULL;
            }

            $last_working_date = '';
            if (isset($data['last_working_date']) && !empty($data['last_working_date'])) {
                $last_working_date = date('Y-m-d', strtotime($data['last_working_date']));
            } elseif (isset($data['last_work_date_as_employee']) && !empty($data['last_work_date_as_employee'])) {
                $last_working_date = date('Y-m-d', strtotime($data['last_work_date_as_employee']));
            } else {
                $last_working_date = NULL;
            }

            $joining_date = '';
            if (isset($data['joining_date']) && !empty($data['joining_date'])) {
                $joining_date = date('Y-m-d', strtotime($data['joining_date']));
            } elseif (isset($data['date_first_join']) && !empty($data['date_first_join'])) {
                $joining_date = date('Y-m-d', strtotime($data['date_first_join']));
            } else {
                $joining_date = NULL;
            }

            if (!empty($data['pdsid'])) {
                $customEmail = $data['pdsid'] . '@noipunno.gov.bd';
            } else {
                $customEmail = '';
            }

            $teacher = new Teacher;
            $teacher->eiin = @$data['eiin'] ?? @$data['eiin_no'];  // ?? @$data['caid'];
            $teacher->caid = @$data['caid'];
            $teacher->emp_id = @$data['emp_id'];
            $teacher->pdsid = @$data['pdsid'] ?? @$data['emp_uid'];
            $teacher->name_en = @$data['name_en'] ?? @$data['fullname'] ?? @$data['employee_name'];
            $teacher->name_bn = @$data['name_bn'] ?? @$data['fullname_bn'] ?? @$data['employee_name_bn'];
            $teacher->fathers_name = @$data['fathers_name'] ?? @$data['fathersname'];
            $teacher->mothers_name = @$data['mothers_name'] ?? @$data['mothersname'];
            $teacher->email = @$data['email'] ?? @$customEmail;
            $teacher->mobile_no = @$data['mobile_no'] ?? @$data['mobileno'] ?? @$data['mobile_number'];
            $teacher->gender = @$data['gender'];
            $teacher->date_of_birth = $birthday;
            $teacher->institute_name = @$data['institute_name'] ?? @$data['institutename'];
            $teacher->institute_type = @$data['institute_type'] ?? @$data['levelname'];
            $teacher->institute_category = @$data['institute_category'] ?? @$data['institute_type_name'];
            $teacher->index_number = @$data['index_number'] ?? @$data['indexnumber'] ?? @$data['index_no'];
            $teacher->workstation_name = @$data['workstation_name'];
            $teacher->branch_institute_name = @$data['branch_institute_name'] ?? @$data['institute_name'] ?? @$data['institutename'];
            $teacher->branch_institute_category = @$data['branch_institute_category'] ?? @$data['institute_category'] ?? @$data['institute_type_name'];
            $teacher->service_break_institute = @$data['service_break_institute'];

            // $teacher->designation_id = @$data['designation_id'] ?? @$data['designationid'] ?? @$data['designation'] ?? 2;
            $teacher->designation_id = @$data['designation'];
            $designation = Designation::where('uid', @$data['designation'])->first();
            $teacher->designation = @$designation->designation_name;
            // $teacher->designation = @$data['designation'] ?? @$data['designation_name'];
            $teacher->division_id = @$data['division_id'] ?? @$data['divisionid'];
            $teacher->district_id = @$data['district_id'] ?? @$data['districtid'];
            $teacher->upazilla_id = @$data['upazilla_id'] ?? @$data['upazila_id'] ?? @$data['upazilaid'];
            $teacher->joining_year = date('Y', strtotime(@$data['joining_date']));
            $teacher->mpo_code = @$data['mpo_code'];
            $teacher->joining_date = $joining_date;
            $teacher->last_working_date = $last_working_date;
            $teacher->nid = @$data['nid'];
            $teacher->teacher_type = @$data['teacher_type'];
            // $teacher->access_type = @$data['access_type'];
            $teacher->role = @$data['role'];
            $teacher->ismpo = @$data['ismpo'];  // mpo or not
            $teacher->isactive = @$data['isactive'] ?? 1;  // teacher active or not
            $teacher->data_source = @$data['data_source'];  // emis or bandbeis
            // $teacher->teacher_source = @$data['teacher_source'];
            $teacher->blood_group = @$data['blood_group'];
            $teacher->emergency_contact = @$data['emergency_contact'];
            $teacher->image = @$data['image'];
            $teacher->signature = @$data['signature'];
            $teacher->address = @$data['address'];  // address field added, check if the field exists in DB

            $teacher->is_foreign = @$data['is_foreign'];
            $teacher->country = @$data['country_uid'];
            $teacher->state = @$data['state'];
            $teacher->city = @$data['city'];
            $teacher->zip_code = @$data['zip_code'];

            $teacher->save();
            Log::info('qqq4');
            if (config('services.attendance.enabled')) {
                $this->attendanceSyncService->sync($teacher, 'create');
            }

            DB::commit();

            return $teacher;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Teacher creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update($data, $id, $is_restore = false)
    {
        Log::info('8877', $data);
        Log::info('8899' . $data['name_en']);

        DB::beginTransaction();

        try {
            if (!empty($data['pdsid'])) {
                $customEmail = $data['pdsid'] . '@gmail.com';
            } else {
                $customEmail = 'jafargonjcollegebd@gmail.com';
            }

            if ($is_restore) {
                Teacher::where('caid', $id)->orWhere('uid', $id)->onlyTrashed()->restore();
            }

            // Try to find teacher by CAID first, then by UID to handle both caller conventions
            $teacher = Teacher::where('caid', $id)->orWhere('uid', $id)->first();

            if (!$teacher) {
                DB::rollBack();
                Log::error('Teacher not found for update', ['id' => $id, 'data' => $data]);
                throw new \Exception('Teacher not found');
            }

            if (empty($teacher->pdsid) && !empty(@$data['pdsid'])) {
                $teacher->pdsid = @$data['pdsid'];
            }
            if (empty($teacher->index_number) && !empty(@$data['index_number'])) {
                $teacher->index_number = @$data['index_number'];
            }

            if (empty(@$data['emp_id'])) {
                Log::info('emp_id empty in teacher update');
                throw new \Exception('emp_id cannot be empty.');
            }

            $designation = Designation::where('uid', @$data['designation'])->first();
            $teacher->eiin = @$data['eiin'];
            $teacher->caid = @$data['caid'];
            $teacher->emp_id = $data['emp_id'];
            $teacher->name_en = $data['name_en'] ?? null;
            $teacher->name_bn = @$data['name_bn'] ?? null;
            $teacher->designation = @$designation->designation_name;
            $teacher->designation_id = $data['designation'];
            $teacher->fathers_name = @$data['fathers_name'] ?? @$data['fathersname'];
            $teacher->mothers_name = @$data['mothers_name'] ?? @$data['mothersname'];
            $teacher->email = @$data['email'] ?? $customEmail;
            $teacher->mobile_no = @$data['mobile_no'] ?? @$data['mobileno'] ?? @$data['mobile_number'];
            $teacher->division_id = @$data['division_id'] ?? @$data['divisionid'];
            $teacher->district_id = @$data['district_id'] ?? @$data['districtid'];
            $teacher->upazilla_id = @$data['upazilla_id'] ?? @$data['upazila_id'] ?? @$data['upazilaid'];
            $teacher->gender = @$data['gender'];
            $teacher->teacher_type = @$data['teacher_type'];
            $teacher->blood_group = @$data['blood_group'];
            $teacher->emergency_contact = @$data['emergency_contact'];
            $teacher->joining_date = @$data['joining_date'];
            // $teacher->access_type = @$data['access_type'];
            // $teacher->role = @$data['role'];
            // Only update image/signature if a new value is provided to avoid clearing existing files unintentionally
            if (array_key_exists('image', $data) && !empty($data['image'])) {
                $teacher->image = $data['image'];
            }
            if (array_key_exists('signature', $data) && !empty($data['signature'])) {
                $teacher->signature = $data['signature'];
            }
            $teacher->address = @$data['address'];  // address field added, check if the field exists in DB
            $teacher->is_foreign = @$data['is_foreign'];
            $teacher->country = @$data['country_uid'];
            $teacher->state = @$data['state'];
            $teacher->city = @$data['city'];
            $teacher->zip_code = @$data['zip_code'];
            $teacher->save();
            Log::info('qqq3');
            // Sync with attendance service
            if (config('services.attendance.enabled')) {
                $this->attendanceSyncService->sync($teacher, 'update', $id);
            }

            DB::commit();

            return $teacher;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::debug('Teacher update failed:' . $e->getMessage(), [
                'student_id' => $id,
                'data' => $data,
                'exception' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    public function getById($id)
    {
        return Teacher::on('db_read')->where('uid', $id)->orwhere('caid', $id)->first();
    }

    public function getByEmpId($emp_id)
    {
        return Teacher::on('db_read')->where('emp_id', $emp_id)->first();
    }

    public function getByEmpIdShort($emp_id)
    {
        if (empty($emp_id)) {
            return null;
        }

        $cacheKey = 'teacher_short_' . $emp_id;
        $cacheTime = 3600;

        return Cache::remember($cacheKey, $cacheTime, function () use ($emp_id) {
            return Teacher::on('db_read')
                ->where('emp_id', $emp_id)
                ->select(Teacher::SHORT_SELECT_FIELDS)
                ->first();
        });
    }

    public function getByIdWithPaginate($id)
    {
        return Teacher::on('db_read')->where('uid', $id)->paginate(1);
    }

    public function getByEmpIdWithPaginate($emp_id)
    {
        return Teacher::on('db_read')->where('emp_id', $emp_id)->paginate(1);
    }

    public function getByClass($teacher_id)
    {
        return Teacher::on('db_read')->whereIn('uid', $teacher_id)->paginate(200);
    }

    public function getWithTrashedById($id)
    {
        $data = DB::table('teachers')->where('uid', $id)->orWhere('caid', $id)->orWhere('pdsid', $id)->first();
        return $data;
    }

    public function getByCaId($id)
    {
        return Teacher::on('db_read')->where('caid', $id)->orWhere('pdsid', $id)->orWhere('index_number', $id)->first();
    }

    public function getByPdsOrIndex($id)
    {
        return Teacher::on('db_read')->where('pdsid', $id)->orWhere('index_number', $id)->where('pdsid', '!=', 0)->whereNotNull('index_number')->first();
    }

    public function getByEiinId($eiin, $is_not_paginate = null, $optimize = null, $search = null)
    {
        if ($optimize) {
            return Teacher::on('db_read')->with('designations')->select('uid', 'pdsid', 'caid', 'name_en', 'name_bn', 'index_number', 'image')->where('eiin', $eiin)->get();
        } else {
            if ($is_not_paginate) {
                return Teacher::on('db_read')->with('designations')->select('uid', 'pdsid', 'caid', 'emp_id', 'name_en', 'name_bn', 'index_number', 'designation_id', 'mobile_no', 'eiin', 'division_id', 'district_id', 'upazilla_id', 'blood_group', 'gender', 'image', 'address')->where('eiin', $eiin)->get();
            } else {
                return Teacher::on('db_read')
                    ->with('designations')
                    ->select('uid', 'pdsid', 'caid', 'emp_id', 'name_en', 'name_bn', 'index_number', 'designation_id', 'mobile_no', 'eiin', 'division_id', 'district_id', 'upazilla_id', 'blood_group', 'gender', 'image', 'address')
                    ->where('eiin', $eiin)
                    ->where('deleted_at', null)
                    ->where(function ($query) use ($search) {
                        if ($search) {
                            $query
                                ->where('pdsid', 'like', '%' . $search . '%')
                                ->orWhere('caid', 'like', '%' . $search . '%')
                                ->orWhere('emp_id', 'like', '%' . $search . '%')
                                ->orWhere('name_en', 'like', '%' . $search . '%')
                                ->orWhere('index_number', 'like', '%' . $search . '%')
                                ->orWhere('mobile_no', 'like', '%' . $search . '%')
                                ->orWhere('address', 'like', '%' . $search . '%');
                        }
                    })
                    ->paginate(200);
            }
        }
    }

    public function getBanbeisTeachers()
    {
        return DB::table('banbeis_teacher')->get();
    }

    public function getBanbeisTeachersById($id)
    {
        return DB::table('banbeis_teacher')->where('emp_uid', $id)->first();
    }

    public function getBanbeisTeachersByEiinID($eiin, $optimize = null)
    {
        if ($optimize) {
            return DB::table('banbeis_teacher')
                ->select('banbeis_teacher.emp_uid', 'banbeis_teacher.employee_name', 'banbeis_teacher.designation_name', 'banbeis_teacher.employee_name_bn')
                ->leftJoin('teachers', 'banbeis_teacher.emp_uid', '=', 'teachers.pdsid')
                ->whereNull('teachers.pdsid')
                ->where('banbeis_teacher.eiin_no', $eiin)
                ->whereNotIn('banbeis_teacher.designation_id', [41, 37, 108, 42, 27, 62, 26, 43, 46, 121, 40, 213, 158, 34, 217, 92])
                ->get();
        } else {
            return DB::table('banbeis_teacher')
                ->leftJoin('teachers', 'banbeis_teacher.emp_uid', '=', 'teachers.pdsid')
                ->whereNull('teachers.pdsid')
                ->where('banbeis_teacher.eiin_no', $eiin)
                ->whereNotIn('banbeis_teacher.designation_id', [41, 37, 108, 42, 27, 62, 26, 43, 46, 121, 40, 213, 158, 34, 217, 92])
                ->get();
        }
    }

    public function getEmisTeachers()
    {
        return DB::table('emis_teacher')
            ->whereIn('designationid', [1, 4, 7, 8, 11, 12, 13, 14, 15, 29, 30, 31, 32, 33, 37, 39, 43, 44, 45, 46, 47, 52, 53, 54, 56, 70, 71, 72, 73, 74, 77, 78, 79, 226])
            ->get();
    }

    public function getEmisTeachersById($pdsid)
    {
        return DB::table('emis_teacher')
            ->select('pdsid', 'fullname', 'mobileno', 'email', 'nid', 'eiin')
            ->where('pdsid', $pdsid)
            ->first();
    }

    public function getEmisTeachersByEiinID($eiin, $optimize = null)
    {
        if ($optimize) {
            return DB::table('emis_teacher')
                ->select('emis_teacher.fullname', 'emis_teacher.pdsid', 'emis_teacher.designation', 'emis_teacher.fullname_bn')
                ->leftJoin('teachers', 'emis_teacher.pdsid', '=', 'teachers.pdsid')
                ->where(function ($query) {
                    $query->whereNull('teachers.pdsid');
                    $query->orWhereNotNull('teachers.deleted_at');
                })
                ->where('emis_teacher.eiin', $eiin)
                ->whereNotIn('emis_teacher.designationid', [17, 35, 57, 113, 145, 239, 241, 242, 245, 247, 249, 250, 251, 252, 257, 258, 260, 262, 267, 276, 279, 280, 281, 282, 338, 339, 340, 341, 342, 343, 344, 347, 427, 527, 590, 589, 607, 627, 668, 707, 827, 867, 907, 947, 1031, 1033, 1037, 1107, 1127])
                // ->whereIn('emis_teacher.designationid', [1, 4, 7, 8, 11, 12, 13, 14, 15, 29, 30, 31, 32, 33, 37, 39, 43, 44, 45, 46, 47, 52, 53, 54, 56, 70, 71, 72, 73, 74, 77, 78, 79, 226])
                // ->limit(500)
                ->get();
        } else {
            return DB::table('emis_teacher')
                ->leftJoin('teachers', 'emis_teacher.pdsid', '=', 'teachers.pdsid')
                ->select('emis_teacher.*')
                ->where(function ($query) {
                    $query->whereNull('teachers.pdsid');
                    $query->orWhereNotNull('teachers.deleted_at');
                })
                ->where('emis_teacher.eiin', $eiin)
                ->whereNotIn('emis_teacher.designationid', [17, 35, 57, 113, 145, 239, 241, 242, 245, 247, 249, 250, 251, 252, 257, 258, 260, 262, 267, 276, 279, 280, 281, 282, 338, 339, 340, 341, 342, 343, 344, 347, 427, 527, 590, 589, 607, 627, 668, 707, 827, 867, 907, 947, 1031, 1033, 1037, 1107, 1127])
                // ->whereIn('emis_teacher.designationid', [1, 4, 7, 8, 11, 12, 13, 14, 15, 29, 30, 31, 32, 33, 37, 39, 43, 44, 45, 46, 47, 52, 53, 54, 56, 70, 71, 72, 73, 74, 77, 78, 79, 226])
                ->limit(500)
                ->get();
        }
    }

    public function getEmisTeachersByEiinAndPdsID($eiin, $pdsid)
    {
        return DB::table('emis_teacher')
            ->select('emis_teacher.*')
            ->leftJoin('teachers', 'emis_teacher.pdsid', '=', 'teachers.pdsid')
            ->where(function ($query) use ($pdsid) {
                $query->where('emis_teacher.pdsid', 'LIKE', '%' . $pdsid . '%');
                $query->orWhereNotNull('teachers.deleted_at');
            })
            ->where('emis_teacher.eiin', $eiin)
            ->limit(500)
            // ->whereIn('emis_teacher.designationid', [1, 4, 7, 8, 11, 12, 13, 14, 15, 29, 30, 31, 32, 33, 37, 39, 43, 44, 45, 46, 47, 52, 53, 54, 56, 70, 71, 72, 73, 74, 77, 78, 79, 226])
            ->get();
    }

    public function getEmisTeacherByPdsID($pdsid)
    {
        return DB::table('emis_teacher')
            ->select('*')
            ->where('pdsid', $pdsid)
            ->first();
    }

    public function getBanbiesTeacherByIndexNo($index_no)
    {
        return DB::table('banbeis_teacher')
            ->select('*')
            ->where('index_no', $index_no)
            ->first();
    }

    public function classTeacherCheck($teacher_uid)
    {
        return ClassRoom::select('uid', 'eiin', 'branch_id', 'version_id', 'shift_id', 'class_id', 'section_id', 'session_year')->where('class_teacher_id', $teacher_uid)->where('session_year', date('Y'))->first();
    }

    public function authAccountCreateTeacher($data)
    {
        //         $accessToken = UtilsCookie::getCookie();
        // $endpoint = 'https://accounts.project-ca.com/api/v1/user/account-create';
        // $response = Http::withHeaders([
        //     'Authorization' =>  'Bearer ' . $accessToken,
        //     'Content-Type' => 'application/json'
        // ])->post($endpoint, [
        //     'name' => @$data['name_en'],
        //     'email' => @$data['email'],
        //     'phone_no' => @$data['mobile_no'],
        //     'password' => 123456,
        //     'eiin' => @app('sso-auth')->user()->eiin,
        //     'pdsid' => @$data['pdsid'],
        //     'user_type_id' => 1,
        //     'year' => 2023,
        // ]);

        // if (!$response->ok()) {
        //     return false;
        // }
        // $result =  json_decode($response->getBody(), true);
        // return $result;
        return;
    }

    public function getInstituteByEiin($eiin, $has_eiin = null)
    {
        if (!$has_eiin) {
            return Institute::with('board')
                ->select('eiin', 'institute_name', 'institute_name_bn', 'board_uid', 'logo', 'is_foreign')
                ->where('eiin', $eiin)
                ->union(DB::table('banbeis_teacher')->select('eiin_no as eiin', 'institute_name')->where('eiin_no', $eiin))
                ->union(DB::table('emis_teacher')->select('eiin', 'institutename as institute_name')->where('eiin', $eiin))
                ->first();
        } else {
            return Institute::with('board')
                ->select('eiin', 'institute_name', 'institute_name_bn', 'board_uid', 'logo', 'is_foreign')
                ->where('eiin', $eiin)
                ->first();
        }
    }

    // public function delete($id)
    // {
    //     $result = Teacher::where('uid', $id)->first();
    //     $result->delete();
    //     return true;
    // }
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $teacher = Teacher::where('uid', $id)->first();

            if (!$teacher) {
                throw new \Exception('Teacher not found');
            }
            // remember whether we have an emp_id to decide attendance deletion
            $hasEmpId = !empty($teacher->emp_id);

            // Delete from local database first
            $teacher->delete();

            DB::commit();

            // If emp_id exists, try to delete from attendance service (don't roll back local deletion on failure)
            if ($hasEmpId && $this->attendanceSyncService->isEnabled()) {
                try {
                    $this->attendanceSyncService->deleteTeacher($teacher->emp_id);
                } catch (\Exception $attendanceEx) {
                    Log::error('Attendance delete failed for emp_id ' . $teacher->emp_id . ': ' . $attendanceEx->getMessage());

                    return [
                        'success' => true,
                        'message' => 'Teacher deleted locally, but attendance delete failed: ' . $attendanceEx->getMessage()
                    ];
                }

                return [
                    'success' => true,
                    'message' => 'Teacher deleted from both local system and attendance system'
                ];
            }

            // emp_id not provided: local deletion completed
            return [
                'success' => true,
                'message' => 'Teacher deleted locally. emp_id not provided so not deleted from attendance system.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Can not delete teacher: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to delete teacher: ' . $e->getMessage()
            ];
        }
    }

    public function teachersList($request)
    {
        $teachers = Teacher::on('db_read');

        if (!empty($request->input('name'))) {
            $teacherName = $request->input('name');
            $teachers = $teachers->where(function ($query) use ($teacherName) {
                $query
                    ->where('name_en', 'like', "%{$teacherName}%")
                    ->orWhere('name_bn', 'like', "%{$teacherName}%");
            });
        }

        if (app('sso-auth')->user()->caid != '4010001') {
            if ((app('sso-auth')->user()->user_type_id == '5') && (empty(app('sso-auth')->user()->upazila_id))) {
                $totalInstituteArr = Institute::on('db_read')->where('is_foreign', 1)->pluck('eiin')->toArray();
                $teachers = $teachers->whereIn('eiin', $totalInstituteArr);
            } else if (!empty($request->upazila_id)) {
                $teachers = $teachers->where('upazilla_id', $request->upazila_id);
            }
        }

        if (!empty($request->input('phone'))) {
            $teachers = $teachers->where('mobile_no', $request->input('phone'));
        }
        if (!empty($request->input('pdsid'))) {
            $teachers = $teachers->where('pdsid', $request->input('pdsid'));
        }

        $total_teacher = $teachers->count();
        // $perPage = 10; // Number of items per page
        $perPage = $request->limit ?? 10;  // Number of items per page
        $page = $request->page ?? 1;  // Current page number

        $offset = ($page - 1) * $perPage;

        $teachers = $teachers->skip($offset)->take($perPage)->get();

        return ['total_teacher' => $total_teacher, 'teachers' => $teachers];
    }

    public function searchTeacherByPDSID($request)
    {
        $pdsid = $request->pdsid ?? $request['pdsid'] ?? '';

        if (!empty($pdsid)) {
            $teachers_table_teacher = Teacher::on('db_read')->withTrashed()->where('pdsid', $pdsid)->orWhere('index_number', $pdsid)->first();

            if (!empty($teachers_table_teacher)) {
                $teacher = $teachers_table_teacher;
                $teacher->findFrom = 'teachers_table';
            }

            if (empty($teachers_table_teacher)) {
                $emis_teacher_table_teacher = EmisTeacher::on('db_read')->where('pdsid', '=', $pdsid)->orWhere('indexnumber', '=', $pdsid)->first();

                if (!empty($emis_teacher_table_teacher)) {
                    $teacher = $emis_teacher_table_teacher;
                    $teacher->findFrom = 'emis_teacher_table';
                }
            }

            if (empty($emis_teacher_table_teacher)) {
                $banbeis_student_table_teacher = BanbeisTeacher::on('db_read')->where('index_no', '=', $pdsid)->first();
                if (!empty($banbeis_student_table_teacher)) {
                    $teacher = $banbeis_student_table_teacher;
                    $teacher->findFrom = 'banbeis_student_table';
                }
            }
            return $teacher ?? '';
        }
    }

    public function upazillaTotalTeachers($request)
    {
        $teachers = Teacher::on('db_read')->where('upazilla_id', $request->id)->count();
        return $teachers;
    }

    public function foreignTotalTeachers()
    {
        $totalInstituteArr = Institute::on('db_read')->where('is_foreign', 1)->pluck('eiin')->toArray();
        $teachers = Teacher::on('db_read')->whereIn('eiin', $totalInstituteArr)->count();
        return $teachers;
    }

    /**
     * Find existing teacher by email, mobile_no, pdsid, or caid to prevent duplicates
     *
     * @param array $data
     * @return Teacher|null
     */
    private function findExistingTeacher($data)
    {
        $query = Teacher::query();

        // Check for existing teacher by email, mobile_no, pdsid, or caid
        if (!empty($data['email']) || !empty($data['mobile_no']) || !empty($data['pdsid']) || !empty($data['caid'])) {
            $query->where(function ($q) use ($data) {
                if (!empty($data['email'])) {
                    $q->where('email', $data['email']);
                }
                if (!empty($data['mobile_no'])) {
                    $q->orWhere('mobile_no', $data['mobile_no']);
                }
                if (!empty($data['pdsid'])) {
                    $q->orWhere('pdsid', $data['pdsid']);
                }
                if (!empty($data['caid'])) {
                    $q->orWhere('caid', $data['caid']);
                }
            });

            // Also check by eiin if provided to be more specific
            if (!empty($data['eiin'])) {
                $query->where('eiin', $data['eiin']);
            }

            return $query->first();
        }

        return null;
    }

    public function query()
    {
        return Teacher::on('db_read')->newQuery();
    }
}

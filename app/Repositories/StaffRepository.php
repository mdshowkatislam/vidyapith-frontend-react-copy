<?php

namespace App\Repositories;

use App\Models\Designation;
use App\Models\Institute;
use App\Models\Staff;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use App\Services\AttendanceSyncService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffRepository implements StaffRepositoryInterface
{
    protected $attendanceSyncService;

    public function __construct(AttendanceSyncService $attendanceSyncService)
    {
        $this->attendanceSyncService = $attendanceSyncService;
    }

    public function list()
    {
        return Staff::on('db_read')->get();
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
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

            $staff = new Staff;
            $staff->eiin = @$data['eiin'] ?? @$data['eiin_no'];  // ?? @$data['caid'];
            $staff->caid = @$data['caid'];
            $staff->emp_id = @$data['emp_id'];
            $staff->pdsid = @$data['pdsid'] ?? @$data['emp_uid'];
            $staff->name_en = @$data['name_en'] ?? @$data['fullname'] ?? @$data['employee_name'];
            $staff->name_bn = @$data['name_bn'] ?? @$data['fullname_bn'] ?? @$data['employee_name_bn'];
            $staff->fathers_name = @$data['fathers_name'] ?? @$data['fathersname'];
            $staff->mothers_name = @$data['mothers_name'] ?? @$data['mothersname'];
            $staff->email = @$data['email'] ?? @$customEmail;
            $staff->mobile_no = @$data['mobile_no'] ?? @$data['mobileno'] ?? @$data['mobile_number'];
            $staff->gender = @$data['gender'];
            $staff->date_of_birth = $birthday;
            $staff->institute_name = @$data['institute_name'] ?? @$data['institutename'];
            $staff->institute_type = @$data['institute_type'] ?? @$data['levelname'];
            $staff->institute_category = @$data['institute_category'] ?? @$data['institute_type_name'];
            $staff->index_number = @$data['index_number'] ?? @$data['indexnumber'] ?? @$data['index_no'];
            $staff->workstation_name = @$data['workstation_name'];
            $staff->branch_institute_name = @$data['branch_institute_name'] ?? @$data['institute_name'] ?? @$data['institutename'];
            $staff->branch_institute_category = @$data['branch_institute_category'] ?? @$data['institute_category'] ?? @$data['institute_type_name'];
            $staff->service_break_institute = @$data['service_break_institute'];

            // $staff->designation_id = @$data['designation_id'] ?? @$data['designationid'] ?? @$data['designation'] ?? 2;
            $staff->designation_id = @$data['designation'];
            $designation = Designation::where('uid', @$data['designation'])->first();
            $staff->designation = @$designation->designation_name;
            // $staff->designation = @$data['designation'] ?? @$data['designation_name'];
            $staff->division_id = @$data['division_id'] ?? @$data['divisionid'];
            $staff->district_id = @$data['district_id'] ?? @$data['districtid'];
            $staff->upazilla_id = @$data['upazilla_id'] ?? @$data['upazila_id'] ?? @$data['upazilaid'];
            $staff->upazilla_id = @$data['address'] ?? null;
            $staff->joining_year = date('Y', strtotime(@$data['joining_date']));
            $staff->mpo_code = @$data['mpo_code'];
            $staff->joining_date = $joining_date;
            $staff->last_working_date = $last_working_date;
            $staff->nid = @$data['nid'];
            $staff->staff_type = @$data['staff_type'];
            // $staff->access_type = @$data['access_type'];
            $staff->role = @$data['role'];
            $staff->ismpo = @$data['ismpo'];  // mpo or not
            $staff->isactive = @$data['isactive'] ?? 1;  // staff active or not
            $staff->data_source = @$data['data_source'];  // emis or bandbeis
            // $staff->staff_source = @$data['staff_source'];
            $staff->blood_group = @$data['blood_group'];
            $staff->emergency_contact = @$data['emergency_contact'];
            $staff->image = @$data['image'];
            $staff->signature = @$data['signature'];

            $staff->is_foreign = @$data['is_foreign'];
            $staff->country = @$data['country_uid'];
            $staff->state = @$data['state'];
            $staff->city = @$data['city'];
            $staff->zip_code = @$data['zip_code'];

            $staff->save();
            if (config('services.attendance.enabled')) {
                $this->attendanceSyncService->sync($staff, 'create');
            }
            DB::commit();

            return $staff;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug('Staff creation failed' . $e->getMessage(), [
                'staff_id' => 'id',
                'data' => $data,
                'trace_exception' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update($data, $id, $is_restore = false)
    {
          Log::info('GGG2');
        Log::info($data );
        Log::info($id );
        DB::beginTransaction();
        try {
            if (!empty($data['pdsid'])) {
                $customEmail = $data['pdsid'] . '@noipunno.gov.bd';
            } else {
                $customEmail = '';
            }

            if ($is_restore) {
                Staff::where('caid', $id)->orwhere('uid', $id)->onlyTrashed()->restore();
            }

            $staff = Staff::where('caid', $id)->orwhere('uid', $id)->first();

            $staff->name_en = @$data['name_en'] ?? @$data['fullname'] ?? @$data['employee_name'];
            // $staff->pdsid = @$data['pdsid'] ?? @$data['emp_uid'];
            // $staff->index_number = @$data['index_number'];
            // if(!empty(@$data['eiin'] ?? @$data['eiin_no'] ?? @$data['caid'])) {
            //     $staff->eiin = @$data['eiin'] ?? @$data['eiin_no'] ?? @$data['caid'];
            // }

            /** need to update Eiin */
            if (!empty(@$data['eiin'] ?? @$data['eiin_no'])) {
                $staff->eiin = @$data['eiin'] ?? @$data['eiin_no'];
            }

            if (empty($staff->caid) && !empty(@$data['caid'])) {
                $staff->caid = @$data['caid'];
            }

            if (empty($staff->pdsid) && !empty(@$data['pdsid'])) {
                $staff->pdsid = @$data['pdsid'];
            }
            if (empty($staff->index_number) && !empty(@$data['index_number'])) {
                $staff->index_number = @$data['index_number'];
            }

            #region

            // if (!empty(@$data['designation'] ?? @$data['designation_name'])) {
            //     $staff->designation = @$data['designation'] ?? @$data['designation_name'];
            // }
            // if (!empty(@$data['designation_id'] ?? @$data['designationid'] ?? @$data['designation'])) {
            //     $staff->designation_id = @$data['designation_id'] ?? @$data['designationid'] ?? @$data['designation'];
            // }

            // $designation = Designation::where('uid', @$data['designation'])->first();
            // $staff->designation = @$designation->designation_name;
            #endregion

            if (!empty(@$data['designation'])) {
                $staff->designation_id = @$data['designation'];
            }

            // if (!empty(@$data['designation_id'])) {
            //     $staff->designation_id = @$data['designation_id'];
            // }

            $designation = Designation::where('uid', @$data['designation'])->first();
            $staff->designation = @$designation->designation_name;
            $staff->emp_id = @$data['emp_id'];
            $staff->name_bn = @$data['name_bn'] ?? @$data['fullname_bn'] ?? @$data['employee_name_bn'];
            $staff->fathers_name = @$data['fathers_name'] ?? @$data['fathersname'];
            $staff->mothers_name = @$data['mothers_name'] ?? @$data['mothersname'];
            $staff->email = @$data['email'] ?? $customEmail;
            $staff->mobile_no = @$data['mobile_no'] ?? @$data['mobileno'] ?? @$data['mobile_number'];
            $staff->division_id = @$data['division_id'] ?? @$data['divisionid'];
            $staff->district_id = @$data['district_id'] ?? @$data['districtid'];
            $staff->upazilla_id = @$data['upazilla_id'] ?? @$data['upazila_id'] ?? @$data['upazilaid'];
            $staff->upazilla_id = @$data['address'] ?? null;
            $staff->gender = @$data['gender'];
            $staff->staff_type = @$data['staff_type'];
            $staff->blood_group = @$data['blood_group'];
            $staff->emergency_contact = @$data['emergency_contact'];
            $staff->joining_date = @$data['joining_date'];
            // $staff->access_type = @$data['access_type'];
            // $staff->role = @$data['role'];
            $staff->image = @$data['image'];
            $staff->signature = @$data['signature'];
            $staff->is_foreign = @$data['is_foreign'];
            $staff->country = @$data['country_uid'];
            $staff->state = @$data['state'];
            $staff->city = @$data['city'];
            $staff->zip_code = @$data['zip_code'];
            $staff->save();
            // Sync with attendance service
            if (config('services.attendance.enabled')) {
                $this->attendanceSyncService->sync($staff, 'update');
            }
            DB::commit();

            return $staff;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::debug('Staff update failed: ' . $e->getMessage(), [
                'staff_id' => $id,
                'data' => $data,
                'exception' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    public function getById($id)
    {
        return Staff::on('db_read')->where('uid', $id)->first();
    }

    public function getByEmpId($emp_id)
    {
        return Staff::on('db_read')->where('emp_id', $emp_id)->first();
    }

    public function getWithTrashedById($id)
    {
        return DB::table('staffs')->where('uid', $id)->first();
    }

    public function getWithTrashedByEmpId($emp_id)
    {
        return DB::table('staffs')->where('emp_id', $emp_id)->first();
    }

    public function getByEiinId($eiin, $is_not_paginate = null, $optimize = null, $search = null)
    {
        if ($optimize) {
            return Staff::on('db_read')->with('designations')->select('uid', 'pdsid', 'caid', 'emp_id', 'name_en', 'name_bn', 'index_number')->where('eiin', $eiin)->get();
        } else {
            if ($is_not_paginate) {
                return Staff::on('db_read')->with('designations')->select('uid', 'pdsid', 'caid', 'emp_id', 'name_en', 'name_bn', 'index_number', 'designation_id', 'mobile_no', 'eiin', 'division_id', 'district_id', 'upazilla_id', 'blood_group', 'address')->where('eiin', $eiin)->get();
            } else {
                return Staff::on('db_read')
                    ->with('designations')
                    ->select('uid', 'pdsid', 'caid', 'emp_id', 'name_en', 'name_bn', 'index_number', 'designation_id', 'mobile_no', 'eiin', 'division_id', 'district_id', 'upazilla_id', 'blood_group', 'address')
                    ->where('eiin', $eiin)
                    ->where(function ($query) use ($search) {
                        if ($search) {
                            $query
                                ->where('pdsid', 'like', '%' . $search . '%')
                                ->orWhere('caid', 'like', '%' . $search . '%')
                                ->orWhere('name_en', 'like', '%' . $search . '%')
                                ->orWhere('index_number', 'like', '%' . $search . '%')
                                ->orWhere('mobile_no', 'like', '%' . $search . '%');
                        }
                    })
                    ->paginate(200);
            }
        }
    }

    public function authAccountCreateStaff($data)
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
                ->union(DB::table('banbeis_staff')->select('eiin_no as eiin', 'institute_name')->where('eiin_no', $eiin))
                ->union(DB::table('emis_staff')->select('eiin', 'institutename as institute_name')->where('eiin', $eiin))
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
    //     $result = Staff::where('uid', $id)->first();
    //     $result->delete();
    //     return true;
    // }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $staff = Staff::where('uid', $id)->first();

            if (!$staff) {
                throw new \Exception('Staff not found');
            }

            // Get emp_id before deleting
            if ($staff->emp_id) {
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => 'Machine Unique Id Not Found'
                ];
            }

            // Delete from attendance service first
            if ($this->attendanceSyncService->isEnabled()) {
                $this->attendanceSyncService->deleteStaff($staff->emp_id);
            }

            // Delete from local database
            $staff->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Teacher deleted successfully'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete staff: ' . $e->getMessage());
            return false;
        }
    }

    public function staffsList($request)
    {
        $staffs = Staff::on('db_read');

        if (!empty($request->input('name'))) {
            $staffName = $request->input('name');
            $staffs = $staffs->where(function ($query) use ($staffName) {
                $query
                    ->where('name_en', 'like', "%{$staffName}%")
                    ->orWhere('name_bn', 'like', "%{$staffName}%");
            });
        }

        if (app('sso-auth')->user()->caid != '4010001') {
            if ((app('sso-auth')->user()->user_type_id == '5') && (empty(app('sso-auth')->user()->upazila_id))) {
                $totalInstituteArr = Institute::on('db_read')->where('is_foreign', 1)->pluck('eiin')->toArray();
                $staffs = $staffs->whereIn('eiin', $totalInstituteArr);
            } else if (!empty($request->upazila_id)) {
                $staffs = $staffs->where('upazilla_id', $request->upazila_id);
            }
        }

        if (!empty($request->input('phone'))) {
            $staffs = $staffs->where('mobile_no', $request->input('phone'));
        }
        if (!empty($request->input('pdsid'))) {
            $staffs = $staffs->where('pdsid', $request->input('pdsid'));
        }

        $total_staff = $staffs->count();
        // $perPage = 10; // Number of items per page
        $perPage = $request->limit ?? 10;  // Number of items per page
        $page = $request->page ?? 1;  // Current page number

        $offset = ($page - 1) * $perPage;

        $staffs = $staffs->skip($offset)->take($perPage)->get();

        return ['total_staff' => $total_staff, 'staffs' => $staffs];
    }

    public function query()
    {
        return Staff::on('db_read')->newQuery();
    }
}

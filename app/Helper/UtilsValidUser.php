<?php

namespace App\Helper;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\RolePermission\RolePermission;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Institute;


class UtilsValidUser {

    public static function getUser($data) {
        $role = @$data['user_role'] ?? RolePermission::$TEACHER;
        $user_eiin = @$data['user_eiin'];
        if ($data['user_user_type_id'] == 1) {
            $teacher = Teacher::
            where('caid', @$data['user_caid'])
            ->orwhere('pdsid', @$data['user_pdsid'])
            ->first();
            // $t_data = [
            //     'caid' => @$data['user_caid'],
            //     'pdsid' => @$data['user_pdsid'],
            //     'mobile_no' => @$data['user_phone_no'],
            //     'name_en' => @$data['user_name'] ?? 'Bidyapith',
            //     'email' => @$data['user_email'] ?? @$data['user_pdsid'] ?? @$data['user_caid'] .'@noipunno.gov.bd',
            //     'role' => @$data['user_role'] ?? RolePermission::$TEACHER,
            // ];
            // if (!$teacher) {
            //     Teacher::create($t_data);
            // } else {
            //     $teacher->update($t_data);
            // }
            $role = @$teacher->role ?? @$data['user_role'];
            $user_eiin = @$teacher->eiin ?? @$data['user_eiin'];

        } elseif ($data['user_user_type_id'] == 2) {
            $student = Student::where('caid', @$data['user_caid'])
            ->orwhere('suid', @$data['user_suid'])
            ->first();
            if (!$student) {
                Student::create([
                    'caid' => @$data['user_caid'],
                    'suid' => @$data['user_suid'],
                    'student_mobile_no' => @$data['user_phone_no'],
                    'student_name_en' => @$data['user_name'],
                    'email' => @$data['user_email'] ?? 'admin@admin.com',
                ]);
            }
            $role = $student->role ?? RolePermission::$STUDENT;
        } elseif ($data['user_user_type_id'] == 3) {
            $institute = Institute::where('caid', @$data['user_caid'])
            ->orwhere('eiin', @$data['user_eiin'])
            ->first();
            
            if (!$institute) {
                Institute::create([
                    'caid' => @$data['user_caid'],
                    'eiin' => @$data['user_eiin'],
                    'phone' => @$data['user_phone_no'],
                    'institute_name' => @$data['user_name'],
                    'email' => @$data['user_email'] ?? 'admin@admin.com',
                ]);
            }
            $role = $institute->role ?? RolePermission::$INSTITUTE;
        }

        try {
            $user_type = @$data['user_user_type_id'];
            // if($user_type != 4 ) {
                $userInfo = UtilsUserInfo::userInfo();
                if($userInfo->status) {
                    $permissions = $userInfo->data['permission_access_modules'];
                    $permission = array_column($permissions, 'id');
                    if(!in_array(3, $permission) === true && !isApi()) {
                        return redirect()->to(config('app.auth_url'));
                    }
                } else {
                    return redirect()->to(config('app.auth_url'));
                }
            // }
        }  catch (\Exception $e) {
            return redirect()->to(config('app.auth_url'));
        }
        
        $user = User::where('caid', @$data['user_caid'])->first();
        if (!$user) {
             $u_data = [
                'caid' => @$data['user_caid'],
                'eiin' => @$data['user_eiin'],
                'pdsid' => @$data['user_pdsid'],
                'suid' => @$data['user_suid'],
                'phone_no' => @$data['user_phone_no'],
                'role' => $role ?? RolePermission::$INSTITUTE,
                'name' => @$data['user_name'] ?? 'Bidyapith',
                'email' => @$data['user_email'] ??  @$data['user_caid'].'@noipunno.com',
                'user_type_id' => @$data['user_user_type_id'] ?? 4,
                'upazila_id' => @$data['user_upazila_id'] ?? 4,
                'district_id' => @$data['user_district_id'] ?? 4,
                'division_id' => @$data['user_division_id'] ?? 4,
                'board_id' => @$data['user_board_id'] ?? 4,
                'password' => Hash::make(@$data['user_caid']),
                'remember_token' => 'auto',
            ];
            $user = User::create($u_data);
        }
        return $user;
    }
}

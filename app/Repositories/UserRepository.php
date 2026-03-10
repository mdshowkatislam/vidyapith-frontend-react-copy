<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function create($data)
    {
        $u_data = [
            'caid' => @$data['caid'],
            'eiin' => @$data['eiin'],
            'pdsid' => @$data['pdsid'],
            'suid' => @$data['suid'],
            'phone_no' => @$data['mobile_no'],
            'role' => @$data['role'] ?? 'institute',
            'name' => @$data['name_en'] ?? 'Bidyapith',
            'email' => @$data['email'] ?? (@$data['pdsid'] ? $data['pdsid'] . '@noipunno.gov.bd' : ''),
            'user_type_id' => @$data['user_type_id'] ?? 4,
            'password' => Hash::make(@$data['caid']),
        ];
        return User::create($u_data);
    }
    public function update($id, $data)
    {
        /**
         * user update
         * we change this logic because teacher will be
         * switch one school to another then we need to update EIIN number accourdly
         * which priviously need to auth verification
         * step are
         * 1. not need to send EIIN number to auth
         * 2. it will be change teacher module
         *
         */
        $user = User::where('caid', $id)->first();
        if ($user) {
            $u_data = [
                'name' => @$data['name_en'] ?? @$data['institute_name'],
                'eiin' => @$data['eiin'],
                'phone_no' => @$data['mobile_no'] ?? @$data['phone'],
                'email' => @$data['email'] ?? (@$data['pdsid'] ? $data['pdsid'] . '@noipunno.gov.bd' : ''),
            ];
            return $user->update($u_data);
        } else {
            return $this->create($data);
        }
    }

    public function getByCaid($caid)
    {
        return User::on('db_read')->where('caid', $caid)->first();
    }
}

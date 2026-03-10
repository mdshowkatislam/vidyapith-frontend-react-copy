<?php

namespace App\Container;

use Session;

use App\RolePermission\RolePermission;

use App\Helper\UtilsCookie;


class Role
{
    public function setRole($value)
    {
      return Session::put('user_role', $value);
    }

    public function getRole()
    {
        return Session::get('user_role') ?? app('sso-auth')->user()->role;
    }

    private static function currentRole()
    {
        return Session::get('user_role') ?? app('sso-auth')->user()->role;
    }

    public function role()
    {
        return RolePermission::Permission(app('sso-auth')->user()->role);
    }

    public function roles()
    {
        $roles = RolePermission::Permission(app('sso-auth')->user()->role)->roles;
        return json_decode(json_encode($roles));
    }

    public function permissions()
    {
        return RolePermission::Permission(Role::currentRole())->permissions;
    }
}
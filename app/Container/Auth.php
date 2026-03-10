<?php

namespace App\Container;

use App\Helper\UtilsCookie;

class Auth
{
    public function user()
    {
        return  @auth()->user() ?? AuthInstance::getInstance()->getUser();
    }

    public function setUser($user)
    {
        return AuthInstance::getInstance()->setUser($user);
    }

    public function logout()
    {
        return UtilsCookie::deleteCookie();
    }
}

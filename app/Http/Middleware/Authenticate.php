<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

use App\Helper\UtilsCookie;
use App\Helper\UtilsPassportToken;
use App\Helper\UtilsValidUser;

use App\Models\User;

use Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $decoded_token = UtilsPassportToken::dirtyDecode(UtilsCookie::getCookie());
        if ($decoded_token['valid']) {
            $timeDifference = strtotime(date($decoded_token['expires_at'])) - strtotime(now());
            $user =  UtilsValidUser::getUser($decoded_token);

            if ($timeDifference > 0 && !empty($user->caid)) {    
                auth()->guard()->login($user);
                return route('home');  
            } else {
                return route('login');
            }  return $request->expectsJson() ? null : route('login');
            
        } else {
            return $request->expectsJson() ? null : route('login');
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

use App\Helper\UtilsCookie;
use App\Helper\UtilsPassportToken;
use App\Helper\UtilsValidUser;

use Exception;

class AuthSSOGates
{
    public function handle($request, Closure $next)
    {
        // dd(app('sso-auth')->user()->caid,@$decoded_token['user_caid']);
        try {
            $hostUrl = $request->getHttpHost();
            if (!str_contains($hostUrl, 'localhost:8000')) {
                $decoded_token = UtilsPassportToken::dirtyDecode(UtilsCookie::getCookie());
                if ($decoded_token['valid']) {
                    $timeDifference = strtotime(date($decoded_token['expires_at'])) - strtotime(now());
                    if ($timeDifference < 0 || @app('sso-auth')->user()->caid != @$decoded_token['user_caid']) {
                        // if(app('sso-auth')->user()->caid){
                        //         $user =  UtilsValidUser::getUser($decoded_token);   
                        //         if(@$user->caid){
                        //             auth()->guard()->login($user);
                        //             return route('home');
                        //         }else{
                        //             return $user;
                        //         }
                        // }else{
                        //     return redirect()->route('logout');
                        // }
                        return redirect()->route('logout');
                    } 
                } else {
                    return redirect()->route('logout');
                }
            }
            
        } catch (Exception $exc) {
            return redirect()->route('logout');
        }
        return $next($request);
    }
}


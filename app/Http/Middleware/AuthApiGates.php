<?php

namespace App\Http\Middleware;

use Closure;

use App\Helper\UtilsCookie;
use App\Helper\UtilsPassportToken;
use App\Helper\UtilsValidUser;
use App\Models\User;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthApiGates
{
    use ApiResponser;
    public function handle($request, Closure $next)
    {
     
        // quick debug: record incoming request info and whether a bearer token was present
        // Log::debug('AuthApiGates incoming', [
        //     'uri' => $request->getRequestUri(),
        //     'method' => $request->method(),
        //     'headers' => $request->headers->all(),
        //     'bearer_present' => $request->bearerToken() ? true : false,
        // ]);

        try {
            $decoded_token = UtilsPassportToken::dirtyDecode($request->bearerToken());
            // dd($decoded_token);
          
            if ($decoded_token['valid']) {
                $timeDifference = strtotime(date($decoded_token['expires_at'])) - strtotime(now());
                if ($timeDifference < 0) {
                  
                    return $this->errorResponse('Unauthenticated', 401);
                } else {
                    // $user =  UtilsValidUser::getUser($decoded_token);
                    $user = new User();
                    $user->caid = $decoded_token['user_caid'];
                    $user->eiin = $decoded_token['user_eiin'];
                    $user->pdsid = $decoded_token['user_pdsid'];
                    $user->phone_no = $decoded_token['user_phone_no'];
                    $user->name = $decoded_token['user_name'];
                    $user->email = $decoded_token['user_email'];
                    $user->user_type_id =$decoded_token['user_user_type_id'];
                    $user->board_id =$decoded_token['user_board_id'];
                    $user->upazila_id =$decoded_token['user_upazila_id'];
                    $user->district_id =$decoded_token['user_district_id'];
                    $user->division_id =$decoded_token['user_division_id'];
                    app('sso-auth')->setUser(user: $user);
                }
            } else {
  
                return $this->errorResponse('Unauthenticated', 401);
            }
        } catch (Exception $exc) {
            Log::error('AuthApiGates exception', ['message'=>$exc->getMessage(), 'trace'=>$exc->getTraceAsString()]);
            return $this->errorResponse('Unauthenticated', 401);
        }

        return $next($request);
    }
}




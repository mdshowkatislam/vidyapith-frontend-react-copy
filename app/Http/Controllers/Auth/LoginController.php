<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Helper\UtilsCookie;
use App\Helper\UtilsPassportToken;
use App\Helper\UtilsValidUser;

use App\Models\User;
use Exception;

use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
           \Log::info('login');
        try {
            $auth_url = config(key: 'app.auth_url');
            $redirect_uri = config('app.auth_redirect_uri');
            return Redirect::to("${auth_url}?redirect_uri=${redirect_uri}");
        } catch (Exception $exc) {
            return view('errors/404');
        }
    }

    public function handleCallback(Request $request)
    {
        \Log::info('handleCallback');
        try {
            $decoded_token = UtilsPassportToken::dirtyDecode($request->token);
            
            if ($decoded_token['valid']) {
                $timeDifference = strtotime(date($decoded_token['expires_at'])) - strtotime(now());
                $user =  UtilsValidUser::getUser($decoded_token);
                
                if ($timeDifference > 0 && !empty($user->caid)) {
                    auth()->guard()->login($user);
                    return redirect(route(name: 'home'));
                } else {
                    return redirect(route('login'));
                }
            } else {
                return redirect(route('login'));
            }
        } catch (Exception $exc) {
            return redirect(route('login'));
        }
    }

    protected function logout(Request $request)
    {
        auth()->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        app('sso-auth')->logout();
        return redirect('/');
    }
}

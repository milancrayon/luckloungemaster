<?php

namespace App\Http\Controllers\Master\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'master';
    protected $mastername;

    public function __construct()
    {
        parent::__construct();
        $this->mastername = $this->findUsername();
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $pageTitle = "Master Login";
        return view('master.auth.login', compact('pageTitle'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth()->guard('master');
    }

    public function username()
    {
        return 'mastername';
    }

    public function login(Request $request)
    {

        $this->validateLogin($request);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard('master')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect($this->redirectTo);
    }


    public function findUsername()
    {
        $login = request()->input('mastername');
        print_r($login);
        exit();
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mastername';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }
}

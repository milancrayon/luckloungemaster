<?php

namespace App\Http\Controllers\Master\Auth;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\MasterPasswordReset;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{

    public function showLinkRequestForm()
    {
        $pageTitle = 'Account Recovery';
        return view('master.auth.passwords.email', compact('pageTitle'));
    }

    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $master = Master::where('email', $request->email)->first();
        if (!$master) {
            $notify[] = ['error', 'No master account found with this email'];
            return back()->withNotify($notify);
        }

        $code                           = verificationCode(6);
        $masterPasswordReset             = new MasterPasswordReset();
        $masterPasswordReset->email      = $master->email;
        $masterPasswordReset->token      = $code;
        $masterPasswordReset->created_at = Carbon::now();
        $masterPasswordReset->save();

        $masterIpInfo  = getIpInfo();
        $masterBrowser = osBrowser();
        notify($master, 'PASS_RESET_CODE', [
            'code'             => $code,
            'operating_system' => $masterBrowser['os_platform'],
            'browser'          => $masterBrowser['browser'],
            'ip'               => $masterIpInfo['ip'],
            'time'             => $masterIpInfo['time'],
        ], ['email'], false);

        $email = $master->email;
        session()->put('pass_res_mail', $email);

        return to_route('master.password.code.verify');
    }

    public function codeVerify()
    {
        $pageTitle = 'Verify Code';
        $email     = session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error', 'Oops! session expired'];
            return to_route('master.password.reset')->withNotify($notify);
        }
        return view('master.auth.passwords.code_verify', compact('pageTitle', 'email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required']);
        $masterPasswordReset = MasterPasswordReset::where('email', session()->get('pass_res_mail'))->first();

        if ($masterPasswordReset->token != $request->code) {
            $notify[] = ['error', 'Verification code dose\t match'];
            return to_route('master.login')->withNotify($notify);
        }

        $notify[] = ['success', 'You can change your password'];
        $code     = str_replace(' ', '', $request->code);
        return to_route('master.password.reset.form', $code)->withNotify($notify);
    }
}

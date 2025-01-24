<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Game;
use App\Models\GameLog;
use App\Models\MastersTransaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MasterController extends Controller
{

    public function dashboard()
    {
        $pageTitle = 'Dashboard';

        // User Info
        $widget['total_users']             = User::count();
        $widget['verified_users']          = User::active()->count();
        $widget['email_unverified_users']  = User::emailUnverified()->count();
        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();

        $widget['total_games']         = Game::count();
        $widget['total_played']        = GameLog::sum('invest');
        $widget['total_invest_amount'] = GameLog::sum('invest');
        $widget['total_win_amount']    = GameLog::win()->sum('win_amo');
        $widget['total_loss_amount']   = GameLog::loss()->sum('invest');
        $widget['total_profit']        = $widget['total_invest_amount'] - $widget['total_win_amount'];

        // user Browsing, Country, Operating Log
        $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDay(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);

        $deposit['total_deposit_amount']   = Deposit::successful()->sum('amount');
        $deposit['total_deposit_pending']  = Deposit::pending()->count();
        $deposit['total_deposit_rejected'] = Deposit::rejected()->count();
        $deposit['total_deposit_charge']   = Deposit::successful()->sum('charge');

        $withdrawals['total_withdraw_amount']   = Withdrawal::approved()->sum('amount');
        $withdrawals['total_withdraw_pending']  = Withdrawal::pending()->count();
        $withdrawals['total_withdraw_rejected'] = Withdrawal::rejected()->count();
        $withdrawals['total_withdraw_charge']   = Withdrawal::approved()->sum('charge');

        return view('master.dashboard', compact('pageTitle', 'widget', 'chart', 'deposit', 'withdrawals'));
    }

    public function profile()
    {
        $pageTitle = 'Profile';
        $master     = auth('master')->user();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('master.profile', compact('pageTitle', 'master', 'countries'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth('master')->user();
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray   = (array)$countryData;
        $countries      = implode(',', array_keys($countryArray));

        $countryCode    = $request->country;
        $country        = $countryData->$countryCode->country;
        $dialCode       = $countryData->$countryCode->dial_code;
        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:40',
            'country' => 'required|in:' . $countries,
        ]);

        $exists = User::where('mobile', $request->mobile)->where('dial_code', $dialCode)->where('id', '!=', $user->id)->exists();
        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $user->mobile = $request->mobile;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;

        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->country_name = @$country;
        $user->dial_code = $dialCode;
        $user->country_code = $countryCode;
        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('master.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $master     = auth('master')->user();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('master.password', compact('pageTitle', 'master', 'countries'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|min:5|confirmed',
        ]);

        $user = auth('master')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('master.password')->withNotify($notify);
    }

    public function transaction()
    {
        $pageTitle = 'Transaction Logs';

        $remarks = MastersTransaction::distinct('remark')->orderBy('remark')->get('remark');
        $user = auth('master')->user();
        $masterId = $user->id;
        $transactions = MastersTransaction::searchable(['trx', 'master:mastername'])->filter(['trx_type', 'remark'])->dateFilter()->orderBy('id', 'desc')->with('master');
        if ($masterId) {
            $transactions = $transactions->where('master_id', $masterId);
        }
        $transactions = $transactions->paginate(getPaginate());

        return view('master.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }
}

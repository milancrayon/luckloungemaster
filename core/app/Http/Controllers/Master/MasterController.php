<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Game;
use App\Models\GameLog;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\Withdrawal;
use Carbon\Carbon;


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
}

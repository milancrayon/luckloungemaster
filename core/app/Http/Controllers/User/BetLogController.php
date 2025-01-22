<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Bet; 


class BetLogController extends Controller {
    protected $pageTitle;

    public function index($type = null) {
        $pageTitle = 'All Bets';
        if ($type) {
            try {
                $bets      = Bet::$type();
                $pageTitle = ucfirst($type) . ' ' . 'Bets';
            } catch (\Exception $e) {
                abort(404);
            }
        } else {
            $bets = Bet::query();
        }
        $bets = $bets->where('user_id', auth()->id())->searchable(['bet_number'])->with(['bets' => function ($query) {
            $query->relationalData();
        }])->orderBy('id', 'desc')->paginate(getPaginate());


        $user                       = auth()->user();
        $widget['totalBet']         = Bet::where('user_id', $user->id)->count();
        $widget['pendingBet']       = Bet::where('user_id', $user->id)->pending()->count();
        $widget['wonBet']           = Bet::where('user_id', $user->id)->win()->count();
        $widget['loseBet']          = Bet::where('user_id', $user->id)->lose()->count();
        $widget['refundedBet']      = Bet::where('user_id', $user->id)->refunded()->count();
        $report['bet_return_amount'] = collect([]);
        $report['bet_stake_amount']  = collect([]);
        $report['bet_dates']         = collect([]);

        $startDate = now()->startOfDay();
        $endDate   = now()->endOfDay();

        // if ($request->date) {
        //     $date      = explode('-', $request->date);
        //     $startDate = Carbon::parse($date[0])->startOfDay();
        //     $endDate   = Carbon::parse($date[1])->endOfDay();
        // }

        $totalBets = Bet::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("SUM(CASE WHEN status = " . Status::BET_WIN . " AND amount_returned = " . Status::NO . " THEN return_amount ELSE 0 END) as return_amount")
            ->selectRaw("SUM(stake_amount) as stake_amount")
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m-%d') as dates")
            ->orderBy('created_at')
            ->groupBy('dates')
            ->get();

        $totalBets->map(function ($betData) use ($report) {
            $report['bet_dates']->push($betData->dates);
            $report['bet_return_amount']->push(getAmount($betData->return_amount));
            $report['bet_stake_amount']->push(getAmount($betData->stake_amount));
        });

        return view('Template::user.bet.index', compact('pageTitle', 'bets','widget', 'report', 'user'));
    }
}

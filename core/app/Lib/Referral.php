<?php

namespace App\Lib;

use App\Models\CommissionLog;
use App\Models\Referral as RModel;
use App\Models\Transaction;
use App\Models\User;
class Referral {
    public static function levelCommission($user, $amount, $trx, $commissionType = '') {
        $tempUser = $user;
        $i        = 1;
        $level    = RModel::where('commission_type', $commissionType)->count();

      
        while ($i <= $level) {
            $referer    =   User::find($tempUser->ref_by);
            $commission = RModel::where('commission_type', $commissionType)->where('level', $i)->first();
            
            if (!$referer || !$commission) {
                break;
            }

            $commissionAmount = ($amount * $commission->percent) / 100;
            $referer->balance += $commissionAmount;
            $referer->save();
          
            $_commisionType = "Bet place";
            if($commissionType == "win"){
                $_commisionType = "Bet Winning";
            }

            $transactions[] = [
                'user_id'      => $referer->id,
                'amount'       => getAmount($commissionAmount, 8),
                'post_balance' => $referer->balance,
                'trx_type'     => '+',
                'details'      => $_commisionType. ' commission from : ' . $user->username,
                'remark'       => 'referral',
                'trx'          => $trx,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

           

            $commissionLog[] = [
                'user_id'             => $referer->id,
                'who'           => $user->id,
                'level'             => $i,
                'amount' => $commissionAmount,
                'main_amo'           => $amount,
                'title'             => $_commisionType. ' commission from : ' . $user->username,
                'trx'               => $trx,
                'created_at'        => now(),
                'updated_at'        => now(),
                'post_balance'      => $referer->balance,
                'type'              => $commissionType,
                'percent'           => $commission->percent,
            ];

            notify($referer, 'REFERRAL_COMMISSION', [
                'username'           => $referer->username,
                'amount'             => $commissionAmount,
                'trx'                => $trx,
                'commission_type'    => $commissionType,
                'level'              => $i,
                'commission_percent' => $commission->percent,
                'referral_user'      => $user->username,
            ]);

            $tempUser = $referer;
            $i++;
        }

        if (isset($transactions)) {
            Transaction::insert($transactions);
        }

        if (isset($commissionLog)) {
            CommissionLog::insert($commissionLog);
        }
    }
}

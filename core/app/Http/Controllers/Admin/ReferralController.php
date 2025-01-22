<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;
use App\Constants\Status;

class ReferralController extends Controller {
    public function index() {
        $pageTitle = 'Manage Referral';
        $referrals = Referral::get();
        return view('admin.referral.index', compact('pageTitle', 'referrals'));
    }

    public function store(Request $request) {
        $request->validate([
            'level'           => 'required|array|min:1',
            'level.*'         => 'required|integer|min:1',
            'percent'         => 'required|array|min:1',
            'percent.*'       => 'required|numeric|gt:0|regex:/^\d+(\.\d{1,2})?$/',
            'commission_type' => 'required|in:deposit,bet,win',
        ], [
            'level.required'     => 'Minimum one level field is required',
            'level.*.required'   => 'Minimum one level value is required',
            'level.*.integer'    => 'Provide integer number as level',
            'level.*.min'        => 'Level should be grater than 0',
            'percent.required'   => 'Minimum one percentage field is required',
            'percent.*.required' => 'Minimum one percentage value is required',
            'percent.*.integer'  => 'Provide integer number as percentage',
            'percent.*.min'      => 'Percentage should be grater than 0',
        ]);

        Referral::where('commission_type', $request->commission_type)->delete();

        for ($i = 0; $i < count($request->level); $i++) {
            $referral                  = new Referral();
            $referral->level           = $request->level[$i];
            $referral->percent         = $request->percent[$i];
            $referral->commission_type = $request->commission_type;
            $referral->save();
        }

        $notify[] = ['success', 'Referral setting stored successfully'];
        return back()->withNotify($notify);
    }

    // public function status($key) {
    //     $general     = gs();
    //     $general->dc = $key;
    //     $general->save();

    //     $notify[] = ['success', 'Referral commission status updated successfully'];
    //     return back()->withNotify($notify);
    // }

    public function updateStatus($type) {
        $general = gs();

        if (@$general->$type == Status::ENABLE) {
            @$general->$type = Status::DISABLE;
            $general->save();
        } else if (@$general->$type == Status::DISABLE) {
            @$general->$type = Status::ENABLE;
            $general->save();
        } else {
            $notify[] = ['error', 'Something Wrong'];
            return back()->withNotify($notify);
        }

        $notify[] = ['success', 'Referral setting stored successfully'];
        return back()->withNotify($notify);
    }
}

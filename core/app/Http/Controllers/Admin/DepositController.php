<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use App\Models\Form;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\User;
use Illuminate\Http\Request;
use Razorpay\Api\FundAccount;

class DepositController extends Controller
{
    public function pending($userId = null)
    {
        $pageTitle = 'Pending Deposits';
        $deposits = $this->depositData('pending', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function approved($userId = null)
    {
        $pageTitle = 'Approved Deposits';
        $deposits = $this->depositData('approved', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function successful($userId = null)
    {
        $pageTitle = 'Successful Deposits';
        $deposits = $this->depositData('successful', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function rejected($userId = null)
    {
        $pageTitle = 'Rejected Deposits';
        $deposits = $this->depositData('rejected', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function initiated($userId = null)
    {
        $pageTitle = 'Initiated Deposits';
        $deposits = $this->depositData('initiated', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function deposit($userId = null)
    {
        $pageTitle = 'Deposit History';
        $depositData = $this->depositData($scope = null, $summary = true, userId: $userId);
        $deposits = $depositData['data'];
        $summary = $depositData['summary'];
        $successful = $summary['successful'];
        $pending = $summary['pending'];
        $rejected = $summary['rejected'];
        $initiated = $summary['initiated'];
        return view('admin.deposit.log', compact('pageTitle', 'deposits', 'successful', 'pending', 'rejected', 'initiated'));
    }

    protected function depositData($scope = null, $summary = false, $userId = null)
    {
        if ($scope) {
            $deposits = Deposit::$scope()->with(['user', 'gateway']);
        } else {
            $deposits = Deposit::with(['user', 'gateway']);
        }

        if ($userId) {
            $deposits = $deposits->where('user_id', $userId);
        }

        $deposits = $deposits->searchable(['trx', 'user:username'])->dateFilter();

        $request = request();

        if ($request->method) {
            if ($request->method != Status::GOOGLE_PAY) {
                $method = Gateway::where('alias', $request->method)->firstOrFail();
                $deposits = $deposits->where('method_code', $method->code);
            } else {
                $deposits = $deposits->where('method_code', Status::GOOGLE_PAY);
            }
        }

        if (!$summary) {
            return $deposits->orderBy('id', 'desc')->paginate(getPaginate());
        } else {
            $successful = clone $deposits;
            $pending = clone $deposits;
            $rejected = clone $deposits;
            $initiated = clone $deposits;

            $successfulSummary = $successful->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
            $pendingSummary = $pending->where('status', Status::PAYMENT_PENDING)->sum('amount');
            $rejectedSummary = $rejected->where('status', Status::PAYMENT_REJECT)->sum('amount');
            $initiatedSummary = $initiated->where('status', Status::PAYMENT_INITIATE)->sum('amount');

            return [
                'data' => $deposits->orderBy('id', 'desc')->paginate(getPaginate()),
                'summary' => [
                    'successful' => $successfulSummary,
                    'pending' => $pendingSummary,
                    'rejected' => $rejectedSummary,
                    'initiated' => $initiatedSummary,
                ],
            ];
        }
    }

    public function details($id)
    {
        $deposit = Deposit::where('id', $id)->with(['user', 'gateway'])->firstOrFail();
        $pageTitle = $deposit->user->username . ' requested ' . showAmount($deposit->amount);
        $details = ($deposit->detail != null) ? json_encode($deposit->detail) : null;
        return view('admin.deposit.detail', compact('pageTitle', 'deposit', 'details'));
    }

    public function approve($id)
    {
        $deposit = Deposit::where('id', $id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        PaymentController::userDataUpdate($deposit, true);

        $notify[] = ['success', 'Deposit request approved successfully'];

        return to_route('admin.deposit.pending')->withNotify($notify);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'message' => 'required|string|max:255',
        ]);
        $deposit = Deposit::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        $deposit->admin_feedback = $request->message;
        $deposit->status = Status::PAYMENT_REJECT;
        $deposit->save();

        notify($deposit->user, 'DEPOSIT_REJECT', [
            'method_name' => $deposit->methodName(),
            'method_currency' => $deposit->method_currency,
            'method_amount' => showAmount($deposit->final_amount, currencyFormat: false),
            'amount' => showAmount($deposit->amount, currencyFormat: false),
            'charge' => showAmount($deposit->charge, currencyFormat: false),
            'rate' => showAmount($deposit->rate, currencyFormat: false),
            'trx' => $deposit->trx,
            'rejection_message' => $request->message,
        ]);

        $notify[] = ['success', 'Deposit request rejected successfully'];
        return to_route('admin.deposit.pending')->withNotify($notify);

    }

    public function create($userId = null)
    {
        $user = User::findOrFail($userId);
        $pageTitle = 'Create Deposit For ' . $user->username;
        $users = User::active();
        $gateways = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->select(['name', 'alias', 'extra', 'id', 'form_id'])->where('status', Status::ENABLE)->where('method_code', ">", 999);
        })->with('method')->orderby('id')->get();
        $formdatas = [];
        foreach ($gateways as $key => $value) {
            $_form = Form::where('id', $value->method->form_id)->first();
            $_formData = $_form->form_data ?? [];
            $formdatas[] = $_formData;
        }
        return view('admin.deposit.create', compact('pageTitle', 'gateways', 'users', 'userId', 'formdatas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'user_id' => 'required',
            'gateway' => 'required',
            'currency' => 'required',
            'option_identifier' => 'required',
            'transaction_id' => 'required',
        ]);

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable = $request->amount + $charge;
        $finalAmount = $payable * $gate->rate;



        $data = new Deposit();
        $data->user_id = $request->user_id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $request->amount;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amount = $finalAmount;
        $data->btc_amount = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->success_url = urlPath('user.deposit.history');
        $data->failed_url = urlPath('user.deposit.history');
        $data->status = Status::PAYMENT_PENDING;

        if (isset($request->option_identifier)) {
            $userData[] = [
                'name' => 'UPI Id',
                'type' => 'hidden',
                'value' => $request->option_identifier,
            ];
            $userData[] = [
                'name' => 'Transaction Id',
                'type' => 'text',
                'value' => $request->transaction_id,
            ];
            $data->detail = $userData;
        }
        $data->save();
        $notify[] = ['success', 'You have deposit request has been taken'];
        return to_route('admin.deposit.pending')->withNotify($notify);
    }

    public function validateidentifier(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
        ]);

        if (isset($request->code)) {
            $deposit = Gateway::select('extra')->where('code', '>=', 999)->where('code', '!=', $request->code)->where('extra', 'like', '%' . $request->identifier . '%')->get();
        } else {
            $deposit = Gateway::where('code', '>=', 999)->where('extra', 'like', '%' . $request->identifier . '%')->get();
        }

        $status = true;
        if (sizeof($deposit) > 0) {
            foreach ($deposit as $d) {
                $e_data = json_decode($d->extra);
                if ($e_data) {
                    $identifiers = $e_data->identifier;
                    $resl_ = array_search($request->identifier, $identifiers);
                    if ($resl_) {
                        $status = false;
                    }
                }
            }
        }
        $jsonresponse = ['valid' => $status, 'data' => $deposit];

        return response()->json($jsonresponse);
    }
}

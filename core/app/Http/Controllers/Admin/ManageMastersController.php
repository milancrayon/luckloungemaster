<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\MastersTransaction;
use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class ManageMastersController extends Controller
{

    public function allMasters()
    {
        $pageTitle = 'All Masters';
        $masters = $this->masterData();
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }

    public function activeMasters()
    {
        $pageTitle = 'Active Masters';
        $masters = $this->masterData('active');
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }

    public function bannedMasters()
    {
        $pageTitle = 'Banned Masters';
        $masters = $this->masterData('banned');
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }

    public function emailUnverifiedMasters()
    {
        $pageTitle = 'Email Unverified Masters';
        $masters = $this->masterData('emailUnverified');
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }

    public function kycUnverifiedMasters()
    {
        $pageTitle = 'KYC Unverified Masters';
        $masters = $this->masterData('kycUnverified');
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }

    public function kycPendingMasters()
    {
        $pageTitle = 'KYC Unverified Masters';
        $masters = $this->masterData('kycPending');
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }

    public function emailVerifiedMasters()
    {
        $pageTitle = 'Email Verified Masters';
        $masters = $this->masterData('emailVerified');
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }

    public function mobileUnverifiedMasters()
    {
        $pageTitle = 'Mobile Unverified Masters';
        $masters = $this->masterData('mobileUnverified');
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }


    public function mobileVerifiedMasters()
    {
        $pageTitle = 'Mobile Verified Masters';
        $masters = $this->masterData('mobileVerified');
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }

    public function mastersWithBalance()
    {
        $pageTitle = 'Masters with Balance';
        $masters = $this->masterData('withBalance');
        return view('admin.masters.list', compact('pageTitle', 'masters'));
    }

    protected function masterData($scope = null)
    {
        if ($scope) {
            $masters = Master::$scope();
        } else {
            $masters = Master::query();
        }
        return $masters->searchable(['mastername', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id)
    {
        $master = Master::findOrFail($id);
        $pageTitle = 'Master Detail - ' . $master->mastername;
        $totalTransaction = MastersTransaction::where('master_id', $master->id)->count();

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.masters.detail', compact('pageTitle', 'master', 'totalTransaction', 'countries'));
    }

    public function addMasters()
    {
        $pageTitle = 'Add Master Detail';
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.masters.add', compact('pageTitle', 'countries'));
    }

    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $master = Master::findOrFail($id);
        return view('admin.masters.kyc_detail', compact('pageTitle', 'master'));
    }

    public function kycApprove($id)
    {
        $master = Master::findOrFail($id);
        $master->kv = Status::KYC_VERIFIED;
        $master->save();

        notify($master, 'KYC_APPROVE', []);

        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('admin.masters.kyc.pending')->withNotify($notify);
    }

    public function kycReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required'
        ]);
        $master = Master::findOrFail($id);
        $master->kv = Status::KYC_UNVERIFIED;
        $master->kyc_rejection_reason = $request->reason;
        $master->save();

        notify($master, 'KYC_REJECT', [
            'reason' => $request->reason
        ]);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('admin.masters.kyc.pending')->withNotify($notify);
    }


    public function update(Request $request, $id)
    {
        $master = Master::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray   = (array)$countryData;
        $countries      = implode(',', array_keys($countryArray));

        $countryCode    = $request->country;
        $country        = $countryData->$countryCode->country;
        $dialCode       = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:masters,email,' . $master->id,
            'mobile' => 'required|string|max:40',
            'country' => 'required|in:' . $countries,
        ]);

        $exists = Master::where('mobile', $request->mobile)->where('dial_code', $dialCode)->where('id', '!=', $master->id)->exists();
        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $master->mobile = $request->mobile;
        $master->firstname = $request->firstname;
        $master->lastname = $request->lastname;
        $master->email = $request->email;

        $master->address = $request->address;
        $master->city = $request->city;
        $master->state = $request->state;
        $master->zip = $request->zip;
        $master->country_name = @$country;
        $master->dial_code = $dialCode;
        $master->country_code = $countryCode;
        $master->exposure = $request->exposure;
        $master->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $master->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $master->ts = $request->ts ? Status::ENABLE : Status::DISABLE;
        if (!$request->kv) {
            $master->kv = Status::KYC_UNVERIFIED;
            if ($master->kyc_data) {
                foreach ($master->kyc_data as $kycData) {
                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
                    }
                }
            }
            $master->kyc_data = null;
        } else {
            $master->kv = Status::KYC_VERIFIED;
        }
        $master->save();

        $notify[] = ['success', 'Master details updated successfully'];
        return back()->withNotify($notify);
    }


    public function store(Request $request)
    {
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array)$countryData;
        $countries = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        $country = $countryData->$countryCode->country;
        $dialCode = $countryData->$countryCode->dial_code;
        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'mastername' => 'required|string|max:40',
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:masters,email',  // Ensure email is unique for new records
            'mobile' => 'required|string|max:40',
            'country' => 'required|in:' . $countries,
            'amount' => 'required|numeric|gt:0',
            'password' => ['required', 'confirmed', $passwordValidation]
        ]);
        $amount = $request->amount;
        // Check if the mobile number already exists for other records
        $exists = Master::where('mobile', $request->mobile)
            ->where('dial_code', $dialCode)
            ->exists();

        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }
        // Create a new Master instance
        $master = new Master();
        $master->mobile = $request->mobile;
        $master->firstname = $request->firstname;
        $master->lastname = $request->lastname;
        $master->mastername = $request->mastername;
        $master->email = $request->email;
        $master->exposure = $request->exposure;
        $master->balance = $amount;

        $master->address = $request->address;
        $master->city = $request->city;
        $master->state = $request->state;
        $master->zip = $request->zip;
        $master->country_name = @$country;
        $master->dial_code = $dialCode;
        $master->country_code = $countryCode;
        $password = Hash::make($request->password);
        $master->password = $password;
        $master->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $master->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $master->ts = $request->ts ? Status::ENABLE : Status::DISABLE;

        // Handle KYC status
        if (!$request->kv) {
            $master->kv = Status::KYC_UNVERIFIED;
            $master->kyc_data = null;
        } else {
            $master->kv = Status::KYC_VERIFIED;
        }

        $master->save();
        $masterId = $master->id;
        $masterBalance = $master->balance;
        $trx = getTrx();
        $transaction = new MastersTransaction();
        $transaction->trx_type = '+';
        $transaction->remark = 'balance_add';
        $transaction->master_id = $masterId;
        $transaction->amount = $amount;
        $transaction->post_balance = $masterBalance;
        $transaction->charge = 0;
        $transaction->trx =  $trx;
        $transaction->details =  "Initial Balance by Admin " . $amount;
        $transaction->save();

        $notify[] = ['success', 'New Master created successfully'];
        return back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'act' => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);

        $master = Master::findOrFail($id);
        $amount = $request->amount;
        $trx = getTrx();

        $transaction = new MastersTransaction();

        if ($request->act == 'add') {
            $master->balance += $amount;

            $transaction->trx_type = '+';
            $transaction->remark = 'balance_add';

            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', 'Balance added successfully'];
        } else {
            if ($amount > $master->balance) {
                $notify[] = ['error', $master->mastername . ' doesn\'t have sufficient balance.'];
                return back()->withNotify($notify);
            }

            $master->balance -= $amount;

            $transaction->trx_type = '-';
            $transaction->remark = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[] = ['success', 'Balance subtracted successfully'];
        }

        $master->save();

        $transaction->master_id = $master->id;
        $transaction->amount = $amount;
        $transaction->post_balance = $master->balance;
        $transaction->charge = 0;
        $transaction->trx =  $trx;
        $transaction->details = $request->remark;
        $transaction->save();

        // notify($master, $notifyTemplate, [
        //     'trx' => $trx,
        //     'amount' => showAmount($amount, currencyFormat: false),
        //     'remark' => $request->remark,
        //     'post_balance' => showAmount($master->balance, currencyFormat: false)
        // ]);

        return back()->withNotify($notify);
    }

    public function passwordset(Request $request, $id)
    {
        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'password' => ['required', 'confirmed', $passwordValidation]
        ]);

        $master = Master::findOrFail($id);
        $password = Hash::make($request->password);
        $master->password = $password;
        $master->save();
        $notify[] = ['success', 'Password changed successfully'];
        return back()->withNotify($notify);
    }

    public function login($id)
    {
        Auth::loginUsingId($id);
        return to_route('master.home');
    }

    public function status(Request $request, $id)
    {
        $master = Master::findOrFail($id);
        if ($master->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255'
            ]);
            $master->status = Status::USER_BAN;
            $master->ban_reason = $request->reason;
            $notify[] = ['success', 'Master banned successfully'];
        } else {
            $master->status = Status::USER_ACTIVE;
            $master->ban_reason = null;
            $notify[] = ['success', 'Master unbanned successfully'];
        }
        $master->save();
        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $master = Master::findOrFail($id);
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.masters.detail', $master->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $master->mastername;
        return view('admin.masters.notification_single', compact('pageTitle', 'master'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required',
            'via'     => 'required|in:email,sms,push',
            'subject' => 'required_if:via,email,push',
            'image'   => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $imageUrl = null;
        if ($request->via == 'push' && $request->hasFile('image')) {
            $imageUrl = fileUploader($request->image, getFilePath('push'));
        }

        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', 'Default notification template is not enabled'];
            return back()->withNotify($notify);
        }

        $master = Master::findOrFail($id);
        notify($master, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ], [$request->via], pushImage: $imageUrl);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $notifyToMaster = Master::notifyToMaster();
        $masters        = Master::active()->count();
        $pageTitle    = 'Notification to Verified Masters';

        if (session()->has('SEND_NOTIFICATION') && !request()->email_sent) {
            session()->forget('SEND_NOTIFICATION');
        }

        return view('admin.masters.notification_all', compact('pageTitle', 'masters', 'notifyToMaster'));
    }


    public function countBySegment($methodName)
    {
        return Master::active()->$methodName()->count();
    }

    public function list()
    {
        $query = Master::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%')->orWhere('mastername', 'like', '%' . request()->search . '%');
            });
        }
        $masters = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'masters'   => $masters,
            'more'    => $masters->hasMorePages()
        ]);
    }

    public function notificationLog($id)
    {
        $master = Master::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $master->mastername;
        $logs = NotificationLog::where('master_id', $id)->with('master')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs', 'master'));
    }

    public function transaction(Request $request, $masterId = null)
    {
        $pageTitle = 'Transaction Logs';

        $remarks = MastersTransaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = MastersTransaction::searchable(['trx', 'master:mastername'])->filter(['trx_type', 'remark'])->dateFilter()->orderBy('id', 'desc')->with('master');
        if ($masterId) {
            $transactions = $transactions->where('master_id', $masterId);
        }
        $transactions = $transactions->paginate(getPaginate());

        return view('admin.masters.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }
}

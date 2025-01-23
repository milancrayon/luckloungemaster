<?php

namespace App\Http\Controllers\Master;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class ManageCustomersController extends Controller
{

    public function allCustomers()
    {
        $pageTitle = 'All Customers';
        $customers = $this->customerData();
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }

    public function activeCustomers()
    {
        $pageTitle = 'Active Customers';
        $customers = $this->customerData('active');
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }

    public function bannedCustomers()
    {
        $pageTitle = 'Banned Customers';
        $customers = $this->customerData('banned');
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }

    public function emailUnverifiedCustomers()
    {
        $pageTitle = 'Email Unverified Customers';
        $customers = $this->customerData('emailUnverified');
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }

    public function kycUnverifiedCustomers()
    {
        $pageTitle = 'KYC Unverified Customers';
        $customers = $this->customerData('kycUnverified');
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }

    public function kycPendingCustomers()
    {
        $pageTitle = 'KYC Unverified Customers';
        $customers = $this->customerData('kycPending');
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }

    public function emailVerifiedCustomers()
    {
        $pageTitle = 'Email Verified Customers';
        $customers = $this->customerData('emailVerified');
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }


    public function mobileUnverifiedCustomers()
    {
        $pageTitle = 'Mobile Unverified Customers';
        $customers = $this->customerData('mobileUnverified');
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }


    public function mobileVerifiedCustomers()
    {
        $pageTitle = 'Mobile Verified Customers';
        $customers = $this->customerData('mobileVerified');
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }


    public function customersWithBalance()
    {
        $pageTitle = 'Customers with Balance';
        $customers = $this->customerData('withBalance');
        return view('master.customers.list', compact('pageTitle', 'customers'));
    }


    protected function customerData($scope = null)
    {
        if ($scope) {
            $customers = User::$scope();
        } else {
            $customers = User::query();
        };
        $customers->where('created_by', auth()->guard('master')->user()->id);
        return $customers->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }


    public function detail($id)
    {
        $customer = User::findOrFail($id);
        $pageTitle = 'Customer Detail - ' . $customer->username;
        $totalTransaction = Transaction::where('user_id', $customer->id)->count();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('master.customers.detail', compact('pageTitle', 'customer', 'totalTransaction', 'countries'));
    }


    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $customer = User::findOrFail($id);
        return view('master.customers.kyc_detail', compact('pageTitle', 'customer'));
    }

    public function kycApprove($id)
    {
        $customer = User::findOrFail($id);
        $customer->kv = Status::KYC_VERIFIED;
        $customer->save();

        notify($customer, 'KYC_APPROVE', []);

        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('master.customers.kyc.pending')->withNotify($notify);
    }

    public function kycReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required'
        ]);
        $customer = User::findOrFail($id);
        $customer->kv = Status::KYC_UNVERIFIED;
        $customer->kyc_rejection_reason = $request->reason;
        $customer->save();

        notify($customer, 'KYC_REJECT', [
            'reason' => $request->reason
        ]);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('master.customers.kyc.pending')->withNotify($notify);
    }


    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray   = (array)$countryData;
        $countries      = implode(',', array_keys($countryArray));

        $countryCode    = $request->country;
        $country        = $countryData->$countryCode->country;
        $dialCode       = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:customers,email,' . $customer->id,
            'mobile' => 'required|string|max:40',
            'country' => 'required|in:' . $countries,
        ]);

        $exists = User::where('mobile', $request->mobile)->where('dial_code', $dialCode)->where('id', '!=', $customer->id)->exists();
        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $customer->mobile = $request->mobile;
        $customer->firstname = $request->firstname;
        $customer->lastname = $request->lastname;
        $customer->email = $request->email;

        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->zip = $request->zip;
        $customer->country_name = @$country;
        $customer->dial_code = $dialCode;
        $customer->country_code = $countryCode;

        $customer->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $customer->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $customer->ts = $request->ts ? Status::ENABLE : Status::DISABLE;
        if (!$request->kv) {
            $customer->kv = Status::KYC_UNVERIFIED;
            if ($customer->kyc_data) {
                foreach ($customer->kyc_data as $kycData) {
                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
                    }
                }
            }
            $customer->kyc_data = null;
        } else {
            $customer->kv = Status::KYC_VERIFIED;
        }
        $customer->save();

        $notify[] = ['success', 'Customer details updated successfully'];
        return back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'act' => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);

        $customer = User::findOrFail($id);
        $amount = $request->amount;
        $trx = getTrx();

        $transaction = new Transaction();

        if ($request->act == 'add') {
            $customer->balance += $amount;

            $transaction->trx_type = '+';
            $transaction->remark = 'balance_add';

            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', 'Balance added successfully'];
        } else {
            if ($amount > $customer->balance) {
                $notify[] = ['error', $customer->username . ' doesn\'t have sufficient balance.'];
                return back()->withNotify($notify);
            }

            $customer->balance -= $amount;

            $transaction->trx_type = '-';
            $transaction->remark = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[] = ['success', 'Balance subtracted successfully'];
        }

        $customer->save();

        $transaction->user_id = $customer->id;
        $transaction->amount = $amount;
        $transaction->post_balance = $customer->balance;
        $transaction->charge = 0;
        $transaction->trx =  $trx;
        $transaction->details = $request->remark;
        $transaction->save();

        notify($customer, $notifyTemplate, [
            'trx' => $trx,
            'amount' => showAmount($amount, currencyFormat: false),
            'remark' => $request->remark,
            'post_balance' => showAmount($customer->balance, currencyFormat: false)
        ]);

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

        $customer = User::findOrFail($id);
        $password = Hash::make($request->password);
        $customer->password = $password;
        $customer->save();
        $notify[] = ['success', 'Password changed successfully'];
        return back()->withNotify($notify);
    }

    public function login($id)
    {
        Auth::loginUsingId($id);
        return to_route('customer.home');
    }

    public function status(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        if ($customer->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255'
            ]);
            $customer->status = Status::USER_BAN;
            $customer->ban_reason = $request->reason;
            $notify[] = ['success', 'Customer banned successfully'];
        } else {
            $customer->status = Status::USER_ACTIVE;
            $customer->ban_reason = null;
            $notify[] = ['success', 'Customer unbanned successfully'];
        }
        $customer->save();
        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $customer = User::findOrFail($id);
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('master.customers.detail', $customer->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $customer->username;
        return view('master.customers.notification_single', compact('pageTitle', 'customer'));
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
            return to_route('master.dashboard')->withNotify($notify);
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

        $customer = User::findOrFail($id);
        notify($customer, 'DEFAULT', [
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
            return to_route('master.dashboard')->withNotify($notify);
        }

        $notifyToCustomer = User::notifyToCustomer();
        $customers        = User::active()->count();
        $pageTitle    = 'Notification to Verified Customers';

        if (session()->has('SEND_NOTIFICATION') && !request()->email_sent) {
            session()->forget('SEND_NOTIFICATION');
        }

        return view('master.customers.notification_all', compact('pageTitle', 'customers', 'notifyToCustomer'));
    }

    public function sendNotificationAll(Request $request)
    {
        $request->validate([
            'via'                          => 'required|in:email,sms,push',
            'message'                      => 'required',
            'subject'                      => 'required_if:via,email,push',
            'start'                        => 'required|integer|gte:1',
            'batch'                        => 'required|integer|gte:1',
            'being_sent_to'                => 'required',
            'cooling_time'                 => 'required|integer|gte:1',
            'number_of_top_deposited_customer' => 'required_if:being_sent_to,topDepositedCustomers|integer|gte:0',
            'number_of_days'               => 'required_if:being_sent_to,notLoginCustomers|integer|gte:0',
            'image'                        => ["nullable", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'number_of_days.required_if'               => "Number of days field is required",
            'number_of_top_deposited_customer.required_if' => "Number of top deposited customer field is required",
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('master.dashboard')->withNotify($notify);
        }


        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', 'Default notification template is not enabled'];
            return back()->withNotify($notify);
        }

        if ($request->being_sent_to == 'selectedCustomers') {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['customer' => session()->get('SEND_NOTIFICATION')['customer']]);
            } else {
                if (!$request->customer || !is_array($request->customer) || empty($request->customer)) {
                    $notify[] = ['error', "Ensure that the customer field is populated when sending an email to the designated customer group"];
                    return back()->withNotify($notify);
                }
            }
        }

        $scope          = $request->being_sent_to;
        $customerQuery      = User::oldest()->active()->$scope();

        if (session()->has("SEND_NOTIFICATION")) {
            $totalCustomerCount = session('SEND_NOTIFICATION')['total_customer'];
        } else {
            $totalCustomerCount = (clone $customerQuery)->count() - ($request->start - 1);
        }


        if ($totalCustomerCount <= 0) {
            $notify[] = ['error', "Notification recipients were not found among the selected customer base."];
            return back()->withNotify($notify);
        }


        $imageUrl = null;

        if ($request->via == 'push' && $request->hasFile('image')) {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['image' => session()->get('SEND_NOTIFICATION')['image']]);
            }
            if ($request->hasFile("image")) {
                $imageUrl = fileUploader($request->image, getFilePath('push'));
            }
        }

        $customers = (clone $customerQuery)->skip($request->start - 1)->limit($request->batch)->get();

        foreach ($customers as $customer) {
            notify($customer, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ], [$request->via], pushImage: $imageUrl);
        }

        return $this->sessionForNotification($totalCustomerCount, $request);
    }


    private function sessionForNotification($totalCustomerCount, $request)
    {
        if (session()->has('SEND_NOTIFICATION')) {
            $sessionData                = session("SEND_NOTIFICATION");
            $sessionData['total_sent'] += $sessionData['batch'];
        } else {
            $sessionData               = $request->except('_token');
            $sessionData['total_sent'] = $request->batch;
            $sessionData['total_customer'] = $totalCustomerCount;
        }

        $sessionData['start'] = $sessionData['total_sent'] + 1;

        if ($sessionData['total_sent'] >= $totalCustomerCount) {
            session()->forget("SEND_NOTIFICATION");
            $message = ucfirst($request->via) . " notifications were sent successfully";
            $url     = route("master.customers.notification.all");
        } else {
            session()->put('SEND_NOTIFICATION', $sessionData);
            $message = $sessionData['total_sent'] . " " . $sessionData['via'] . "  notifications were sent successfully";
            $url     = route("master.customers.notification.all") . "?email_sent=yes";
        }
        $notify[] = ['success', $message];
        return redirect($url)->withNotify($notify);
    }

    public function countBySegment($methodName)
    {
        return User::active()->$methodName()->count();
    }

    public function list()
    {
        $query = User::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%')->orWhere('username', 'like', '%' . request()->search . '%');
            });
        }
        $customers = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'customers'   => $customers,
            'more'    => $customers->hasMorePages()
        ]);
    }

    public function notificationLog($id)
    {
        $customer = User::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $customer->username;
        $logs = NotificationLog::where('user_id', $id)->with('customer')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('master.reports.notification_history', compact('pageTitle', 'logs', 'customer'));
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
            'username' => 'required|string|max:40',
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:users,email',  // Ensure email is unique for new records
            'mobile' => 'required|string|max:40',
            'country' => 'required|in:' . $countries,
            'password' => ['required', 'confirmed', $passwordValidation]
        ]);
        $master_id = auth()->guard('master')->user()->id;
        // Check if the mobile number already exists for other records
        $exists = User::where('mobile', $request->mobile)
            ->where('dial_code', $dialCode)
            ->exists();

        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }
        // Create a new Master instance
        $customer = new User();
        $customer->mobile = $request->mobile;
        $customer->firstname = $request->firstname;
        $customer->lastname = $request->lastname;
        $customer->username = $request->username;
        $customer->email = $request->email;
        $customer->exposure = $request->exposure;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->zip = $request->zip;
        $customer->country_name = @$country;
        $customer->dial_code = $dialCode;
        $customer->country_code = $countryCode;
        $password = Hash::make($request->password);
        $customer->password = $password;
        $customer->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $customer->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $customer->ts = $request->ts ? Status::ENABLE : Status::DISABLE;
        $customer->created_by = $master_id;
        $customer->updated_by = $master_id;

        // Handle KYC status
        if (!$request->kv) {
            $customer->kv = Status::KYC_UNVERIFIED;
            $customer->kyc_data = null;
        } else {
            $customer->kv = Status::KYC_VERIFIED;
        }

        $customer->save();
        $notify[] = ['success', 'New Customer created successfully'];
        return back()->withNotify($notify);
    }

    public function addCustomers()
    {
        $pageTitle = 'Add Customer Detail';
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('master.customers.add', compact('pageTitle', 'countries'));
    }
}

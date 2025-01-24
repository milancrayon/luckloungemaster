<?php

namespace App\Http\Controllers\Master;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\MastersTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $customer = User::where('id', $id)
            ->where('created_by', auth()->guard('master')->user()->id)
            ->firstOrFail();  // Use firstOrFail instead of findOrFail for custom conditions

        $pageTitle = 'Customer Detail - ' . $customer->username;
        $totalTransaction = Transaction::where('user_id', $customer->id)->count();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('master.customers.detail', compact('pageTitle', 'customer', 'totalTransaction', 'countries'));
    }


    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $customer = User::where('id', $id)
            ->where('created_by', auth()->guard('master')->user()->id)
            ->firstOrFail();  // Use firstOrFail instead of findOrFail for custom conditions
        return view('master.customers.kyc_detail', compact('pageTitle', 'customer'));
    }

    public function kycApprove($id)
    {
        $customer = User::where('id', $id)
            ->where('created_by', auth()->guard('master')->user()->id)
            ->firstOrFail();  // Use firstOrFail instead of findOrFail for custom conditions
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
        $customer = User::where('id', $id)
            ->where('created_by', auth()->guard('master')->user()->id)
            ->firstOrFail();  // Use firstOrFail instead of findOrFail for custom conditions
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
        $master_id = auth()->guard('master')->user()->id;
        $master = Master::where('id', $master_id)->firstOrFail();  // Fetch master based on authenticated user's ID

        // Check if the master's status is not 1 (e.g., banned or inactive)
        if ($master->status != 1) {
            $notify[] = ['error', 'Your account is banned. You cannot perform this action.'];
            return back()->withNotify($notify);
        }
        $customer = User::where('id', $id)
            ->where('created_by', auth()->guard('master')->user()->id)
            ->firstOrFail();  // Use firstOrFail instead of findOrFail for custom conditions
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray   = (array)$countryData;
        $countries      = implode(',', array_keys($countryArray));

        $countryCode    = $request->country;
        $country        = $countryData->$countryCode->country;
        $dialCode       = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:users,email,' . $customer->id,
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
        $customer->updated_by = $master_id;
        $customer->exposure = $request->exposure;
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
        $master_id = auth()->guard('master')->user()->id;
        $master = Master::where('id', $master_id)->firstOrFail();  // Fetch master based on authenticated user's ID

        // Check if the master's status is not 1 (e.g., banned or inactive)
        if ($master->status != 1) {
            $notify[] = ['error', 'Your account is banned. You cannot perform this action.'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'act' => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
        ]);
        $amount = $request->amount;
        if ($amount > $master->balance) {
            $notify[] = ['error', $master->mastername . ' doesn\'t have sufficient balance.'];
            return back()->withNotify($notify);
        }

        $customer = User::where('id', $id)
            ->where('created_by', auth()->guard('master')->user()->id)
            ->firstOrFail();  // Use firstOrFail instead of findOrFail for custom conditions

        $trx = getTrx();

        $transaction = new Transaction();
        $master_transaction = new MastersTransaction();
        if ($request->act == 'add') {
            $customer->balance += $amount;
            $master->balance -= $amount;
            $transaction->trx_type = '+';
            $transaction->remark = 'balance_add';
            $master_transaction->trx_type = '-';
            $master_transaction->remark = 'balance_subtract';
            $master_transaction->details = 'The balance has been added to the customer ' . $customer->username;
            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', 'Balance added successfully'];
        } else {
            if ($amount > $customer->balance) {
                $notify[] = ['error', $customer->username . ' doesn\'t have sufficient balance.'];
                return back()->withNotify($notify);
            }

            $customer->balance -= $amount;
            $master->balance += $amount;
            $transaction->trx_type = '-';
            $transaction->remark = 'balance_subtract';
            $master_transaction->trx_type = '+';
            $master_transaction->remark = 'balance_add';
            $master_transaction->details = 'The balance has been subtracted to the customer ' . $customer->username;
            $notifyTemplate = 'BAL_SUB';
            $notify[] = ['success', 'Balance subtracted successfully'];
        }

        $customer->save();
        $master->save();

        $master_transaction->master_id = $master->id;
        $master_transaction->amount = $amount;
        $master_transaction->post_balance = $master->balance;
        $master_transaction->charge = 0;
        $master_transaction->trx =  $trx;
        $master_transaction->save();

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

        $customer = User::where('id', $id)
            ->where('created_by', auth()->guard('master')->user()->id)
            ->firstOrFail();  // Use firstOrFail instead of findOrFail for custom conditions
        $password = Hash::make($request->password);
        $customer->password = $password;
        $customer->save();
        $notify[] = ['success', 'Password changed successfully'];
        return back()->withNotify($notify);
    }


    public function status(Request $request, $id)
    {
        $customer = User::where('id', $id)
            ->where('created_by', auth()->guard('master')->user()->id)
            ->firstOrFail();  // Use firstOrFail instead of findOrFail for custom conditions
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

    public function store(Request $request)
    {
        $master_id = auth()->guard('master')->user()->id;
        $master = Master::where('id', $master_id)->firstOrFail();  // Fetch master based on authenticated user's ID

        // Check if the master's status is not 1 (e.g., banned or inactive)
        if ($master->status != 1) {
            $notify[] = ['error', 'Your account is banned. You cannot perform this action.'];
            return back()->withNotify($notify);
        }

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
        $customer->profile_complete = 1;

        // Handle KYC status
        if (!$request->kv) {
            $customer->kv = Status::KYC_UNVERIFIED;
            $customer->kyc_data = null;
        } else {
            $customer->kv = Status::KYC_VERIFIED;
        }
        $customer_exists = User::where('created_by', $master_id)->exists();

        if (!$customer_exists) {
            $master = Master::findOrFail($master_id);
            $master->exposure = $request->exposure;
            $master->save();
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

    public function transaction(Request $request, $userId = null)
    {
        $pageTitle = 'Transaction Logs';

        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::select('transactions.*', 'users.username')  // Select fields from both tables
            ->join('users', 'users.id', '=', 'transactions.user_id')  // Join with the 'users' table based on the user_id
            ->where('users.created_by', auth()->guard('master')->user()->id)  // Filter users by 'created_by'
            ->searchable(['trx', 'users.username'])  // Assuming you have a custom searchable scope
            ->filter(['trx_type', 'remark'])  // Assuming you have a custom filter scope
            ->dateFilter()  // Assuming you have a custom date filter scope
            ->orderBy('transactions.id', 'desc');  // Ordering by transaction ID

        // If $userId is provided, filter by user_id
        if ($userId) {
            $transactions = $transactions->where('transactions.user_id', $userId);
        }

        // Paginate the results
        $transactions = $transactions->paginate(getPaginate());



        return view('master.customers.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'Customer Login History';
        $loginLogs = UserLogin::select('user_logins.*', 'users.username')  // Select fields from both tables
            ->join('users', 'users.id', '=', 'user_logins.user_id')  // Join with the 'users' table based on the user_id
            ->where('users.created_by', auth()->guard('master')->user()->id)  // Filter users by 'created_by'
            ->searchable(['user:username'])  // Assuming you have a custom searchable scope
            ->dateFilter()  // Assuming you have a custom date filter scope
            ->orderBy('user_logins.id', 'desc');  // Ordering by user_login ID

        // Paginate the results
        $loginLogs = $loginLogs->paginate(getPaginate());

        return view('master.customers.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)
            ->orderBy('id', 'desc')
            ->with(['user' => function ($query) {
                // Apply a condition to the related 'user' model
                $query->where('created_by', auth()->guard('master')->user()->id);
            }])
            ->paginate(getPaginate());
        return view('master.customers.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }


    public function login($id)
    {
        Auth::loginUsingId($id);
        return to_route('user.home');
    }
}

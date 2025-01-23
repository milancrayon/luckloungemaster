<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {
    Route::middleware('master.guest')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login');
            Route::get('logout', 'logout')->middleware('master')->withoutMiddleware('master.guest')->name('logout');
        });

        // Master Password Reset
        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('reset');
            Route::post('reset', 'sendResetCodeEmail');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function () {
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
            Route::post('password/reset/change', 'reset')->name('password.change');
        });
    });
});

Route::middleware('master')->group(function () {
    Route::controller('MasterController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');
        Route::get('transactions', 'transaction')->name('transaction');
    });
    // Customers Manager
    Route::controller('ManageCustomersController')->name('customers.')->prefix('customers')->group(function () {
        Route::get('/', 'allCustomers')->name('all');
        Route::get('active', 'activeCustomers')->name('active');
        Route::get('banned', 'bannedCustomers')->name('banned');
        Route::get('email-verified', 'emailVerifiedCustomers')->name('email.verified');
        Route::get('email-unverified', 'emailUnverifiedCustomers')->name('email.unverified');
        Route::get('mobile-unverified', 'mobileUnverifiedCustomers')->name('mobile.unverified');
        Route::get('kyc-unverified', 'kycUnverifiedCustomers')->name('kyc.unverified');
        Route::get('kyc-pending', 'kycPendingCustomers')->name('kyc.pending');
        Route::get('mobile-verified', 'mobileVerifiedCustomers')->name('mobile.verified');
        Route::get('with-balance', 'customersWithBalance')->name('with.balance');
        Route::get('add', 'addCustomers')->name('add');
        Route::get('detail/{id}', 'detail')->name('detail');
        Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details');
        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
        Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('add-sub-balance/{id}', 'addSubBalance')->name('add.sub.balance');
        Route::post('passwordset/{id}', 'passwordset')->name('passwordset');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');
        Route::get('list', 'list')->name('list');
        Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
        Route::post('store', 'store')->name('store');
        Route::get('transaction/{customer_id?}', 'transaction')->name('transaction');
        Route::get('login/history', 'loginHistory')->name('login.history');
        Route::get('login/ipHistory/{ip}', 'loginIpHistory')->name('login.ipHistory');
    });
});

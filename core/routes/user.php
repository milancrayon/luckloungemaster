<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/login', 'showLoginForm')->name('login');
            Route::post('/login', 'login');
            Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
        });

        Route::controller('RegisterController')->middleware(['guest'])->group(function () {
            Route::get('register', 'showRegistrationForm')->name('register');
            Route::post('register', 'register');
            Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
        });

        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('request');
            Route::post('email', 'sendResetCodeEmail')->name('email');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function () {
            Route::post('password/reset', 'reset')->name('password.update');
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
        });

        Route::controller('SocialiteController')->group(function () {
            Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
            Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
        });
    });
});

Route::middleware('auth')->name('user.')->group(function () {

    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');


    Route::middleware(['check.status'])->group(function () {

        Route::namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::get('transactions', 'transactions')->name('transactions');
                Route::get('referral', 'referrals')->name('referrals');

                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');

                Route::get('game/log', 'gameLog')->name('game.log');
                Route::get('commission/log', 'commissionLog')->name('commission.log');

                Route::controller('BetController')->prefix('bet')->name('bet.')->group(function () {
                    Route::post('place-bet', 'placeBet')->name('place');
                });
                Route::get('my-bets/{type?}', 'BetLogController@index')->name('bets');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

    
            Route::controller('PlayController')->prefix('play')->name('play.')->group(function () {

                Route::post('aviatorbets', 'aviatorbets')->name('aviatorbets');
                Route::post('aviatergenerate', 'aviatergenerate')->name('aviatergenerate');
                Route::post('aviatorincreamentor', 'aviatorincreamentor')->name('aviatorincreamentor');
                Route::post('aviatorhistory', 'aviatorhistory')->name('aviatorhistory');
                Route::post('aviatorgameover', 'aviatorgameover')->name('aviatorgameover');
                Route::post('aviatorbetadd', 'aviatorbetadd')->name('aviatorbetadd');
                Route::post('aviatorcashout', 'aviatorcashout')->name('aviatorcashout');

                Route::post('roulettebet', 'roulettebet')->name('roulettebet');
                Route::post('roulettebetupdate', 'roulettebetupdate')->name('roulettebetupdate');

                Route::get('game/{alias}', 'playGame')->name('game');
                Route::post('game/invest/{alias}', 'investGame')->name('game.invest');
                Route::post('game/end/{alias}', 'gameEnd')->name('game.end');

                Route::post('roulette/submit', 'rouletteSubmit')->name('roulette.submit');
                Route::post('roulette/result', 'rouletteResult')->name('roulette.result');

                Route::post('dice/submit', 'diceSubmit')->name('dice.submit');
                Route::post('dice/result', 'diceResult')->name('dice.result');

                Route::post('keno/submit', 'kenoSubmit')->name('keno.submit');
                Route::post('keno/update', 'kenoUpdate')->name('keno.update');

                Route::post('blackjack/hit', 'blackjackHit')->name('blackjack.hit');
                Route::post('blackjack/stay', 'blackjackStay')->name('blackjack.stay');
                Route::post('blackjack/again/{id}', 'blackjackAgain')->name('blackjack.again');

                Route::post('mine/cashout', 'mineCashout')->name('mine.cashout');

                Route::post('poker/deal', 'pokerDeal')->name('game.poker.deal');
                Route::post('poker/call', 'pokerCall')->name('game.poker.call');
                Route::post('poker/fold', 'pokerFold')->name('game.poker.fold');
            });
        });

    });
});

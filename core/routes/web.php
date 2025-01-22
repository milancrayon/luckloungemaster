<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');


Route::controller('BetSlipController')->prefix('bet')->name('bet.')->group(function () {
    Route::get('add-to-bet-slip', 'addToBetSlip')->name('slip.add');
    Route::post('remove/{id}', 'remove')->name('slip.remove');
    Route::post('remove-all', 'removeAll')->name('slip.remove.all');
    Route::post('update', 'update')->name('slip.update');
    Route::post('update-all', 'updateAll')->name('slip.update.all');
});


Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    
    Route::post('/upload', 'upload');

    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::post('/subscribe', 'subscribe')->name('subscribe.post');
    
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    
    Route::get('games', 'games')->name('games');
    
    Route::get('search', 'search')->name('search');
    Route::post('search', 'searchSubmit');
    Route::post('searchgame', 'searchgame');

    Route::get('blog', 'blog')->name('blog');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');
    
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::get('maintenance-mode', 'SiteController@maintenance')->withoutMiddleware('maintenance')->name('maintenance');
    
    Route::get('/bet', 'bet')->name('bet');

    Route::get('odds-by-market/{id}', 'getOdds')->name('market.odds');
    Route::get('markets/{gameSlug}', 'markets')->name('game.markets');
    Route::get('league/{slug}', 'gamesByLeague')->name('league.games');
    Route::get('category/{slug}', 'gamesByCategory')->name('category.games');
    Route::get('switch-to/{type}', 'switchType')->name('switch.type');
    Route::get('odds-type/{type}', 'oddsType')->name('odds.type');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});

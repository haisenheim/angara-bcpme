<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    //dd(tenant('name'));
    Route::get('/', function () {
            return redirect(route('login'));
    });

    Route::post('/connect','App\Http\Controllers\Tenant\AuthController@login')->name('connect');
    Route::get('/tenant/home','App\Http\Controllers\Tenant\HomeController@index')->name('tenant.home');

    Route::namespace('App\Http\Controllers\Tenant\Admin')
    ->prefix('admin')
    ->middleware(['auth','tenant.admin'])
    ->name('admin.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::resource('exploitants','ExploitantController');
        Route::resource('members','MemberController');
        Route::post('member/key','MemberController@addKey')->name('member.add.key');
        Route::resource('entrepots','EntrepotController');
        Route::resource('villages','VillageController');
        Route::resource('entrepots','EntrepotController');
        Route::resource('agents','AgentController');
        Route::resource('previsions','PrevisionController');
        Route::get('prevsion/data','PrevisionController@fetchAll')->name('previsions.all');
        Route::resource('users','UserController');
        Route::get('user/enable/{token}','UserController@enable')->name('user.enable');
        Route::get('user/disable/{token}','UserController@disable')->name('user.disable');
        Route::resource('mouvements','MouvementController');
        Route::resource('requests','RequestController');
        Route::get('mouvement/data','MouvementController@fetchAll')->name('mouvements.all');

        Route::resource('entrees','EntreeController');
        Route::get('entree/data','EntreeController@fetchAll')->name('entrees.all');
        Route::post('entree/paiemnt','EntreeController@addPaiement')->name('entree.paiement');
        Route::get('paiements','EntreeController@getPaiements')->name('paiements');

        Route::resource('sorties','SortieController');
        Route::get('sortie/data','SortieController@fetchAll')->name('sorties.all');

        Route::resource('wallets','WalletController');
        Route::get('caisses','WalletController@getCaisses')->name('caisses.index');
        Route::get('my_wallets','WalletController@getMyWallets')->name('my.wallets');
        Route::post('wallet/recharge','WalletController@recharger')->name('wallet.recharge');
        Route::get('recharges','WalletController@getRecharges')->name('recharges');
        Route::get('wallet/disable/{token}','WalletController@disable')->name('wallet.disable');
        Route::get('wallet/enable/{token}','WalletController@enable')->name('wallet.enable');
        Route::get('kpi/agents/solde','KpiController@getAgentSolde')->name('kpi.agents.solde');
    });

    Route::namespace('App\Http\Controllers\Tenant\Agent')
        ->prefix('agent')
        ->middleware(['auth','tenant.agent'])
        ->name('agent.')
        ->group(function(){
            Route::get('dashboard','DashboardController@index')->name('dashboard');
            Route::resource('members','MemberController');
            Route::post('member/key','MemberController@addKey')->name('member.add.key');
            Route::resource('previsions','PrevisionController');
            Route::get('prevsion/data','PrevisionController@fetchAll')->name('previsions.all');
            Route::resource('entrepots','EntrepotController');
            Route::resource('villages','VillageController');
    });

    Route::namespace('App\Http\Controllers\Tenant\Rstock')
        ->prefix('rstock')
        ->middleware(['auth','tenant.rstock'])
        ->name('rstock.')
        ->group(function(){
            Route::get('dashboard','DashboardController@index')->name('dashboard');
            Route::resource('entrees','EntreeController');
            Route::get('entree/data','EntreeController@fetchAll')->name('entrees.all');

            Route::resource('sorties','SortieController');
            Route::get('sortie/data','SortieController@fetchAll')->name('sorties.all');

            Route::resource('members','MemberController');
            Route::post('member/key','MemberController@addKey')->name('member.add.key');
            Route::resource('previsions','PrevisionController');
            Route::get('prevsion/data','PrevisionController@fetchAll')->name('previsions.all');
            Route::post('prevsion/entree','PrevisionController@addEntree')->name('prevision.entree');
            Route::resource('entrepots','EntrepotController');
            Route::resource('villages','VillageController');
    });

    Route::namespace('App\Http\Controllers\Tenant\Payeur')
        ->prefix('payeur')
        ->middleware(['auth','tenant.payeur'])
        ->name('payeur.')
        ->group(function(){
            Route::get('dashboard','DashboardController@index')->name('dashboard');
            Route::resource('exploitants','ExploitantController');
            Route::resource('members','MemberController');
            Route::post('member/key','MemberController@addKey')->name('member.add.key');
            Route::resource('entrepots','EntrepotController');
            Route::resource('villages','VillageController');
            Route::resource('entrepots','EntrepotController');
            Route::resource('agents','AgentController');
            Route::resource('previsions','PrevisionController');
            Route::get('prevsion/data','PrevisionController@fetchAll')->name('previsions.all');
            Route::resource('users','UserController');
            Route::get('user/enable/{token}','UserController@enable')->name('user.enable');
            Route::get('user/disable/{token}','UserController@disable')->name('user.disable');
            //Route::resource('mouvements','MouvementController');
            Route::resource('requests','RequestController');
            //Route::get('mouvement/data','MouvementController@fetchAll')->name('mouvements.all');

            Route::resource('entrees','EntreeController');
            Route::get('entree/data','EntreeController@fetchAll')->name('entrees.all');
            Route::post('entree/paiemnt','EntreeController@addPaiement')->name('entree.paiement');
            Route::get('paiements','EntreeController@getPaiements')->name('paiements');

            Route::resource('sorties','SortieController');
            Route::get('sortie/data','SortieController@fetchAll')->name('sorties.all');

            Route::resource('wallets','WalletController');
            Route::get('caisses','WalletController@getCaisses')->name('caisses.index');
            Route::get('my_wallets','WalletController@getMyWallets')->name('my.wallets');
            Route::post('wallet/recharge','WalletController@recharger')->name('wallet.recharge');
            Route::get('recharges','WalletController@getRecharges')->name('recharges');
            Route::get('wallet/disable/{token}','WalletController@disable')->name('wallet.disable');
            Route::get('wallet/enable/{token}','WalletController@enable')->name('wallet.enable');
            Route::get('kpi/agents/solde','KpiController@getAgentSolde')->name('kpi.agents.solde');
    });
});

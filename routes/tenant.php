<?php

declare(strict_types=1);

use App\Models\Arrondissement;
use App\Models\Departement;
use App\Models\Instruction\Scoring\Individual\Choice;
use App\Models\Instruction\Scoring\Individual\DossierChoice;
use App\Models\Niveau;
use App\Models\Region;
use App\Models\Structuration\Membre;
use App\Models\Village;
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

    Route::get('/critere/choices',function(){
        $critere_id = request()->query('id');
        $choices = \App\Models\Instruction\Scoring\Individual\Choice::where('critere_id',$critere_id)->get();
        return response()->json($choices->map(function ($choice) {
                return [
                    'id' => $choice->id,
                    'name' => $choice->name,
                ];
            })
        );
    })->name('scoring.critere.choices');

    Route::post('/critere/reponse',function(){
        $data = request()->all();
        //dd($data);
        $dossier_id = request()->entreprise_id;
        $choice_id = request()->choice_id;
        $choice = Choice::find($choice_id);
        //dd($choice);

        DossierChoice::updateOrCreate(
            [
                'dossier_id' => $dossier_id,
                'critere_id' => $choice->critere_id,
            ],
            [
                'choice_id' => $choice_id,
                'donnee_collectee'=>$choice->name,
                'score'=>$choice->valeur,
                'score_pondere'=>($choice->valeur * $choice->critere->poids)/100
            ]
        );

        return redirect()->back();
    })->name('scoring.critere.reponse');

    Route::get('/producteur',function () {
        $items = Membre::all();
        $niveaux = Niveau::all();
        $niveaux = $niveaux->map(function ($niveau) {
            return [
                'id' => $niveau->id,
                'name' => $niveau->name,
            ];
        });
        return response()->json([
            'producteurs' => $items->map(function ($item) {
                return [
                'nom' => $item->last_name,
                'prenom' => $item->first_name,
                'email' => $item->email,
                'telephone' => $item->phone,
                'situation_matrimoniale' => $item->situation_matrimoniale,
                'date_naissance' => $item->dtn,
                'lieu_naissance' => $item->lieu,
                'sexe' => $item->male?'Homme':'Femme',
                'niveau_etude' => $item->niveau?->name,
                'numero_cni'=> $item->cni,
                'date_expiration_cni' => $item->dt_expiration_cni,
                'numero_compte' => $item->compte,
                'nombre_enfants' => $item->nb_enfants,
                'arrondissement' => $item->arrondissement?->name,
                'departement' => $item->departement?->name,
                'region' => $item->region?->name,
                'date_adhesion' => $item->date_adhesion,
                'village' => $item->village?->name,
            ];
            }),
            'niveaux'=> $niveaux,
            'villages'=>Village::all()->map(function ($village) {
                return [
                    'id' => $village->id,
                    'name' => $village->name,
                ];
            }),
            'sexe' => [
                'Homme' => 'Homme',
                'Femme' => 'Femme',
            ],
            'arrondissements' => Arrondissement::all()->map(function ($arrondissement) {
                return [
                    'id' => $arrondissement->id,
                    'name' => $arrondissement->name,
                ];
            }),
            'departements' => Departement::all()->map(function ($dep) {
                return [
                    'id' => $dep->id,
                    'name' => $dep->name,
                ];
            }),
            'regions' => Region::all()->map(function ($reg) {
                return [
                    'id' => $reg->id,
                    'name' => $reg->name,
                ];
            }),

            'situations' => [
                'celibataire' => 'Célibataire',
                'marie' => 'Marié(e)',
                'divorce' => 'Divorcé(e)',
                'veuf' => 'Veuf(ve)',
            ],
        ]);
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

           // Route::get('members','MemberController@show')->name('members.show');
            Route::post('member/verger','MemberController@addVerger')->name('members.verger.add');
            Route::get('member/verger','MemberController@getVerger')->name('members.verger.show');
            Route::get('verger/grille/{token}','MemberController@editScoring')->name('verger.edit.scoring');

            Route::post('member/verger/campagne','MemberController@addCampagneVerger')->name('verger.campagne.add');
            Route::post('member/verger/travail','MemberController@addTravailCampagne')->name('campagne.travail.add');
            Route::post('member/verger/traitement','MemberController@addTraitementCampagne')->name('campagne.traitement.add');
            Route::post('member/verger/rendement','MemberController@setRendementCampagne')->name('campagne.rendement');
            Route::post('member/verger/visite','MemberController@addVisiteCampagne')->name('campagne.visite.add');
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

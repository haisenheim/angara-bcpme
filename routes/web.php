<?php

use App\Http\Controllers\HomeController;
use App\Imports\ApmeImport;
use App\Models\Agence;
use Illuminate\Support\Facades\Route;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpKernel\Exception\HttpException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('init/permissions',function(){
    //die();
    app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    Permission::create(['name'=>'entreprises.*','label'=>'Gestion des entreprises']);
    Permission::create(['name'=>'dossiers.*','label'=>'Gestion des dossiers']);
    Permission::create(['name'=>'entreprises.list','label'=>'Lister toutes les entreprises']);
    Permission::create(['name'=>'entreprises.my','label'=>'Lister mes entreprises uniquement']);
    Permission::create(['name'=>'dossiers.list','label'=>'Lister tous les dossiers d\'instruction']);
    Permission::create(['name'=>'dossiers.my','label'=>'Lister mes dossiers d\'instruction uniquement']);
    Permission::create(['name'=>'entreprises.create','label'=>'Creer des entreprises']);
    Permission::create(['name'=>'entreprises.edit','label'=>'Editer toute entreprise']);
    Permission::create(['name'=>'entreprises.show','label'=>'Afficher une entreprise']);
    Permission::create(['name'=>'entreprises.extract','label'=>'Extraire de fiche signalitique de l\'entreprise']);
    Permission::create(['name'=>'dossiers.create','label'=>'Inclure de l\'entreprise au programme']);
    Permission::create(['name'=>'dossiers.edit','label'=>'Instruire un dossier']);
    Permission::create(['name'=>'dossiers.show','label'=>'Afficher un dossier d\'instruction']);
    Permission::create(['name'=>'dossiers.extract','label'=>'Extraire un dossier d\'instruction']);
    Permission::create(['name'=>'users.handle','label'=>'Gerer des utilisateurs']);

    return 'Initialisation des droits terminee';
});

Route::get('apme',function(){
    //$file = request()->fichier;
    Excel::import(new ApmeImport, public_path('files/apme.xlsx'));
    return 'Ok';
});

Route::get('test-airtel',function(){
    $headers = array(
        'Content-Type' => 'application/json',
        'Accept' => '*/*',
        'X-Country' => 'CG',
        'X-Currency' => 'XAF',
        'Authorization' => 'Bearer UC*****2w',
        'x-signature' => 'MGsp*********Ag==',
        'x-key' => 'DVZC***********NM='
      );
      $client = new Http();
      // Define array of request body.
      $request_body = array();
      try {
        $response = $client->post('https://openapiuat.airtel.africa/standard/v2/cashin/', array(
          'headers' => $headers,
          'json' => $request_body
          )
        );
       print_r($response->getBody()->getContents());
      }
      catch (HttpException $e) {
      // handle exception or api errors.
        print_r($e->getMessage());
      }

});



Route::get('load',function(){
    /* $produits = Produit::all();
    foreach($produits as $p){
        foreach($produits as $item){
            if(Str::startsWith($item->code, $p->code) && ($item->code != $p->code)){
                $item->parent_id = $p->id;
                $item->save();
            }
        }
    } */
   $users = User::whereNull('token')->get();
   foreach($users as $user){
    $names = explode(' ',$user->name);
    $agence = Agence::find($user->agence_id);
    $user->representation_id = $agence->representation_id;
    $email = strtolower(substr($names[1],0,1)).'.'.strtolower($names[0]).rand(10,99).'@angara.com';
    $user->password = bcrypt('1234');
    $user->token = sha1($user->id . rand(1,999));
    $user->email = $email;
    $user->phone = '6'.rand(56329020,996772878);
    $user->save();
    echo $email .'<br/>';
   }
    //dd($produits);
    return 'ok';
});

Route::get('questions',function(){
    $chars = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U'];
    $questions = Question::all();
    foreach($questions as $q){
        $ch = substr(trim($q->name),0,1);
        for($i=0;$i<21;$i++){
            if($chars[$i]==strtoupper($ch)){
                $q->sous_critere_id = $i+1;
                $q->save();
            }
        }
    }
    return 'ok';
});

Route::get('/', function () {
    return redirect('login');
});



Route::namespace('App\Http\Controllers\Util')
    ->prefix('util')
    ->name('util.')
    ->group(function(){
        Route::get('region/departements','SearchController@getDepartementsByRegionId')->name('region.departements');
        Route::get('departement/arrondissements','SearchController@getArrondissementsByDepartementId')->name('departement.arrondissements');
        Route::get('arrondissement/villages','SearchController@getVillagesByArrondissementId')->name('arrondissement.villages');

        Route::get('localites','SearchController@getLocalites')->name('localites');
        Route::get('organismes','SearchController@getOrganismes')->name('organismes');
        Route::get('ville/agences','SearchController@getAgencesByVilleId')->name('ville.agences');
       Route::get('agence/caisses','SearchController@getCaissesByAgenceId')->name('agence.caisses');
       Route::get('produits/all','SearchController@fetchAllProduit')->name('produits.list');
       Route::get('services/afs','SearchController@fetchAfs')->name('afs.list');
       Route::get('services/anfs','SearchController@fetchAnfs')->name('anfs.list');
       Route::get('entreprise/create/date','SearchController@loadEntrepriseData')->name('entreprise.create.data');
       Route::get('programme/create/date','SearchController@loadProgrammeData')->name('programme.create.data');

    });

Route::namespace('App\Http\Controllers\Admin')
    ->prefix('admin')
    ->middleware(['auth','admin'])
    ->name('admin.')
    ->group(function(){
        Route::resource('entreprises','CompanyController');
        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');

        Route::post('entreprise/programme','CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::post('programme/user/permissions','ProgrammeController@saveUserPermissions')->name('programme.user.permissions.save');
        Route::post('programme/poste','ProgrammeController@savePoste')->name('programme.poste.save');
        Route::post('programme/user','ProgrammeController@saveUser')->name('programme.user.save');

        Route::get('entreprise/tiers/physique/{token}','CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique','CompanyController@saveTiersPhysique')->name('entreprise.physique.save');

        Route::get('entreprise/tiers/morale/{token}','CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::post('entreprise/tiers/morale','CompanyController@saveTiersMorale')->name('entreprise.morale.save');

        Route::get('entreprise/questionnaire/{token}','CompanyController@createQuestionnaire')->name('entreprise.questionnaire');
        Route::post('entreprise/questionnaire','CompanyController@saveQuestionnaire')->name('entreprise.questionnaire.save');

        Route::resource('dossiers','DossierController');

        Route::resource('programmes','ProgrammeController');
        Route::post('programme/composante','ProgrammeController@saveComposante')->name('programme.composante.save');
        Route::post('programme/appui','ProgrammeController@saveAppui')->name('programme.appui.save');
        Route::post('programme/produit','ProgrammeController@saveProduit')->name('programme.produit.save');
        Route::post('programme/resultat','ProgrammeController@saveResultat')->name('programme.resultat.save');
        Route::post('programme/save','ProgrammeController@save')->name('programmes.save');

        Route::resource('users','UserController');
        Route::get('territoire','TerritoireController@index')->name('territoire');
        Route::get('companies/data','CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');


        //Route::resource('entreprises','EntrepriseController');
        Route::get('dossier/{id}','EntrepriseController@getDossier')->name('dossier.show');
        Route::get('dossier/instruction/{id}','EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
        Route::post('engagement','EntrepriseController@setEngagement')->name('entreprise.set.engagement');

        Route::post('dossier/analyse','EntrepriseController@setAnalyse')->name('entreprise.dossier.analyse');

        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::resource('users','UserController');
        Route::get('user/enable/{token}','UserController@enable')->name('user.enable');
        Route::get('user/disable/{token}','UserController@disable')->name('user.disable');

        Route::resource('operateurs','OperateurController');

        Route::get('instruction/criteres/params','InstructionController@getCritereParamsForm');

        Route::post('instruction/dossier/dsf','InstructionController@loadDsf')->name('instruction.dsf');
        Route::get('instruction/dossier/create','InstructionController@createDossier')->name('instruction.dossier.create');
        Route::get('instruction/dossier/{id}','InstructionController@getDossier')->name('instruction.dossier');
        Route::get('instruction/dossier','InstructionController@findDossier')->name('instruction.dossier.find');
        Route::get('instruction/critere/choices','InstructionController@getChoices')->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse','InstructionController@saveCritereReponse')->name('instruction.critere.reponse');

    });

Route::namespace('App\Http\Controllers\Gestionnaire')
    ->prefix('gestionnaire')
    ->middleware(['auth','gestionnaire'])
    ->name('gestionnaire.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');

        Route::resource('entreprises','CompanyController');
        Route::post('entreprise/save','CompanyController@save')->name('entreprises.save');

        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('entreprise/programme','CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::post('entreprise/appui','CompanyController@saveAppui')->name('entreprise.appui.save');
        Route::get('entreprise/tiers/physique/{token}','CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique','CompanyController@saveTiersPhysique')->name('entreprise.physique.save');

        Route::get('entreprise/tiers/morale/{token}','CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::post('entreprise/tiers/morale','CompanyController@saveTiersMorale')->name('entreprise.morale.save');

        Route::get('entreprise/questionnaire/{token}','CompanyController@createQuestionnaire')->name('entreprise.questionnaire');
        Route::post('entreprise/questionnaire','CompanyController@saveQuestionnaire')->name('entreprise.questionnaire.save');

        Route::resource('dossiers','DossierController');

        Route::resource('programmes','ProgrammeController');
        Route::post('programme/composante','ProgrammeController@saveComposante')->name('programme.composante.save');
        Route::post('programme/resultat','ProgrammeController@saveResultat')->name('programme.resultat.save');

        Route::resource('users','UserController');
        Route::get('territoire','TerritoireController@index')->name('territoire');
        Route::get('companies/data','CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');


        //Route::resource('entreprises','EntrepriseController');
        Route::get('dossier/{id}','EntrepriseController@getDossier')->name('dossier.show');
        Route::get('dossier/instruction/{id}','EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
        Route::post('engagement','EntrepriseController@setEngagement')->name('entreprise.set.engagement');

        Route::post('dossier/analyse','EntrepriseController@setAnalyse')->name('entreprise.dossier.analyse');


        Route::get('instruction/criteres/params','InstructionController@getCritereParamsForm');

        Route::post('instruction/dossier/dsf','InstructionController@loadDsf')->name('instruction.dsf');
        Route::get('instruction/dossier/create','InstructionController@createDossier')->name('instruction.dossier.create');
        Route::get('instruction/dossier/{id}','InstructionController@getDossier')->name('instruction.dossier');
        Route::get('instruction/dossier','InstructionController@findDossier')->name('instruction.dossier.find');
        Route::get('instruction/critere/choices','InstructionController@getChoices')->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse','InstructionController@saveCritereReponse')->name('instruction.critere.reponse');
        Route::resource('wallets','WalletController');

        Route::resource('cooperatives','CooperativeController');
        Route::get('cooperative/data','CooperativeController@fetchAll')->name('cooperatives.all');

        Route::resource('members','MemberController');
        Route::get('member/data','MemberController@fetchAll')->name('members.all');

        Route::resource('entrepots','EntrepotController');
        Route::resource('requests','RequestController');
        Route::post('request/validate','RequestController@valider')->name('request.validate');
        Route::post('request/cancel','RequestController@cancel')->name('request.cancel');

    });


Route::namespace('App\Http\Controllers\Analyste')
    ->prefix('analyste')
    ->middleware(['auth','analyste'])
    ->name('analyste.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::resource('entreprises','CompanyController');
        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('entreprise/programme','CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::get('entreprise/tiers/physique/{token}','CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique','CompanyController@saveTiersPhysique')->name('entreprise.physique.save');

        Route::get('entreprise/tiers/morale/{token}','CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::post('entreprise/tiers/morale','CompanyController@saveTiersMorale')->name('entreprise.morale.save');

        Route::get('entreprise/questionnaire/{token}','CompanyController@createQuestionnaire')->name('entreprise.questionnaire');
        Route::post('entreprise/questionnaire','CompanyController@saveQuestionnaire')->name('entreprise.questionnaire.save');

        Route::resource('dossiers','DossierController');
        Route::post('dossier/dsf','DossierController@loadDsf')->name('dossier.dsf');
        Route::resource('programmes','ProgrammeController');
        Route::post('programme/composante','ProgrammeController@saveComposante')->name('programme.composante.save');
        Route::post('programme/resultat','ProgrammeController@saveResultat')->name('programme.resultat.save');

        Route::resource('users','UserController');
        Route::get('territoire','TerritoireController@index')->name('territoire');
        Route::get('companies/data','CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');


        //Route::resource('entreprises','EntrepriseController');
        Route::get('dossier/{id}','EntrepriseController@getDossier')->name('dossier.show');
        Route::get('dossier/instruction/{id}','EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
        Route::post('engagement','EntrepriseController@setEngagement')->name('entreprise.set.engagement');

        Route::post('dossier/analyse','EntrepriseController@setAnalyse')->name('entreprise.dossier.analyse');


        Route::get('instruction/criteres/params','InstructionController@getCritereParamsForm');

        Route::post('instruction/dossier/dsf','InstructionController@loadDsf')->name('instruction.dsf');
        Route::get('instruction/dossier/create','InstructionController@createDossier')->name('instruction.dossier.create');
        Route::get('instruction/dossier/{id}','InstructionController@getDossier')->name('instruction.dossier');
        Route::get('instruction/dossier','InstructionController@findDossier')->name('instruction.dossier.find');
        Route::get('instruction/critere/choices','InstructionController@getChoices')->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse','InstructionController@saveCritereReponse')->name('instruction.critere.reponse');

    });

Route::namespace('App\Http\Controllers\Ca')
    ->prefix('ca')
    ->middleware(['auth','ca'])
    ->name('ca.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::get('entreprises','CompanyController@index')->name('entreprises.index');
        Route::get('entreprises/{token}','CompanyController@show')->name('entreprises.show');
        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');
        Route::resource('dossiers','DossierController');
        Route::get('programmes','ProgrammeController@index')->name('programmes.index');
        Route::get('programmes/{token}','ProgrammeController@show')->name('programmes.show');
        Route::get('users','UserController@index')->name('users.index');
        Route::get('user/disable/{token}','UserController@disable')->name('user.disable');
        Route::get('user/enable/{token}','UserController@enable')->name('user.enable');
        Route::get('territoire','TerritoireController@index')->name('territoire');
        Route::get('companies/data','CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');
       // Route::get('dossier/{id}','EntrepriseController@getDossier')->name('dossier.show');
       // Route::get('dossier/instruction/{id}','EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
       // Route::get('instruction/critere/choices','InstructionController@getChoices')->name('instruction.critere.choices');
});

Route::namespace('App\Http\Controllers\Regional')
    ->prefix('regional')
    ->middleware(['auth','regional'])
    ->name('regional.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::get('entreprises','CompanyController@index')->name('entreprises.index');
        Route::get('entreprises/{token}','CompanyController@show')->name('entreprises.show');
        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');
        Route::resource('dossiers','DossierController');
        Route::get('programmes','ProgrammeController@index')->name('programmes.index');
        Route::get('programmes/{token}','ProgrammeController@show')->name('programmes.show');
        Route::get('users','UserController@index')->name('users.index');
        Route::get('user/disable/{token}','UserController@disable')->name('user.disable');
        Route::get('user/enable/{token}','UserController@enable')->name('user.enable');
        Route::get('territoire','TerritoireController@index')->name('territoire');
        Route::get('companies/data','CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');
       // Route::get('dossier/{id}','EntrepriseController@getDossier')->name('dossier.show');
       // Route::get('dossier/instruction/{id}','EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
       // Route::get('instruction/critere/choices','InstructionController@getChoices')->name('instruction.critere.choices');
});

Route::namespace('App\Http\Controllers\Cooperative')
    ->prefix('cooperative')
    ->middleware(['auth','cooperative'])
    ->name('cooperative.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::resource('exploitants','ExploitantController');
        Route::resource('members','MemberController');
        Route::post('member/key','MemberController@addKey')->name('member.add.key');
        Route::resource('entrepots','EntrepotController');
        Route::resource('villages','VillageController');
        Route::resource('entrepots','EntrepotController');
        Route::resource('agents','AgentController');
        Route::resource('mouvements','MouvementController');
        Route::resource('requests','RequestController');
        Route::get('mouvement/data','MouvementController@fetchAll')->name('mouvements.all');

        Route::resource('entrees','EntreeController');
        Route::get('entree/data','EntreeController@fetchAll')->name('entrees.all');

        Route::resource('sorties','SortieController');
        Route::get('sortie/data','SortieController@fetchAll')->name('sorties.all');

        Route::resource('wallets','WalletController');
        Route::post('wallet/recharge','WalletController@recharger')->name('wallet.recharge');
        Route::get('recharges','WalletController@getRecharges')->name('recharges');
        Route::get('wallet/disable/{token}','WalletController@disable')->name('wallet.disable');
        Route::get('wallet/enable/{token}','WalletController@enable')->name('wallet.enable');
        Route::get('kpi/agents/solde','KpiController@getAgentSolde')->name('kpi.agents.solde');
    });

Route::namespace('App\Http\Controllers\Program')
    ->prefix('program')
    ->middleware(['auth','program'])
    ->name('program.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::get('entreprises','CompanyController@index')->name('entreprises.index')->middleware('permission:entreprises.list');
        //Route::resource('entreprises','CompanyController')->middleware();
        //Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');
        //Route::post('entreprise/programme','CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::get('entreprise/tiers/physique/{token}','CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique','CompanyController@saveTiersPhysique')->name('entreprise.physique.save');

        Route::get('entreprise/tiers/morale/{token}','CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::post('entreprise/tiers/morale','CompanyController@saveTiersMorale')->name('entreprise.morale.save');

        Route::get('entreprise/questionnaire/{token}','CompanyController@createQuestionnaire')->name('entreprise.questionnaire');
        Route::post('entreprise/questionnaire','CompanyController@saveQuestionnaire')->name('entreprise.questionnaire.save');

        Route::resource('dossiers','DossierController');

        //Route::resource('programmes','ProgrammeController');
        //Route::post('programme/composante','ProgrammeController@saveComposante')->name('programme.composante.save');
        //Route::post('programme/resultat','ProgrammeController@saveResultat')->name('programme.resultat.save');

        Route::resource('users','UserController');
        //Route::get('territoire','TerritoireController@index')->name('territoire');
        Route::get('companies/data','CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        //Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');


        //Route::resource('entreprises','EntrepriseController');
       Route::get('dossier/{id}','EntrepriseController@getDossier')->name('dossier.show');
        Route::get('dossier/instruction/{id}','EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
        Route::post('engagement','EntrepriseController@setEngagement')->name('entreprise.set.engagement');
        Route::post('dossier/analyse','EntrepriseController@setAnalyse')->name('entreprise.dossier.analyse');
        Route::get('instruction/criteres/params','InstructionController@getCritereParamsForm');
        Route::post('instruction/dossier/dsf','InstructionController@loadDsf')->name('instruction.dsf');
        Route::get('instruction/dossier/create','InstructionController@createDossier')->name('instruction.dossier.create');
        Route::get('instruction/dossier/{id}','InstructionController@getDossier')->name('instruction.dossier');
        Route::get('instruction/dossier','InstructionController@findDossier')->name('instruction.dossier.find');
        Route::get('instruction/critere/choices','InstructionController@getChoices')->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse','InstructionController@saveCritereReponse')->name('instruction.critere.reponse');

    });







Route::get('/home',[HomeController::class,'index'])->name('home')->middleware('auth');
Route::get('/profile',[HomeController::class,'profile'])->name('profile')->middleware('auth');
Route::post('/profile',[HomeController::class,'storeProfile'])->name('profile.store')->middleware('auth');
Route::post('/logout',[HomeController::class,'logout'])->name('logout')->middleware('auth');

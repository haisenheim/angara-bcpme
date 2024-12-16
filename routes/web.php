<?php

use App\Http\Controllers\HomeController;
use App\Models\Agence;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\Produit;
use App\Models\Question;
use App\Models\User;

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

        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::resource('users','UserController');
        Route::get('user/enable/{token}','UserController@enable')->name('user.enable');
        Route::get('user/disable/{token}','UserController@disable')->name('user.disable');
        Route::resource('caisses','CaisseController');
        Route::resource('departements','DepartementController');
        Route::get('departement/enable/{token}','DepartementController@enable')->name('departement.enable');
        Route::get('departement/disable/{token}','DepartementController@disable')->name('departement.disable');
        Route::post('user/caisse','UserController@setCaisse')->name('user.caisse');
        Route::post('user/departement','UserController@setDepartement')->name('user.departement');
        Route::post('caisse/compte','CaisseController@addCompte')->name('caisse.compte');
        Route::post('caisse/set/compte','CaisseController@setCompte')->name('caisse.set.compte');
        Route::post('caisse/add/compte','CaisseController@addCompte')->name('caisse.add.compte');
        Route::get('caisse/enable/{token}','CaisseController@enable')->name('caisse.enable');

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
        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('entreprise/programme','CompanyController@saveProgramme')->name('entreprise.programme.save');
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







Route::get('/home',[HomeController::class,'index'])->name('home')->middleware('auth');
Route::get('/profile',[HomeController::class,'profile'])->name('profile')->middleware('auth');
Route::post('/logout',[HomeController::class,'logout'])->name('logout')->middleware('auth');

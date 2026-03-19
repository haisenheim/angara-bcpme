<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Util\PayementController;
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




Route::get('load',function(){
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




foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // your actual routes
        Route::get('/', function () {
            return redirect(route('login'));
        });

        Route::get('/payment/callback/{token}', [PayementController::class, 'handleCallback'])->name('util.paiement.callback');

        Route::namespace('App\Http\Controllers\Admin')
        ->prefix('admin')
        ->middleware(['auth','admin'])
        ->name('admin.')
        ->group(function(){
            Route::resource('entreprises','CompanyController');
            Route::resource('entites','EntiteController');
            Route::resource('cooperatives','CooperativeController');
            Route::get('tenants/data','CooperativeController@fetchAll')->name('cooperatives.fetchAll');
            Route::get('cooperatives/{token}/stats','CooperativeController@getStats')->name('cooperatives.stats');
            Route::resource('secteurs','SecteurController');
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
            Route::get('villages','TerritoireController@getVillages')->name('villages.index');
            Route::get('villages/{id}','TerritoireController@getVillage')->name('villages.show');
            Route::get('agences','TerritoireController@getAgences')->name('agences.index');
            Route::get('agences/{id}','TerritoireController@getAgence')->name('agences.show');
            Route::get('villages/{id}/edit','TerritoireController@getVillageEdit')->name('villages.edit');
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
    });
}



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
        Route::resource('entites','EntiteController');
        Route::resource('cooperatives','CooperativeController');
        Route::resource('secteurs','SecteurController');
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

        // ESG - Paramétrage
        Route::resource('evaluation-frameworks','EvaluationFrameworkController')->parameters(['evaluation-framework'=>'evaluation_framework']);
        Route::resource('evaluation-categories','EvaluationCategoryController')->parameters(['evaluation-category'=>'evaluation_category']);
        Route::resource('evaluation-indicators','EvaluationIndicatorController')->parameters(['evaluation-indicator'=>'evaluation_indicator']);
        Route::resource('evaluation-thresholds','EvaluationScoreThresholdController')->parameters(['evaluation-threshold'=>'evaluation_threshold']);
        Route::resource('evaluation-settings','EvaluationSettingController')->parameters(['evaluation-setting'=>'evaluation_setting']);
    });

Route::namespace('App\Http\Controllers\Gestionnaire')
    ->prefix('gestionnaire')
    ->middleware(['auth','gestionnaire'])
    ->name('gestionnaire.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');

        // AJAX Dashboard endpoints
        Route::get('dashboard/stats','DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/recent-dossiers','DashboardController@getRecentDossiers')->name('dashboard.recent.dossiers');
        Route::get('dashboard/dossiers-distribution','DashboardController@getDossiersDistribution')->name('dashboard.dossiers.distribution');
        Route::get('dashboard/entreprises-data','DashboardController@getEntreprisesData')->name('dashboard.entreprises.data');

        Route::resource('secteurs','SecteurController');
        // ESG - Profils d'évaluation (avant resource pour éviter conflit de routes)
        Route::get('entreprises/evaluation-profiles','EntrepriseEvaluationProfileController@index')->name('entreprises.evaluation-profiles.index');
        Route::get('entreprises/{entreprise}/evaluation-profile','EntrepriseEvaluationProfileController@show')->name('entreprises.evaluation-profile.show');
        Route::get('entreprises/{entreprise}/evaluation-profile/create','EntrepriseEvaluationProfileController@create')->name('entreprises.evaluation-profile.create');
        Route::post('entreprises/{entreprise}/evaluation-profile','EntrepriseEvaluationProfileController@store')->name('entreprises.evaluation-profile.store');
        Route::get('entreprises/{entreprise}/evaluation-profile/edit','EntrepriseEvaluationProfileController@edit')->name('entreprises.evaluation-profile.edit');
        Route::put('entreprises/{entreprise}/evaluation-profile','EntrepriseEvaluationProfileController@update')->name('entreprises.evaluation-profile.update');
        Route::resource('entreprises','CompanyController');
        Route::resource('entites','EntiteController');
        Route::post('entreprise/save','CompanyController@save')->name('entreprises.save');
        Route::post('entite/save','EntiteController@save')->name('entites.save');

        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('entreprise/programme','CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::post('entreprise/appui','CompanyController@saveAppui')->name('entreprise.appui.save');
        Route::post('entreprise/element','CompanyController@addElement')->name('entreprise.element.save');
        Route::get('entreprise/tiers/physique/{token}','CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique','CompanyController@saveTiersPhysique')->name('entreprise.physique.save');

        Route::get('entreprise/tiers/morale/{token}','CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::post('entreprise/tiers/morale','CompanyController@saveTiersMorale')->name('entreprise.morale.save');

        Route::get('entreprise/questionnaire/{token}','CompanyController@createQuestionnaire')->name('entreprise.questionnaire');
        Route::post('entreprise/questionnaire','CompanyController@saveQuestionnaire')->name('entreprise.questionnaire.save');
        Route::get('entreprise/engagements/{token}','CompanyController@getEngagementReport')->name('entreprise.get.engagements');

        //entites individuelles

        Route::post('entite/programme','EntiteController@saveProgramme')->name('entite.programme.save');
        Route::post('entite/appui','EntiteController@saveAppui')->name('entite.appui.save');
        Route::post('entite/element','EntiteController@addElement')->name('entite.element.save');
        Route::get('entite/tiers/physique/{token}','EntiteController@createTiersPhysique')->name('entite.physique.create');
        Route::post('entite/tiers/physique','EntiteController@saveTiersPhysique')->name('entite.physique.save');

        Route::get('entite/tiers/morale/{token}','EntiteController@createTiersMorale')->name('entite.morale.create');
        Route::post('entite/tiers/morale','EntiteController@saveTiersMorale')->name('entite.morale.save');

        Route::get('entite/questionnaire/{token}','EntiteController@createQuestionnaire')->name('entite.questionnaire');
        Route::post('entite/questionnaire','EntiteController@saveQuestionnaire')->name('entite.questionnaire.save');
        Route::get('entities/data','EntiteController@fetchAll')->name('entites.all');

        Route::get('entite/member/{token}','EntiteController@createFromMember')->name('entite.member.create');
        Route::post('entite/member','EntiteController@storeFromMember')->name('entite.member.store');
        //

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
        Route::get('wallet/enable','WalletController@enable')->name('wallet.enable');
        Route::get('wallet/disable','WalletController@disable')->name('wallet.disable');

        Route::resource('cooperatives','CooperativeController');
        Route::get('cooperative/data','CooperativeController@fetchAll')->name('cooperatives.all');
        Route::post('cooperative/caisse','CooperativeController@addCaisse')->name('cooperative.caisse.add');
        Route::get('cooperative/entrees/{token}','CooperativeController@getEntree')->name('cooperative.entrees.show');
        Route::get('cooperative/entrepots/{token}','CooperativeController@getEntrepot')->name('cooperative.entrepots.show');
        Route::post('cooperative/comptes','CooperativeController@addCompte')->name('cooperative.comptes.add');
        Route::post('cooperative/paiements/export','CooperativeController@exportPaiements')->name('cooperative.paiements.export');
        Route::post('cooperative/entrees/export','CooperativeController@exportEntrees')->name('cooperative.entrees.export');

        Route::get('members/show','MemberController@show')->name('members.show');
        Route::get('members/verger','MemberController@getVerger')->name('members.verger.show');
        //Route::resource('members','MemberController');
        Route::get('member/data','MemberController@fetchAll')->name('members.all');

        Route::get('request//{request_id}','RequestController@getByTenant')->name('request.get');
        Route::resource('entrepots','EntrepotController');
        Route::resource('requests','RequestController');
        Route::post('request/validate','RequestController@valider')->name('request.validate');
        Route::post('request/cancel','RequestController@cancel')->name('request.cancel');
        Route::post('cooperative/users','CooperativeController@addUser')->name('cooperative.users.store');
    });



Route::namespace('App\Http\Controllers\Analyste')
    ->prefix('analyste')
    ->middleware(['auth','analyste'])
    ->name('analyste.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');

        // AJAX Dashboard endpoints
        Route::get('dashboard/stats','DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/dossiers-distribution','DashboardController@getDossiersDistribution')->name('dashboard.dossiers.distribution');
        Route::get('dashboard/monthly-analysis','DashboardController@getMonthlyAnalysis')->name('dashboard.monthly.analysis');
        Route::get('dashboard/recent-dossiers','DashboardController@getRecentDossiers')->name('dashboard.recent.dossiers');
        Route::get('dashboard/performance-metrics','DashboardController@getPerformanceMetrics')->name('dashboard.performance.metrics');
        Route::get('dashboard/alerts','DashboardController@getAlerts')->name('dashboard.alerts');
        Route::get('dashboard/programmes','DashboardController@getProgrammes')->name('dashboard.programmes');
        Route::resource('entreprises','CompanyController');
        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('entreprise/programme','CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::get('entreprise/tiers/physique/{token}','CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique','CompanyController@saveTiersPhysique')->name('entreprise.physique.save');
        Route::get('entreprise/engagements/{token}','EntrepriseController@getEngagementReport')->name('entreprise.get.engagements');
        Route::get('grille/analyse/{token}','DossierController@getGrilleAnalyse')->name('dossier.get.grille.analyse');

        Route::get('entreprise/tiers/morale/{token}','CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::post('entreprise/tiers/morale','CompanyController@saveTiersMorale')->name('entreprise.morale.save');

        Route::get('entreprise/questionnaire/{token}','CompanyController@createQuestionnaire')->name('entreprise.questionnaire');
        Route::post('entreprise/questionnaire','CompanyController@saveQuestionnaire')->name('entreprise.questionnaire.save');

        // ESG - Évaluations (avant resource dossiers)
        Route::get('dossiers/esg-evaluations','DossierEsgEvaluationController@index')->name('dossiers.esg-evaluations.index');
        Route::get('dossiers/{dossier}/esg-evaluation','DossierEsgEvaluationController@show')->name('dossiers.esg-evaluation.show');
        Route::get('dossiers/{dossier}/esg-evaluation/create','DossierEsgEvaluationController@create')->name('dossiers.esg-evaluation.create');
        Route::post('dossiers/{dossier}/esg-evaluation','DossierEsgEvaluationController@store')->name('dossiers.esg-evaluation.store');
        Route::get('dossiers/{dossier}/esg-evaluation/edit','DossierEsgEvaluationController@edit')->name('dossiers.esg-evaluation.edit');
        Route::put('dossiers/{dossier}/esg-evaluation','DossierEsgEvaluationController@update')->name('dossiers.esg-evaluation.update');
        Route::post('dossiers/{dossier}/esg-evaluation/submit','DossierEsgEvaluationController@submit')->name('dossiers.esg-evaluation.submit');
        Route::post('dossiers/{dossier}/esg-evaluation/rebuild-scores','DossierEsgEvaluationController@rebuildScores')->name('dossiers.esg-evaluation.rebuild-scores');
        Route::resource('dossiers','DossierController');
        Route::post('dossier/dsf','DossierController@loadDsf')->name('dossier.dsf');
        Route::resource('programmes','ProgrammeController');
        Route::post('programme/composante','ProgrammeController@saveComposante')->name('programme.composante.save');
        Route::post('programme/resultat','ProgrammeController@saveResultat')->name('programme.resultat.save');

        Route::resource('users','UserController');
        Route::get('territoire','TerritoireController@index')->name('territoire');
        Route::get('companies/data','CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/paginated','CompanyController@fetchPaginated')->name('entreprises.paginated');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');


        //Route::resource('entreprises','EntrepriseController');
        Route::get('dossier/{id}','EntrepriseController@getDossier')->name('dossier.show');
        Route::get('dossier/instruction/{id}','EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
        Route::post('engagement','EntrepriseController@setEngagement')->name('entreprise.set.engagement');

        Route::post('dossier/analyse','DossierController@setAnalyse')->name('dossier.set.analyse');


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

        // AJAX Dashboard endpoints
        Route::get('dashboard/stats','DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/performance-data','DashboardController@getPerformanceData')->name('dashboard.performance.data');
        Route::get('dashboard/team-performance','DashboardController@getTeamPerformance')->name('dashboard.team.performance');
        Route::get('dashboard/recent-dossiers','DashboardController@getRecentDossiers')->name('dashboard.recent.dossiers');
        Route::get('dashboard/monthly-stats','DashboardController@getMonthlyStats')->name('dashboard.monthly.stats');
        Route::get('dashboard/alerts','DashboardController@getAlerts')->name('dashboard.alerts');
        Route::get('entreprises','CompanyController@index')->name('entreprises.index');
        Route::get('entreprises/{token}','CompanyController@show')->name('entreprises.show');
        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('entreprise/programme','CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::get('entreprise/engagements/{token}','CompanyController@getEngagementReport')->name('entreprise.get.engagements');
        //entites individuelles
        Route::get('entites','EntiteController@index')->name('entites.index');
        Route::get('entites/{token}','EntiteController@show')->name('entites.show');
        Route::post('entite/programme','EntiteController@saveProgramme')->name('entite.programme.save');
        Route::get('entities/data','EntiteController@fetchAll')->name('entites.all');
        //


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
        Route::get('instruction/critere/choices',[\App\Http\Controllers\Gestionnaire\InstructionController::class,'getChoices'])->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse',[\App\Http\Controllers\Gestionnaire\InstructionController::class,'saveCritereReponse'])->name('instruction.critere.reponse');
        Route::post('dossier/analyse','DossierController@setAnalyse')->name('dossier.set.analyse');

        Route::resource('wallets','WalletController');

        Route::resource('cooperatives','CooperativeController');
        Route::get('cooperative/data','CooperativeController@fetchAll')->name('cooperatives.all');

        Route::resource('members','MemberController');
        Route::get('member/data','MemberController@fetchAll')->name('members.all');

        Route::resource('entrepots','EntrepotController');
        Route::resource('requests','RequestController');
        Route::post('request/validate','RequestController@valider')->name('request.validate');
        Route::post('request/cancel','RequestController@cancel')->name('request.cancel');

        // ESG - Validation des évaluations
        Route::get('esg-evaluations','DossierEsgValidationController@index')->name('esg-evaluations.index');
        Route::get('esg-evaluations/dashboard','DossierEsgValidationController@dashboard')->name('esg-evaluations.dashboard');
        Route::get('esg-evaluations/{evaluation}','DossierEsgValidationController@show')->name('esg-evaluations.show');
        Route::post('esg-evaluations/{evaluation}/validate','DossierEsgValidationController@validateEvaluation')->name('esg-evaluations.validate');
        Route::post('esg-evaluations/{evaluation}/reject','DossierEsgValidationController@rejectEvaluation')->name('esg-evaluations.reject');
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



Route::namespace('App\Http\Controllers\Program')
    ->prefix('program')
    ->middleware(['auth','program'])
    ->name('program.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::get('entreprises','CompanyController@index')->name('entreprises.index')->middleware('permission:entreprises.list');
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

Route::namespace('App\Http\Controllers\Sectoriel')
    ->prefix('sectoriel')
    ->middleware(['auth','sectoriel'])
    ->name('sectoriel.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');

        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');

        //entites individuelles
        Route::get('members','MemberController@show')->name('members.show');
        Route::post('members/verger','MemberController@addVerger')->name('members.verger.add');
        Route::get('members/verger','MemberController@getVerger')->name('members.verger.show');

        Route::post('members/verger/campagne','MemberController@addCampagneVerger')->name('verger.campagne.add');
        Route::post('members/verger/travail','MemberController@addTravailCampagne')->name('campagne.travail.add');
        Route::post('members/verger/traitement','MemberController@addTraitementCampagne')->name('campagne.traitement.add');
        Route::post('members/verger/rendement','MemberController@setRendementCampagne')->name('campagne.rendement');
        Route::post('members/verger/visite','MemberController@addVisiteCampagne')->name('campagne.visite.add');

        Route::resource('wallets','WalletController');
        Route::resource('caisses','CaisseController');
        Route::resource('requests','RequestController');
        Route::resource('programmes','ProgrammeController');
        Route::resource('users','UserController');
        Route::get('territoire','TerritoireController@index')->name('territoire');


        Route::resource('villages','VillageController');

        Route::resource('cooperatives','CooperativeController');
        Route::get('cooperative/data','CooperativeController@fetchAll')->name('cooperatives.all');
        Route::post('cooperative/caisse','CooperativeController@addCaisse')->name('cooperative.caisse.add');
        Route::get('cooperative/entrees/{token}','CooperativeController@getEntree')->name('cooperative.entrees.show');
        Route::get('cooperative/entrepot','CooperativeController@getEntrepot')->name('entrepots.show');

        Route::post('cooperative/paiements/export','CooperativeController@exportPaiements')->name('cooperative.paiements.export');
        Route::post('cooperative/entrees/export','CooperativeController@exportEntrees')->name('cooperative.entrees.export');



});


Route::namespace('App\Http\Controllers\Structuration\Banquier')
    ->prefix('structuration/gestionnaire')
    ->middleware(['auth','structuration.gestionnaire'])
    ->name('structuration_gestionnaire.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::resource('requests','RequestController');
        Route::resource('comptes','CompteController');
        Route::post('request/validate','RequestController@valider')->name('request.validate');
        Route::post('request/cancel','RequestController@cancel')->name('request.cancel');
    });







Route::get('/home',[HomeController::class,'index'])->name('home')->middleware('auth');
Route::get('/profile',[HomeController::class,'profile'])->name('profile')->middleware('auth');
Route::post('/profile',[HomeController::class,'storeProfile'])->name('profile.store')->middleware('auth');
Route::post('/logout',[HomeController::class,'logout'])->name('logout')->middleware('auth');

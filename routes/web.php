<?php

use App\Http\Controllers\HomeController;
use App\Imports\ApmeImport;
use App\Models\Agence;
use Illuminate\Support\Facades\Route;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
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

/*
| Page d’accueil de l’application centrale BC-PME.
*/
Route::get('/', function () {
    return view('accueil');
})->name('accueil');

$registerGovernanceSpace = function (string $prefix, string $middleware, string $name) {
    Route::prefix($prefix)
        ->middleware(['auth', $middleware])
        ->name($name.'.')
        ->group(function () {
            Route::get('dashboard', [\App\Http\Controllers\RoleSpace\DashboardController::class, 'index'])->name('dashboard');
            Route::get('entreprises', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entreprisesIndex'])->name('entreprises.index');
            Route::get('entreprises/{token}', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entrepriseShow'])->name('entreprises.show');
            Route::get('entreprises/{token}/pieces', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entreprisePieces'])->name('entreprises.pieces');
            Route::get('dossiers', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossiersIndex'])->name('dossiers.index');
            Route::get('dossiers/{token}', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierShow'])->name('dossiers.show');
        });
};

$registerGovernanceSpace('pca', 'pca', 'pca');
$registerGovernanceSpace('administrateur', 'adm', 'administrateur');
$registerGovernanceSpace('dg', 'dg', 'dg');
$registerGovernanceSpace('dga', 'dga', 'dga');
$registerGovernanceSpace('controleur', 'respci', 'controleur');
$registerGovernanceSpace('auditeur', 'respaud', 'auditeur');



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
        Route::resource('secteurs','SecteurController');
        Route::get('prospects','CompanyController@getProspects')->name('entreprises.prospects');

        Route::post('entreprise/programme','CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::post('programme/poste','ProgrammeController@savePoste')->name('programme.poste.save');
        Route::post('programme/user','ProgrammeController@saveUser')->name('programme.user.save');

        Route::get('entreprise/tiers/physique/{token}','CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique','CompanyController@saveTiersPhysique')->name('entreprise.physique.save');

        Route::get('entreprise/tiers/morale/{token}','CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::get('entreprise/tiers/morale/search/portfolio','CompanyController@searchTierMoralePortfolio')->name('entreprise.morale.search');
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
        Route::resource('pieces-exigibles', 'PieceExigibleDefinitionController')->except(['destroy']);
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
        Route::get('user/enable/{token}','UserController@enable')->name('user.enable');
        Route::get('user/disable/{token}','UserController@disable')->name('user.disable');

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

        // AJAX Dashboard endpoints
        Route::get('dashboard/stats','DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/recent-dossiers','DashboardController@getRecentDossiers')->name('dashboard.recent.dossiers');
        Route::get('dashboard/dossiers-distribution','DashboardController@getDossiersDistribution')->name('dashboard.dossiers.distribution');
        Route::get('dashboard/entreprises-data','DashboardController@getEntreprisesData')->name('dashboard.entreprises.data');

        Route::resource('secteurs','SecteurController');
        Route::get('entreprises/prospects/create','CompanyController@createProspect')->name('entreprises.prospects.create');
        Route::post('entreprises/prospects','CompanyController@storeProspect')->name('entreprises.prospects.store');
        Route::post('entreprises/prospects/{token}/submit','CompanyController@submitProspect')->name('entreprises.prospects.submit');
        Route::get('entreprises/{token}/pieces-exigibles', 'PieceExigibleController@index')->name('entreprises.pieces-exigibles.index');
        Route::post('entreprises/{token}/pieces-exigibles/{definition}', 'PieceExigibleController@store')->name('entreprises.pieces-exigibles.store');
        Route::get('entreprises/{token}/analyse-critique', 'AnalyseCritiqueController@show')->name('entreprises.analyse-critique.show');
        Route::post('entreprises/{token}/analyse-critique', 'AnalyseCritiqueController@update')->name('entreprises.analyse-critique.update');
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
        Route::get('entreprise/tiers/morale/search/portfolio','CompanyController@searchTierMoralePortfolio')->name('entreprise.morale.search');
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

        Route::resource('dossiers','DossierController');

        Route::resource('programmes','ProgrammeController', ['except' => ['create', 'store']]);
        Route::post('programme/composante','ProgrammeController@saveComposante')->name('programme.composante.save');
        Route::post('programme/resultat','ProgrammeController@saveResultat')->name('programme.resultat.save');

        Route::resource('users','UserController');
        Route::get('territoire','TerritoireController@index')->name('territoire');
        Route::get('companies/data','CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/data/paginated','CompanyController@fetchPaginated')->name('entreprises.paginated');
        Route::get('companies/stats','CompanyController@fetchStats')->name('entreprises.stats');
        Route::get('companies/filter-options','CompanyController@fetchFilterOptions')->name('entreprises.filter-options');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        Route::get('companies/prospects/paginated','CompanyController@fetchProspectsPaginated')->name('prospects.paginated');
        Route::get('companies/prospects/stats','CompanyController@fetchProspectsStats')->name('prospects.stats');
        Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('programs/data/paginated','ProgrammeController@fetchPaginated')->name('programmes.paginated');
        Route::get('programs/stats','ProgrammeController@fetchStats')->name('programmes.stats');
        Route::get('programs/filter-options','ProgrammeController@fetchFilterOptions')->name('programmes.filter-options');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');
        Route::get('folders/data/paginated','DossierController@fetchPaginated')->name('dossiers.paginated');
        Route::get('folders/stats','DossierController@fetchStats')->name('dossiers.stats');
        Route::get('folders/filter-options','DossierController@fetchFilterOptions')->name('dossiers.filter-options');
        Route::get('grille/analyse/{token}','DossierController@getGrilleAnalyse')->name('dossier.get.grille.analyse');
        Route::post('dossier/grille/analyse','DossierController@setAnalyse')->name('dossier.set.analyse');

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

        // AJAX Dashboard endpoints
        Route::get('dashboard/stats','DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/dossiers-distribution','DashboardController@getDossiersDistribution')->name('dashboard.dossiers.distribution');
        Route::get('dashboard/monthly-analysis','DashboardController@getMonthlyAnalysis')->name('dashboard.monthly.analysis');
        Route::get('dashboard/recent-dossiers','DashboardController@getRecentDossiers')->name('dashboard.recent.dossiers');
        Route::get('dashboard/performance-metrics','DashboardController@getPerformanceMetrics')->name('dashboard.performance.metrics');
        Route::get('dashboard/alerts','DashboardController@getAlerts')->name('dashboard.alerts');
        Route::get('dashboard/programmes','DashboardController@getProgrammes')->name('dashboard.programmes');
        Route::get('entreprises/data/paginated','CompanyController@fetchPaginated')->name('entreprises.paginated');
        Route::get('entreprises/stats','CompanyController@fetchEntreprisesIndexStats')->name('entreprises.stats');
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

        Route::resource('dossiers','DossierController');
        Route::post('dossier/dsf','DossierController@loadDsf')->name('dossier.dsf');
        Route::resource('programmes','ProgrammeController', ['except' => ['create', 'store']]);
        Route::post('programme/composante','ProgrammeController@saveComposante')->name('programme.composante.save');
        Route::post('programme/resultat','ProgrammeController@saveResultat')->name('programme.resultat.save');

        Route::resource('users','UserController');
        Route::get('territoire','TerritoireController@index')->name('territoire');
        Route::get('companies/data','CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/filter-options','CompanyController@fetchFilterOptions')->name('entreprises.filter-options');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        Route::get('companies/prospects/paginated','CompanyController@fetchProspectsPaginated')->name('prospects.paginated');
        Route::get('companies/prospects/stats','CompanyController@fetchProspectsStats')->name('prospects.stats');
        Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('programs/data/paginated','ProgrammeController@fetchPaginated')->name('programmes.paginated');
        Route::get('programs/stats','ProgrammeController@fetchStats')->name('programmes.stats');
        Route::get('programs/filter-options','ProgrammeController@fetchFilterOptions')->name('programmes.filter-options');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');
        Route::get('folders/data/paginated','DossierController@fetchPaginated')->name('dossiers.paginated');
        Route::get('folders/stats','DossierController@fetchStats')->name('dossiers.stats');
        Route::get('folders/filter-options','DossierController@fetchFilterOptions')->name('dossiers.filter-options');


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

Route::namespace('App\Http\Controllers\AnalysteCredit')
    ->prefix('analyste-credit')
    ->middleware(['auth','analyste.credit'])
    ->name('analyste-credit.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
    });

Route::namespace('App\Http\Controllers\AnalysteJuridique')
    ->prefix('analyste-juridique')
    ->middleware(['auth','analyste.juridique'])
    ->name('analyste-juridique.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
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
        Route::get('companies/data/paginated','CompanyController@fetchPaginated')->name('entreprises.paginated');
        Route::get('companies/stats','CompanyController@fetchStats')->name('entreprises.stats');
        Route::get('companies/filter-options','CompanyController@fetchFilterOptions')->name('entreprises.filter-options');
        Route::get('companies/all/prospects','CompanyController@fetchProspects')->name('prospects.all');
        Route::get('companies/prospects/paginated','CompanyController@fetchProspectsPaginated')->name('prospects.paginated');
        Route::get('companies/prospects/stats','CompanyController@fetchProspectsStats')->name('prospects.stats');
        Route::get('programs/data','ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('folders/data','DossierController@fetchAll')->name('dossiers.all');
        Route::get('folders/data/paginated','DossierController@fetchPaginated')->name('dossiers.paginated');
        Route::get('folders/stats','DossierController@fetchStats')->name('dossiers.stats');
        Route::get('folders/filter-options','DossierController@fetchFilterOptions')->name('dossiers.filter-options');
        Route::get('instruction/critere/choices',[\App\Http\Controllers\Gestionnaire\InstructionController::class,'getChoices'])->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse',[\App\Http\Controllers\Gestionnaire\InstructionController::class,'saveCritereReponse'])->name('instruction.critere.reponse');
        Route::post('dossier/analyse','DossierController@setAnalyse')->name('dossier.set.analyse');
        Route::get('dossier/grille/analyse/{token}','DossierController@getGrilleAnalyse')->name('dossier.get.grille.analyse');

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

Route::namespace('App\Http\Controllers\Program')
    ->prefix('program')
    ->middleware(['auth','program'])
    ->name('program.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::get('entreprises','CompanyController@index')->name('entreprises.index');
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

Route::namespace('App\Http\Controllers\ChefFiliere')
    ->prefix('chef-filiere')
    ->middleware(['auth','chef.filiere'])
    ->name('chef-filiere.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::get('qualifications','QualificationController@index')->name('qualifications.index');
        Route::get('qualifications/{token}','QualificationController@show')->name('qualifications.show');
        Route::post('qualifications/{token}','QualificationController@update')->name('qualifications.update');
        Route::post('qualifications/{token}/programmes','QualificationController@saveProgrammes')->name('qualifications.programmes.save');
        Route::post('qualifications/{token}/submit','QualificationController@submit')->name('qualifications.submit');
    });

Route::namespace('App\Http\Controllers\ChefAgence')
    ->prefix('chef-agence')
    ->middleware(['auth','chef.agence'])
    ->name('chef-agence.')
    ->group(function(){
        Route::get('dashboard','DashboardController@index')->name('dashboard');
        Route::get('prospects','ProspectController@index')->name('prospects.index');
        Route::get('prospects/{token}','ProspectController@show')->name('prospects.show');
        Route::post('prospects/{token}/approve','ProspectController@approve')->name('prospects.approve');
        Route::get('instructions','ProspectController@instructionIndex')->name('instructions.index');
        Route::get('instructions/{token}','ProspectController@instructionShow')->name('instructions.show');
        Route::post('instructions/{token}/approve','ProspectController@approveInstruction')->name('instructions.approve');
    });







Route::namespace('App\Http\Controllers\Juridique')
    ->prefix('juridique')
    ->middleware(['auth', 'reju'])
    ->name('juridique.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('entreprises', 'CompanyController@index')->name('entreprises.index');
        Route::get('entreprises/{token}', 'CompanyController@show')->name('entreprises.show');
        Route::get('prospects', 'ProspectController@index')->name('prospects.index');
        Route::get('prospects/{token}', 'ProspectController@show')->name('prospects.show');
        Route::post('prospects/{token}/avis', 'ProspectController@store')->name('prospects.avis');
    });

Route::namespace('App\Http\Controllers\Conformite')
    ->prefix('conformite')
    ->middleware(['auth', 'reconf'])
    ->name('conformite.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('prospects', 'ProspectController@index')->name('prospects.index');
        Route::get('prospects/{token}', 'ProspectController@show')->name('prospects.show');
        Route::post('prospects/{token}/avis', 'ProspectController@store')->name('prospects.avis');
    });

Route::get('/home',[HomeController::class,'index'])->name('home')->middleware('auth');
Route::get('/profile',[HomeController::class,'profile'])->name('profile')->middleware('auth');
Route::post('/profile',[HomeController::class,'storeProfile'])->name('profile.store')->middleware('auth');
Route::post('/logout',[HomeController::class,'logout'])->name('logout')->middleware('auth');

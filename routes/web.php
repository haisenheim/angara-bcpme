<?php

use App\Http\Controllers\RoleSpace\PortfolioController;
use App\Http\Controllers\HomeController;
use App\Imports\ApmeImport;
use App\Models\Agence;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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

Route::get('apme', function () {
    // $file = request()->fichier;
    Excel::import(new ApmeImport, public_path('files/apme.xlsx'));

    return 'Ok';
});

Route::get('load', function () {
    $users = User::whereNull('token')->get();
    foreach ($users as $user) {
        $names = explode(' ', $user->name);
        $agence = Agence::find($user->agence_id);
        $user->representation_id = $agence->representation_id;
        $email = strtolower(substr($names[1], 0, 1)).'.'.strtolower($names[0]).rand(10, 99).'@angara.com';
        $user->password = bcrypt('1234');
        $user->token = sha1($user->id.rand(1, 999));
        $user->email = $email;
        $user->phone = '6'.rand(56329020, 996772878);
        $user->save();
        echo $email.'<br/>';
    }

    // dd($produits);
    return 'ok';
});

Route::get('questions', function () {
    $chars = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
    $questions = Question::all();
    foreach ($questions as $q) {
        $ch = substr(trim($q->name), 0, 1);
        for ($i = 0; $i < 21; $i++) {
            if ($chars[$i] == strtoupper($ch)) {
                $q->sous_critere_id = $i + 1;
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
        ->group(function () use ($name) {
            Route::get('dashboard', [\App\Http\Controllers\RoleSpace\DashboardController::class, 'index'])->name('dashboard');
            Route::get('dashboard/stats', [\App\Http\Controllers\RoleSpace\DashboardController::class, 'stats'])->name('dashboard.stats');
            Route::get('dashboard/todos', [\App\Http\Controllers\RoleSpace\DashboardController::class, 'todos'])->name('dashboard.todos');
            Route::get('entreprises', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entreprisesIndex'])->name('entreprises.index');
            Route::get('entreprises-export', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entreprisesExport'])->name('entreprises.export');
            Route::get('entreprises/{token}/engagements', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entrepriseEngagementReport'])->name('entreprises.engagements');
            Route::get('entreprises/{token}', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entrepriseShow'])->name('entreprises.show');
            Route::get('entreprises/{token}/fiche/pdf', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entrepriseFichePdf'])->name('entreprises.fiche.pdf');
            Route::get('entreprises/{token}/pieces', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entreprisePieces'])->name('entreprises.pieces');
            if (in_array($name, ['dg', 'dga'], true)) {
                Route::get('dossiers/en-attente-direction', [PortfolioController::class, 'dossiersIndex'])->name('dossiers.en-attente-direction');
                Route::get('dossiers/valides-chef-agence', [PortfolioController::class, 'dossiersIndex'])->name('dossiers.valides-chef-agence');
            }
            Route::get('dossiers', [PortfolioController::class, 'dossiersIndex'])->name('dossiers.index');
            Route::get('dossiers-export', [PortfolioController::class, 'dossiersExport'])->name('dossiers.export');
            Route::post('dossiers/{token}/pieces', [PortfolioController::class, 'storeDossierPiece'])->name('dossiers.pieces.store');
            Route::get('dossiers/{token}', [PortfolioController::class, 'dossierShow'])->name('dossiers.show');
        });
};

$registerGovernanceSpace('pca', 'pca', 'pca');
$registerGovernanceSpace('administrateur', 'adm', 'administrateur');
$registerGovernanceSpace('dg', 'dg', 'dg');
$registerGovernanceSpace('dga', 'dga', 'dga');
$registerGovernanceSpace('respexp', 'respexp', 'respexp');

Route::prefix('respexp')
    ->middleware(['auth', 'respexp'])
    ->name('respexp.')
    ->group(function () {
        Route::post('dossiers/{token}/assign-analyste', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'assignAnalyste'])->name('dossiers.assign-analyste');
        Route::post('dossiers/{token}/avis-credit', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'storeExploitationAvisCredit'])->name('dossiers.avis-credit');
        Route::post('dossiers/{token}/validation-engagements', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'storeExploitationEngagementsDecision'])->name('dossiers.validation-engagements');
        Route::post('dossiers/{token}/soumettre-juridique', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'storeSoumettreJuridique'])->name('dossiers.soumettre-juridique');
        // Rejet intermédiaire de la soumission de l'analyste financier (réouverture de l'étape AF).
        Route::post('dossiers/{token}/rejeter-analyste', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'rejectExploitationAnalyste'])->name('dossiers.rejeter-analyste');
        Route::get('dossiers/{token}/instruction', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierInstructionShow'])->name('dossiers.instruction');
        Route::get('dossiers/{token}/instruction/pdf', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierInstructionPdf'])->name('dossiers.instruction.pdf');
        Route::get('dossiers/{token}/dossier-analyse-critique', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierAnalyseCritiqueSyntheseShow'])->name('dossiers.dossier-analyse-critique');
        Route::get('dossiers/{token}/dossier-analyse-critique/pdf', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierAnalyseCritiqueSynthesePdf'])->name('dossiers.dossier-analyse-critique.pdf');
        Route::get('dossiers/{token}/analyse-critique', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierAnalyseCritiqueShow'])->name('dossiers.analyse-critique');
    });

$registerGovernanceSpace('respaud', 'respaud', 'respaud');
$registerGovernanceSpace('respci', 'respci', 'respci');
$registerGovernanceSpace('reng', 'reng', 'reng');
$registerGovernanceSpace('rerx', 'rerx', 'rerx');

Route::prefix('reng')
    ->middleware(['auth', 'reng'])
    ->name('reng.')
    ->group(function () {
        Route::post('dossiers/{token}/assign-analyste-credit', [PortfolioController::class, 'assignRengAnalysteCredit'])->name('dossiers.assign-analyste-credit');
        Route::post('dossiers/{token}/responsable-avis', [PortfolioController::class, 'storeRengResponsableAvis'])->name('dossiers.responsable-avis');
        Route::post('dossiers/{token}/soumettre-risques', [PortfolioController::class, 'submitRengToRisques'])->name('dossiers.soumettre-risques');
        // Rejet intermédiaire de la soumission de l'analyste crédit (réouverture de l'étape AC).
        Route::post('dossiers/{token}/rejeter-analyste-credit', [PortfolioController::class, 'rejectRengAnalysteCredit'])->name('dossiers.rejeter-analyste-credit');
        // Rejet inter-pôle : RENG renvoie le dossier vers le pôle juridique (réouverture RJU).
        Route::post('dossiers/{token}/rejeter-vers-juridique', [PortfolioController::class, 'rejectEngagementsToJuridique'])->name('dossiers.rejeter-vers-juridique');
        Route::get('dossiers/{token}/instruction', [PortfolioController::class, 'dossierInstructionShow'])->name('dossiers.instruction');
        Route::get('dossiers/{token}/instruction/pdf', [PortfolioController::class, 'dossierInstructionPdf'])->name('dossiers.instruction.pdf');
        Route::get('dossiers/{token}/dossier-analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueSyntheseShow'])->name('dossiers.dossier-analyse-critique');
        Route::get('dossiers/{token}/dossier-analyse-critique/pdf', [PortfolioController::class, 'dossierAnalyseCritiqueSynthesePdf'])->name('dossiers.dossier-analyse-critique.pdf');
        Route::get('dossiers/{token}/analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueShow'])->name('dossiers.analyse-critique');
    });

Route::prefix('rerx')
    ->middleware(['auth', 'rerx'])
    ->name('rerx.')
    ->group(function () {
        Route::post('dossiers/{token}/assign-analyste-risques', [PortfolioController::class, 'assignRerxAnalysteRisques'])->name('dossiers.assign-analyste-risques');
        Route::post('dossiers/{token}/responsable-avis', [PortfolioController::class, 'storeRerxResponsableAvis'])->name('dossiers.responsable-avis');
        Route::post('dossiers/{token}/soumettre-direction', [PortfolioController::class, 'submitRerxToDirection'])->name('dossiers.soumettre-direction');
        // Rejet intermédiaire de la soumission de l'analyste risques (réouverture de l'étape AR).
        Route::post('dossiers/{token}/rejeter-analyste-risques', [PortfolioController::class, 'rejectRerxAnalysteRisques'])->name('dossiers.rejeter-analyste-risques');
        // Rejet inter-pôle : RISQ renvoie le dossier vers le pôle engagements (réouverture RENG).
        Route::post('dossiers/{token}/rejeter-vers-engagements', [PortfolioController::class, 'rejectRisquesToEngagements'])->name('dossiers.rejeter-vers-engagements');
        Route::get('dossiers/{token}/instruction', [PortfolioController::class, 'dossierInstructionShow'])->name('dossiers.instruction');
        Route::get('dossiers/{token}/instruction/pdf', [PortfolioController::class, 'dossierInstructionPdf'])->name('dossiers.instruction.pdf');
        Route::get('dossiers/{token}/dossier-analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueSyntheseShow'])->name('dossiers.dossier-analyse-critique');
        Route::get('dossiers/{token}/dossier-analyse-critique/pdf', [PortfolioController::class, 'dossierAnalyseCritiqueSynthesePdf'])->name('dossiers.dossier-analyse-critique.pdf');
        Route::get('dossiers/{token}/analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueShow'])->name('dossiers.analyse-critique');
    });

Route::prefix('dg')
    ->middleware(['auth', 'dg'])
    ->name('dg.')
    ->group(function () {
        Route::get('dossiers/{token}/instruction', [PortfolioController::class, 'dossierInstructionShow'])->name('dossiers.instruction');
        Route::get('dossiers/{token}/instruction/pdf', [PortfolioController::class, 'dossierInstructionPdf'])->name('dossiers.instruction.pdf');
        Route::get('dossiers/{token}/dossier-analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueSyntheseShow'])->name('dossiers.dossier-analyse-critique');
        Route::get('dossiers/{token}/dossier-analyse-critique/pdf', [PortfolioController::class, 'dossierAnalyseCritiqueSynthesePdf'])->name('dossiers.dossier-analyse-critique.pdf');
        Route::get('dossiers/{token}/analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueShow'])->name('dossiers.analyse-critique');
        Route::get('programmes', [\App\Http\Controllers\Ca\ProgrammeController::class, 'index'])->name('programmes.index');
        Route::get('programmes/{token}', [\App\Http\Controllers\Ca\ProgrammeController::class, 'show'])->name('programmes.show');
        Route::get('programs/data', [\App\Http\Controllers\Ca\ProgrammeController::class, 'fetchAll'])->name('programmes.all');
        Route::get('programs/data/paginated', [\App\Http\Controllers\Ca\ProgrammeController::class, 'fetchPaginated'])->name('programmes.paginated');
        Route::get('programs/stats', [\App\Http\Controllers\Ca\ProgrammeController::class, 'fetchStats'])->name('programmes.stats');
        Route::get('programs/filter-options', [\App\Http\Controllers\Ca\ProgrammeController::class, 'fetchFilterOptions'])->name('programmes.filter-options');
        Route::get('users', [\App\Http\Controllers\Ca\UserController::class, 'index'])->name('users.index');
        Route::get('user/disable/{token}', [\App\Http\Controllers\Ca\UserController::class, 'disable'])->name('user.disable');
        Route::get('user/enable/{token}', [\App\Http\Controllers\Ca\UserController::class, 'enable'])->name('user.enable');
    });

Route::prefix('dga')
    ->middleware(['auth', 'dga'])
    ->name('dga.')
    ->group(function () {
        Route::get('dossiers/{token}/instruction', [PortfolioController::class, 'dossierInstructionShow'])->name('dossiers.instruction');
        Route::get('dossiers/{token}/instruction/pdf', [PortfolioController::class, 'dossierInstructionPdf'])->name('dossiers.instruction.pdf');
        Route::get('dossiers/{token}/dossier-analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueSyntheseShow'])->name('dossiers.dossier-analyse-critique');
        Route::get('dossiers/{token}/dossier-analyse-critique/pdf', [PortfolioController::class, 'dossierAnalyseCritiqueSynthesePdf'])->name('dossiers.dossier-analyse-critique.pdf');
        Route::get('dossiers/{token}/analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueShow'])->name('dossiers.analyse-critique');
        Route::get('programmes', [\App\Http\Controllers\Ca\ProgrammeController::class, 'index'])->name('programmes.index');
        Route::get('programmes/{token}', [\App\Http\Controllers\Ca\ProgrammeController::class, 'show'])->name('programmes.show');
        Route::get('programs/data', [\App\Http\Controllers\Ca\ProgrammeController::class, 'fetchAll'])->name('programmes.all');
        Route::get('programs/data/paginated', [\App\Http\Controllers\Ca\ProgrammeController::class, 'fetchPaginated'])->name('programmes.paginated');
        Route::get('programs/stats', [\App\Http\Controllers\Ca\ProgrammeController::class, 'fetchStats'])->name('programmes.stats');
        Route::get('programs/filter-options', [\App\Http\Controllers\Ca\ProgrammeController::class, 'fetchFilterOptions'])->name('programmes.filter-options');
        Route::get('users', [\App\Http\Controllers\Ca\UserController::class, 'index'])->name('users.index');
        Route::get('user/disable/{token}', [\App\Http\Controllers\Ca\UserController::class, 'disable'])->name('user.disable');
        Route::get('user/enable/{token}', [\App\Http\Controllers\Ca\UserController::class, 'enable'])->name('user.enable');
    });
$registerGovernanceSpace('controleur', 'controleur', 'controleur');
$registerGovernanceSpace('auditeur', 'auditeur', 'auditeur');

Route::namespace('App\Http\Controllers\Util')
    ->prefix('util')
    ->name('util.')
    ->group(function () {
        Route::get('region/departements', 'SearchController@getDepartementsByRegionId')->name('region.departements');
        Route::get('departement/arrondissements', 'SearchController@getArrondissementsByDepartementId')->name('departement.arrondissements');
        Route::get('arrondissement/villages', 'SearchController@getVillagesByArrondissementId')->name('arrondissement.villages');

        Route::get('localites', 'SearchController@getLocalites')->name('localites');
        Route::get('organismes', 'SearchController@getOrganismes')->name('organismes');
        Route::get('ville/agences', 'SearchController@getAgencesByVilleId')->name('ville.agences');
        Route::get('produits/all', 'SearchController@fetchAllProduit')->name('produits.list');
        Route::get('services/afs', 'SearchController@fetchAfs')->name('afs.list');
        Route::get('services/anfs', 'SearchController@fetchAnfs')->name('anfs.list');
        Route::get('entreprise/create/date', 'SearchController@loadEntrepriseData')->name('entreprise.create.data');
        Route::get('programme/create/date', 'SearchController@loadProgrammeData')->name('programme.create.data');

    });

Route::namespace('App\Http\Controllers\Admin')
    ->prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('entreprises-export', 'CompanyController@exportEntreprisesClients')->name('entreprises.export');
        Route::resource('entreprises', 'CompanyController');
        Route::resource('secteurs', 'SecteurController');
        Route::get('prospects', 'CompanyController@getProspects')->name('entreprises.prospects');

        Route::post('entreprise/programme', 'CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::get('entreprise/tiers/physique/{token}', 'CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique', 'CompanyController@saveTiersPhysique')->name('entreprise.physique.save');

        Route::get('entreprise/tiers/morale/{token}', 'CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::get('entreprise/tiers/morale/search/portfolio', 'CompanyController@searchTierMoralePortfolio')->name('entreprise.morale.search');
        Route::post('entreprise/tiers/morale', 'CompanyController@saveTiersMorale')->name('entreprise.morale.save');

        Route::get('entreprise/questionnaire/{token}', 'CompanyController@createQuestionnaire')->name('entreprise.questionnaire');
        Route::post('entreprise/questionnaire', 'CompanyController@saveQuestionnaire')->name('entreprise.questionnaire.save');

        Route::post('dossier/{token}/pieces', 'DossierController@storeDossierPiece')->name('dossier.pieces.store');
        Route::resource('dossiers', 'DossierController');

        Route::resource('delegation-pouvoirs', 'DelegationPouvoirController')->except(['show']);

        Route::resource('programmes', 'ProgrammeController');
        Route::post('programme/composante', 'ProgrammeController@saveComposante')->name('programme.composante.save');
        Route::post('programme/appui', 'ProgrammeController@saveAppui')->name('programme.appui.save');
        Route::post('programme/produit', 'ProgrammeController@saveProduit')->name('programme.produit.save');
        Route::post('programme/resultat', 'ProgrammeController@saveResultat')->name('programme.resultat.save');
        Route::post('programme/save', 'ProgrammeController@save')->name('programmes.save');

        Route::resource('users', 'UserController');
        Route::get('territoire', 'TerritoireController@index')->name('territoire');
        Route::resource('pieces-exigibles', 'PieceExigibleDefinitionController')->except(['destroy']);
        Route::resource('fichiers-types', 'FichierTypeController');
        Route::get('fichiers-types/{fichiers_type}/enable', 'FichierTypeController@enable')->name('fichiers-types.enable');
        Route::get('fichiers-types/{fichiers_type}/disable', 'FichierTypeController@disable')->name('fichiers-types.disable');
        Route::get('fichiers-types/data/paginated', 'FichierTypeController@fetchPaginated')->name('fichiers-types.paginated');
        Route::get('companies/data', 'CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/all/prospects', 'CompanyController@fetchProspects')->name('prospects.all');
        Route::get('companies/prospects/paginated', 'CompanyController@fetchProspectsPaginated')->name('prospects.paginated');
        Route::get('companies/prospects/stats', 'CompanyController@fetchProspectsStats')->name('prospects.stats');
        Route::get('companies/prospects/export', 'CompanyController@exportProspects')->name('prospects.export');
        Route::get('companies/prospects/filter-options', 'CompanyController@fetchProspectsFilterOptions')->name('prospects.filter-options');
        Route::get('programs/data', 'ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('programs/data/paginated', 'ProgrammeController@fetchPaginated')->name('programmes.paginated');
        Route::get('programs/stats', 'ProgrammeController@fetchStats')->name('programmes.stats');
        Route::get('programs/filter-options', 'ProgrammeController@fetchFilterOptions')->name('programmes.filter-options');
        Route::get('folders/data', 'DossierController@fetchAll')->name('dossiers.all');

        // Route::resource('entreprises','EntrepriseController');
        Route::get('dossier/{id}', 'EntrepriseController@getDossier')->name('dossier.show');
        Route::get('dossier/instruction/{id}', 'EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
        Route::post('engagement', 'EntrepriseController@setEngagement')->name('entreprise.set.engagement');

        Route::post('dossier/analyse', 'EntrepriseController@setAnalyse')->name('entreprise.dossier.analyse');

        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('user/enable/{token}', 'UserController@enable')->name('user.enable');
        Route::get('user/disable/{token}', 'UserController@disable')->name('user.disable');

        Route::get('instruction/criteres/params', 'InstructionController@getCritereParamsForm');

        Route::post('instruction/dossier/dsf', 'InstructionController@loadDsf')->name('instruction.dsf');
        Route::get('instruction/dossier/create', 'InstructionController@createDossier')->name('instruction.dossier.create');
        Route::get('instruction/dossier/{id}', 'InstructionController@getDossier')->name('instruction.dossier');
        Route::get('instruction/dossier', 'InstructionController@findDossier')->name('instruction.dossier.find');
        Route::get('instruction/critere/choices', 'InstructionController@getChoices')->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse', 'InstructionController@saveCritereReponse')->name('instruction.critere.reponse');

    });

Route::namespace('App\Http\Controllers\Gestionnaire')
    ->prefix('gestionnaire')
    ->middleware(['auth', 'gestionnaire'])
    ->name('gestionnaire.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        // AJAX Dashboard endpoints
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/todos', 'DashboardController@getTodos')->name('dashboard.todos');
        Route::get('dashboard/recent-dossiers', 'DashboardController@getRecentDossiers')->name('dashboard.recent.dossiers');
        Route::get('dashboard/dossiers-distribution', 'DashboardController@getDossiersDistribution')->name('dashboard.dossiers.distribution');
        Route::get('dashboard/entreprises-data', 'DashboardController@getEntreprisesData')->name('dashboard.entreprises.data');

        Route::resource('secteurs', 'SecteurController');
        Route::get('entreprises/prospects/create', 'CompanyController@createProspect')->name('entreprises.prospects.create');
        Route::post('entreprises/prospects', 'CompanyController@storeProspect')->name('entreprises.prospects.store');
        Route::post('entreprises/prospects/{token}/submit', 'CompanyController@submitProspect')->name('entreprises.prospects.submit');
        Route::post('entreprises/prospects/{token}/avis-criteres', 'CompanyController@storeProspectCritereAvis')->name('entreprises.prospects.avis-criteres.store');
        Route::get('entreprises/{token}/fiche/pdf', 'CompanyController@fichePdf')->name('entreprises.fiche.pdf');
        Route::post('entreprises/{token}/sites', 'EntrepriseSiteController@store')->name('entreprises.sites.store');
        Route::match(['put', 'patch'], 'entreprises/{token}/sites/{site}', 'EntrepriseSiteController@update')->name('entreprises.sites.update');
        Route::delete('entreprises/{token}/sites/{site}', 'EntrepriseSiteController@destroy')->name('entreprises.sites.destroy');

        Route::post('entreprises/{token}/equipe', 'EntrepriseEquipeController@store')->name('entreprises.equipe.store');
        Route::match(['put', 'patch'], 'entreprises/{token}/equipe/{membre}', 'EntrepriseEquipeController@update')->name('entreprises.equipe.update');
        Route::delete('entreprises/{token}/equipe/{membre}', 'EntrepriseEquipeController@destroy')->name('entreprises.equipe.destroy');
        Route::get('entreprises/{token}/pieces-exigibles', 'PieceExigibleController@index')->name('entreprises.pieces-exigibles.index');
        Route::post('entreprises/{token}/pieces-exigibles/{definition}', 'PieceExigibleController@store')->name('entreprises.pieces-exigibles.store');
        Route::get('entreprises/{token}/analyse-critique', 'AnalyseCritiqueController@show')->name('entreprises.analyse-critique.show');
        Route::post('entreprises/{token}/analyse-critique', 'AnalyseCritiqueController@update')->name('entreprises.analyse-critique.update');
        Route::resource('entreprises', 'CompanyController');
        Route::post('entreprise/save', 'CompanyController@save')->name('entreprises.save');

        Route::get('prospects', 'CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('entreprise/programme', 'CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::post('entreprise/appui', 'CompanyController@saveAppui')->name('entreprise.appui.save');
        Route::post('entreprise/element', 'CompanyController@addElement')->name('entreprise.element.save');
        Route::get('entreprise/tiers/physique/{token}', 'CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique', 'CompanyController@saveTiersPhysique')->name('entreprise.physique.save');

        Route::get('entreprise/tiers/morale/{token}', 'CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::get('entreprise/tiers/morale/search/portfolio', 'CompanyController@searchTierMoralePortfolio')->name('entreprise.morale.search');
        Route::post('entreprise/tiers/morale', 'CompanyController@saveTiersMorale')->name('entreprise.morale.save');

        Route::get('entreprise/questionnaire/{token}', 'CompanyController@createQuestionnaire')->name('entreprise.questionnaire');
        Route::post('entreprise/questionnaire', 'CompanyController@saveQuestionnaire')->name('entreprise.questionnaire.save');
        Route::get('entreprise/engagements/{token}', 'CompanyController@getEngagementReport')->name('entreprise.get.engagements');

        Route::post('dossier/{token}/pieces', 'DossierController@storeDossierPiece')->name('dossier.pieces.store');
        Route::resource('dossiers', 'DossierController');

        Route::resource('programmes', 'ProgrammeController', ['except' => ['create', 'store']]);
        Route::post('programme/composante', 'ProgrammeController@saveComposante')->name('programme.composante.save');
        Route::post('programme/resultat', 'ProgrammeController@saveResultat')->name('programme.resultat.save');

        Route::resource('users', 'UserController');
        Route::get('territoire', 'TerritoireController@index')->name('territoire');
        Route::get('companies/data', 'CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/data/paginated', 'CompanyController@fetchPaginated')->name('entreprises.paginated');
        Route::get('companies/stats', 'CompanyController@fetchStats')->name('entreprises.stats');
        Route::get('companies/filter-options', 'CompanyController@fetchFilterOptions')->name('entreprises.filter-options');
        Route::get('companies/clients-export', 'CompanyController@exportClients')->name('entreprises.export');
        Route::get('companies/all/prospects', 'CompanyController@fetchProspects')->name('prospects.all');
        Route::get('companies/prospects/paginated', 'CompanyController@fetchProspectsPaginated')->name('prospects.paginated');
        Route::get('companies/prospects/stats', 'CompanyController@fetchProspectsStats')->name('prospects.stats');
        Route::get('companies/prospects/export', 'CompanyController@exportProspects')->name('prospects.export');
        Route::get('companies/prospects/filter-options', 'CompanyController@fetchProspectsFilterOptions')->name('prospects.filter-options');
        Route::get('programs/data', 'ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('programs/data/paginated', 'ProgrammeController@fetchPaginated')->name('programmes.paginated');
        Route::get('programs/stats', 'ProgrammeController@fetchStats')->name('programmes.stats');
        Route::get('programs/filter-options', 'ProgrammeController@fetchFilterOptions')->name('programmes.filter-options');
        Route::get('folders/data', 'DossierController@fetchAll')->name('dossiers.all');
        Route::get('folders/data/paginated', 'DossierController@fetchPaginated')->name('dossiers.paginated');
        Route::get('folders/stats', 'DossierController@fetchStats')->name('dossiers.stats');
        Route::get('folders/filter-options', 'DossierController@fetchFilterOptions')->name('dossiers.filter-options');
        Route::get('grille/analyse/{token}', 'DossierController@getGrilleAnalyse')->name('dossier.get.grille.analyse');
        Route::post('dossier/grille/analyse', 'DossierController@setAnalyse')->name('dossier.set.analyse');

        // Route::resource('entreprises','EntrepriseController');
        Route::get('dossier/{id}', 'EntrepriseController@getDossier')->name('dossier.show');
        Route::get('dossier/instruction/{id}', 'EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
        Route::post('engagement', 'EntrepriseController@setEngagement')->name('entreprise.set.engagement');

        Route::post('dossier/analyse', 'EntrepriseController@setAnalyse')->name('entreprise.dossier.analyse');

        Route::get('instruction/criteres/params', 'InstructionController@getCritereParamsForm');

        Route::post('instruction/dossier/dsf', 'InstructionController@loadDsf')->name('instruction.dsf');
        Route::get('instruction/dossier/create', 'InstructionController@createDossier')->name('instruction.dossier.create');
        Route::get('instruction/dossier/{id}', 'InstructionController@getDossier')->name('instruction.dossier');
        Route::get('instruction/dossier', 'InstructionController@findDossier')->name('instruction.dossier.find');
        Route::get('instruction/critere/choices', 'InstructionController@getChoices')->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse', 'InstructionController@saveCritereReponse')->name('instruction.critere.reponse');
    });

Route::namespace('App\Http\Controllers\Analyste')
    ->prefix('analyste')
    ->middleware(['auth', 'analyste'])
    ->name('analyste.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        // AJAX Dashboard endpoints
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/todos', 'DashboardController@getTodos')->name('dashboard.todos');
        Route::get('dashboard/dossiers-distribution', 'DashboardController@getDossiersDistribution')->name('dashboard.dossiers.distribution');
        Route::get('dashboard/monthly-analysis', 'DashboardController@getMonthlyAnalysis')->name('dashboard.monthly.analysis');
        Route::get('dashboard/recent-dossiers', 'DashboardController@getRecentDossiers')->name('dashboard.recent.dossiers');
        Route::get('dashboard/performance-metrics', 'DashboardController@getPerformanceMetrics')->name('dashboard.performance.metrics');
        Route::get('dashboard/alerts', 'DashboardController@getAlerts')->name('dashboard.alerts');
        Route::get('dashboard/programmes', 'DashboardController@getProgrammes')->name('dashboard.programmes');
        Route::get('entreprises/data/paginated', 'CompanyController@fetchPaginated')->name('entreprises.paginated');
        Route::get('entreprises/stats', 'CompanyController@fetchEntreprisesIndexStats')->name('entreprises.stats');
        Route::get('entreprises/clients-export', 'CompanyController@exportClients')->name('entreprises.export');
        Route::resource('entreprises', 'CompanyController');
        Route::get('prospects', 'CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('entreprise/programme', 'CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::get('entreprise/tiers/physique/{token}', 'CompanyController@createTiersPhysique')->name('entreprise.physique.create');
        Route::post('entreprise/tiers/physique', 'CompanyController@saveTiersPhysique')->name('entreprise.physique.save');
        Route::get('entreprise/engagements/{token}', 'EntrepriseController@getEngagementReport')->name('entreprise.get.engagements');
        Route::get('entreprise/engagements/{token}/data', 'EntrepriseController@fetchEngagementReport')->name('entreprise.engagements.data');
        Route::get('entreprise/engagements/{token}/stats', 'EntrepriseController@fetchEngagementReportStats')->name('entreprise.engagements.stats');
        Route::get('entreprise/engagements/{token}/filter-options', 'EntrepriseController@fetchEngagementReportFilterOptions')->name('entreprise.engagements.filter-options');
        Route::get('entreprise/engagements/{token}/export', 'EntrepriseController@exportEngagementReport')->name('entreprise.engagements.export');
        Route::get('grille/analyse/{token}', 'DossierController@getGrilleAnalyse')->name('dossier.get.grille.analyse');

        Route::get('entreprise/tiers/morale/{token}', 'CompanyController@createTiersMorale')->name('entreprise.morale.create');
        Route::post('entreprise/tiers/morale', 'CompanyController@saveTiersMorale')->name('entreprise.morale.save');

        Route::get('entreprise/questionnaire/{token}', 'CompanyController@createQuestionnaire')->name('entreprise.questionnaire');
        Route::post('entreprise/questionnaire', 'CompanyController@saveQuestionnaire')->name('entreprise.questionnaire.save');

        Route::post('dossiers/{dossier}/instruction-avis-brouillon', 'DossierController@saveInstructionAvisDraft')->name('dossiers.instruction-avis-brouillon');
        Route::post('dossiers/{dossier}/soumettre-exploitation', 'DossierController@soumettreExploitation')->name('dossiers.soumettre-exploitation');
        Route::post('dossier/{token}/pieces', 'DossierController@storeDossierPiece')->name('dossier.pieces.store');
        Route::resource('dossiers', 'DossierController');
        Route::post('dossier/dsf', 'DossierController@loadDsf')->name('dossier.dsf');
        Route::resource('programmes', 'ProgrammeController', ['except' => ['create', 'store']]);
        Route::post('programme/composante', 'ProgrammeController@saveComposante')->name('programme.composante.save');
        Route::post('programme/resultat', 'ProgrammeController@saveResultat')->name('programme.resultat.save');

        Route::resource('users', 'UserController');
        Route::get('territoire', 'TerritoireController@index')->name('territoire');
        Route::get('companies/data', 'CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/filter-options', 'CompanyController@fetchFilterOptions')->name('entreprises.filter-options');
        Route::get('companies/all/prospects', 'CompanyController@fetchProspects')->name('prospects.all');
        Route::get('companies/prospects/paginated', 'CompanyController@fetchProspectsPaginated')->name('prospects.paginated');
        Route::get('companies/prospects/stats', 'CompanyController@fetchProspectsStats')->name('prospects.stats');
        Route::get('companies/prospects/export', 'CompanyController@exportProspects')->name('prospects.export');
        Route::get('companies/prospects/filter-options', 'CompanyController@fetchProspectsFilterOptions')->name('prospects.filter-options');
        Route::get('programs/data', 'ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('programs/data/paginated', 'ProgrammeController@fetchPaginated')->name('programmes.paginated');
        Route::get('programs/stats', 'ProgrammeController@fetchStats')->name('programmes.stats');
        Route::get('programs/filter-options', 'ProgrammeController@fetchFilterOptions')->name('programmes.filter-options');
        Route::get('folders/data', 'DossierController@fetchAll')->name('dossiers.all');
        Route::get('folders/data/paginated', 'DossierController@fetchPaginated')->name('dossiers.paginated');
        Route::get('folders/stats', 'DossierController@fetchStats')->name('dossiers.stats');
        Route::get('folders/filter-options', 'DossierController@fetchFilterOptions')->name('dossiers.filter-options');

        // Route::resource('entreprises','EntrepriseController');
        Route::get('dossier/{id}', 'EntrepriseController@getDossier')->name('dossier.show');
        Route::get('dossier/instruction/{id}', 'EntrepriseController@getCreateInstruction')->name('dossier.instruction.create');
        Route::post('engagement', 'EntrepriseController@setEngagement')->name('entreprise.set.engagement');

        Route::post('dossier/analyse', 'DossierController@setAnalyse')->name('dossier.set.analyse');

        Route::get('instruction/criteres/params', 'InstructionController@getCritereParamsForm');

        Route::post('instruction/dossier/dsf', 'InstructionController@loadDsf')->name('instruction.dsf');
        Route::get('instruction/dossier/create', 'InstructionController@createDossier')->name('instruction.dossier.create');
        Route::get('instruction/dossier/{id}', 'InstructionController@getDossier')->name('instruction.dossier');
        Route::get('instruction/dossier', 'InstructionController@findDossier')->name('instruction.dossier.find');
        Route::get('instruction/critere/choices', 'InstructionController@getChoices')->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse', 'InstructionController@saveCritereReponse')->name('instruction.critere.reponse');
    });

Route::namespace('App\Http\Controllers\AnalysteCredit')
    ->prefix('analyste-credit')
    ->middleware(['auth', 'analyste.credit'])
    ->name('analyste-credit.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/todos', 'DashboardController@getTodos')->name('dashboard.todos');
        Route::get('entreprises', [PortfolioController::class, 'entreprisesIndex'])->name('entreprises.index');
        Route::get('entreprises-export', [PortfolioController::class, 'entreprisesExport'])->name('entreprises.export');
        Route::get('dossiers', [PortfolioController::class, 'dossiersIndex'])->name('dossiers.index');
        Route::get('dossiers-export', [PortfolioController::class, 'dossiersExport'])->name('dossiers.export');
        Route::get('dossiers/{token}/instruction', [PortfolioController::class, 'dossierInstructionShow'])->name('dossiers.instruction');
        Route::get('dossiers/{token}/instruction/pdf', [PortfolioController::class, 'dossierInstructionPdf'])->name('dossiers.instruction.pdf');
        Route::get('dossiers/{token}/dossier-analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueSyntheseShow'])->name('dossiers.dossier-analyse-critique');
        Route::get('dossiers/{token}/dossier-analyse-critique/pdf', [PortfolioController::class, 'dossierAnalyseCritiqueSynthesePdf'])->name('dossiers.dossier-analyse-critique.pdf');
        Route::get('dossiers/{token}/analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueShow'])->name('dossiers.analyse-critique');
        Route::post('dossiers/{token}/brouillon', 'DossierController@saveDraft')->name('dossiers.brouillon');
        Route::post('dossiers/{token}/soumettre-reng', 'DossierController@submitToReng')->name('dossiers.soumettre-reng');
        Route::post('dossiers/{token}/pieces', [PortfolioController::class, 'storeDossierPiece'])->name('dossiers.pieces.store');
        Route::get('dossiers/{token}', [PortfolioController::class, 'dossierShow'])->name('dossiers.show');
        Route::get('entreprise/engagements/{token}', 'EntrepriseController@getEngagementReport')->name('entreprise.get.engagements');
        Route::get('entreprise/engagements/{token}/data', 'EntrepriseController@fetchEngagementReport')->name('entreprise.engagements.data');
        Route::get('entreprise/engagements/{token}/stats', 'EntrepriseController@fetchEngagementReportStats')->name('entreprise.engagements.stats');
        Route::get('entreprise/engagements/{token}/filter-options', 'EntrepriseController@fetchEngagementReportFilterOptions')->name('entreprise.engagements.filter-options');
        Route::get('entreprise/engagements/{token}/export', 'EntrepriseController@exportEngagementReport')->name('entreprise.engagements.export');
        Route::post('entreprise/engagement', 'EntrepriseController@setEngagement')->name('entreprise.set.engagement');
        Route::get('entreprises/{token}/pieces', [PortfolioController::class, 'entreprisePieces'])->name('entreprises.pieces');
        Route::get('entreprises/{token}/fiche/pdf', [PortfolioController::class, 'entrepriseFichePdf'])->name('entreprises.fiche.pdf');
        Route::get('entreprises/{token}', [PortfolioController::class, 'entrepriseShow'])->name('entreprises.show');
    });

Route::namespace('App\Http\Controllers\AnalysteRisques')
    ->prefix('analyste-risques')
    ->middleware(['auth', 'analyste.risques'])
    ->name('analyste-risques.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/todos', 'DashboardController@getTodos')->name('dashboard.todos');
        Route::get('entreprises', [PortfolioController::class, 'entreprisesIndex'])->name('entreprises.index');
        Route::get('entreprises-export', [PortfolioController::class, 'entreprisesExport'])->name('entreprises.export');
        Route::get('dossiers', [PortfolioController::class, 'dossiersIndex'])->name('dossiers.index');
        Route::get('dossiers-export', [PortfolioController::class, 'dossiersExport'])->name('dossiers.export');
        Route::get('dossiers/{token}/instruction', [PortfolioController::class, 'dossierInstructionShow'])->name('dossiers.instruction');
        Route::get('dossiers/{token}/instruction/pdf', [PortfolioController::class, 'dossierInstructionPdf'])->name('dossiers.instruction.pdf');
        Route::get('dossiers/{token}/dossier-analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueSyntheseShow'])->name('dossiers.dossier-analyse-critique');
        Route::get('dossiers/{token}/dossier-analyse-critique/pdf', [PortfolioController::class, 'dossierAnalyseCritiqueSynthesePdf'])->name('dossiers.dossier-analyse-critique.pdf');
        Route::get('dossiers/{token}/analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueShow'])->name('dossiers.analyse-critique');
        Route::post('dossiers/{token}/brouillon', 'DossierController@saveDraft')->name('dossiers.brouillon');
        Route::post('dossiers/{token}/soumettre-rerx', 'DossierController@submitToRerx')->name('dossiers.soumettre-rerx');
        Route::post('dossiers/{token}/pieces', [PortfolioController::class, 'storeDossierPiece'])->name('dossiers.pieces.store');
        Route::get('dossiers/{token}', [PortfolioController::class, 'dossierShow'])->name('dossiers.show');
        Route::get('entreprises/{token}/engagements', [PortfolioController::class, 'entrepriseEngagementReport'])->name('entreprises.engagements');
        Route::get('entreprises/{token}/pieces', [PortfolioController::class, 'entreprisePieces'])->name('entreprises.pieces');
        Route::get('entreprises/{token}/fiche/pdf', [PortfolioController::class, 'entrepriseFichePdf'])->name('entreprises.fiche.pdf');
        Route::get('entreprises/{token}', [PortfolioController::class, 'entrepriseShow'])->name('entreprises.show');
    });

Route::namespace('App\Http\Controllers\AnalysteJuridique')
    ->prefix('analyste-juridique')
    ->middleware(['auth', 'analyste.juridique'])
    ->name('analyste-juridique.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/todos', 'DashboardController@getTodos')->name('dashboard.todos');
        Route::get('entreprises', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entreprisesIndex'])->name('entreprises.index');
        Route::get('entreprises-export', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entreprisesExport'])->name('entreprises.export');
        Route::get('dossiers', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossiersIndex'])->name('dossiers.index');
        Route::get('dossiers-export', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossiersExport'])->name('dossiers.export');
        Route::get('dossiers/{token}/instruction', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierInstructionShow'])->name('dossiers.instruction');
        Route::get('dossiers/{token}/instruction/pdf', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierInstructionPdf'])->name('dossiers.instruction.pdf');
        Route::get('dossiers/{token}/dossier-analyse-critique', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierAnalyseCritiqueSyntheseShow'])->name('dossiers.dossier-analyse-critique');
        Route::get('dossiers/{token}/dossier-analyse-critique/pdf', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierAnalyseCritiqueSynthesePdf'])->name('dossiers.dossier-analyse-critique.pdf');
        Route::get('dossiers/{token}/analyse-critique', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierAnalyseCritiqueShow'])->name('dossiers.analyse-critique');
        Route::post('dossiers/{token}/soumettre-reju', 'DossierController@submitToReju')->name('dossiers.soumettre-reju');
        Route::post('dossiers/{token}/pieces', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'storeDossierPiece'])->name('dossiers.pieces.store');
        Route::get('dossiers/{token}', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'dossierShow'])->name('dossiers.show');
        Route::get('entreprises/{token}/engagements', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entrepriseEngagementReport'])->name('entreprises.engagements');
        Route::get('entreprises/{token}/pieces', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entreprisePieces'])->name('entreprises.pieces');
        Route::get('entreprises/{token}/fiche/pdf', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entrepriseFichePdf'])->name('entreprises.fiche.pdf');
        Route::get('entreprises/{token}', [\App\Http\Controllers\RoleSpace\PortfolioController::class, 'entrepriseShow'])->name('entreprises.show');
    });

Route::namespace('App\Http\Controllers\AnalysteConformite')
    ->prefix('analyste-conformite')
    ->middleware(['auth', 'analyste.conformite'])
    ->name('analyste-conformite.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
    });

Route::namespace('App\Http\Controllers\Ca')
    ->prefix('ca')
    ->middleware(['auth', 'ca'])
    ->name('ca.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        // AJAX Dashboard endpoints
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/performance-data', 'DashboardController@getPerformanceData')->name('dashboard.performance.data');
        Route::get('dashboard/team-performance', 'DashboardController@getTeamPerformance')->name('dashboard.team.performance');
        Route::get('dashboard/recent-dossiers', 'DashboardController@getRecentDossiers')->name('dashboard.recent.dossiers');
        Route::get('dashboard/monthly-stats', 'DashboardController@getMonthlyStats')->name('dashboard.monthly.stats');
        Route::get('dashboard/alerts', 'DashboardController@getAlerts')->name('dashboard.alerts');
        Route::get('entreprises', 'CompanyController@index')->name('entreprises.index');
        Route::get('entreprises/{token}', 'CompanyController@show')->name('entreprises.show');
        Route::get('prospects', 'CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('entreprise/programme', 'CompanyController@saveProgramme')->name('entreprise.programme.save');
        Route::get('entreprise/engagements/{token}', 'CompanyController@getEngagementReport')->name('entreprise.get.engagements');

        Route::resource('dossiers', 'DossierController');
        Route::get('programmes', 'ProgrammeController@index')->name('programmes.index');
        Route::get('programmes/{token}', 'ProgrammeController@show')->name('programmes.show');
        Route::get('users', 'UserController@index')->name('users.index');
        Route::get('user/disable/{token}', 'UserController@disable')->name('user.disable');
        Route::get('user/enable/{token}', 'UserController@enable')->name('user.enable');
        Route::get('territoire', 'TerritoireController@index')->name('territoire');
        Route::get('companies/data', 'CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/data/paginated', 'CompanyController@fetchPaginated')->name('entreprises.paginated');
        Route::get('companies/stats', 'CompanyController@fetchStats')->name('entreprises.stats');
        Route::get('companies/filter-options', 'CompanyController@fetchFilterOptions')->name('entreprises.filter-options');
        Route::get('companies/clients-export', 'CompanyController@exportClients')->name('entreprises.export');
        Route::get('companies/all/prospects', 'CompanyController@fetchProspects')->name('prospects.all');
        Route::get('companies/prospects/paginated', 'CompanyController@fetchProspectsPaginated')->name('prospects.paginated');
        Route::get('companies/prospects/stats', 'CompanyController@fetchProspectsStats')->name('prospects.stats');
        Route::get('companies/prospects/export', 'CompanyController@exportProspects')->name('prospects.export');
        Route::get('companies/prospects/filter-options', 'CompanyController@fetchProspectsFilterOptions')->name('prospects.filter-options');
        Route::get('programs/data', 'ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('programs/data/paginated', 'ProgrammeController@fetchPaginated')->name('programmes.paginated');
        Route::get('programs/stats', 'ProgrammeController@fetchStats')->name('programmes.stats');
        Route::get('programs/filter-options', 'ProgrammeController@fetchFilterOptions')->name('programmes.filter-options');
        Route::get('folders/data', 'DossierController@fetchAll')->name('dossiers.all');
        Route::get('folders/data/paginated', 'DossierController@fetchPaginated')->name('dossiers.paginated');
        Route::get('folders/stats', 'DossierController@fetchStats')->name('dossiers.stats');
        Route::get('folders/filter-options', 'DossierController@fetchFilterOptions')->name('dossiers.filter-options');
        Route::get('instruction/critere/choices', [\App\Http\Controllers\Gestionnaire\InstructionController::class, 'getChoices'])->name('instruction.critere.choices');
        Route::post('instruction/critere/reponse', [\App\Http\Controllers\Gestionnaire\InstructionController::class, 'saveCritereReponse'])->name('instruction.critere.reponse');
        Route::post('dossier/analyse', 'DossierController@setAnalyse')->name('dossier.set.analyse');
        Route::post('dossier/{token}/instruction-agence-avis', 'DossierController@saveInstructionAgenceCaAvis')->name('dossier.instruction-agence-avis.save');
        Route::post('dossier/{token}/transmit-exploitation', 'DossierController@transmitInstructionCaToExploitation')->name('dossier.transmit-exploitation');
        Route::get('dossier/grille/analyse/{token}', 'DossierController@getGrilleAnalyse')->name('dossier.get.grille.analyse');
        Route::get('dossier/{token}/analyse-critique', 'DossierController@dossierAnalyseCritiqueSyntheseShow')->name('dossier.analyse-critique.synthese');
        Route::get('dossier/{token}/analyse-critique/pdf', 'DossierController@dossierAnalyseCritiqueSynthesePdf')->name('dossier.analyse-critique.synthese.pdf');
        Route::post('dossier/{token}/pieces', 'DossierController@storeDossierPiece')->name('dossier.pieces.store');

        Route::get('workflow/prospects', [\App\Http\Controllers\Ca\WorkflowController::class, 'prospectIndex'])->name('workflow.prospects.index');
        Route::get('workflow/prospects/{token}', [\App\Http\Controllers\Ca\WorkflowController::class, 'prospectShow'])->name('workflow.prospects.show');
        Route::post('workflow/prospects/{token}/approve', [\App\Http\Controllers\Ca\WorkflowController::class, 'prospectApprove'])->name('workflow.prospects.approve');
        Route::post('workflow/prospects/{token}/reject', [\App\Http\Controllers\Ca\WorkflowController::class, 'prospectReject'])->name('workflow.prospects.reject');
        Route::get('workflow/instructions', [\App\Http\Controllers\Ca\WorkflowController::class, 'instructionIndex'])->name('workflow.instructions.index');
        Route::get('workflow/instructions/{token}', [\App\Http\Controllers\Ca\WorkflowController::class, 'instructionShow'])->name('workflow.instructions.show');
        Route::post('workflow/instructions/{token}/approve', [\App\Http\Controllers\Ca\WorkflowController::class, 'approveInstruction'])->name('workflow.instructions.approve');
        Route::post('workflow/instructions/{token}/reject-qualification', [\App\Http\Controllers\Ca\WorkflowController::class, 'rejectQualification'])->name('workflow.instructions.reject-qualification');
        Route::get('workflow/instruction-dossiers', [\App\Http\Controllers\Ca\WorkflowController::class, 'instructionDossierBundleIndex'])->name('workflow.instruction-dossiers.index');
        Route::get('workflow/instruction-dossiers/{token}', [\App\Http\Controllers\Ca\WorkflowController::class, 'instructionDossierBundleShow'])->name('workflow.instruction-dossiers.show');
        Route::post('workflow/instruction-dossiers/{token}/approve', [\App\Http\Controllers\Ca\WorkflowController::class, 'approveInstructionBundle'])->name('workflow.instruction-dossiers.approve');
        Route::post('workflow/instruction-dossiers/{token}/reject', [\App\Http\Controllers\Ca\WorkflowController::class, 'rejectInstructionBundle'])->name('workflow.instruction-dossiers.reject');

    });

Route::namespace('App\Http\Controllers\Regional')
    ->prefix('regional')
    ->middleware(['auth', 'regional'])
    ->name('regional.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('entreprises-export', 'CompanyController@exportEntreprisesClients')->name('entreprises.export');
        Route::get('entreprises', 'CompanyController@index')->name('entreprises.index');
        Route::get('entreprises/{token}', 'CompanyController@show')->name('entreprises.show');
        Route::get('prospects', 'CompanyController@getProspects')->name('entreprises.prospects');
        Route::post('dossier/{token}/pieces', 'DossierController@storeDossierPiece')->name('dossier.pieces.store');
        Route::resource('dossiers', 'DossierController');
        Route::get('programmes', 'ProgrammeController@index')->name('programmes.index');
        Route::get('programmes/{token}', 'ProgrammeController@show')->name('programmes.show');
        Route::get('users', 'UserController@index')->name('users.index');
        Route::get('user/disable/{token}', 'UserController@disable')->name('user.disable');
        Route::get('user/enable/{token}', 'UserController@enable')->name('user.enable');
        Route::get('territoire', 'TerritoireController@index')->name('territoire');
        Route::get('companies/data', 'CompanyController@fetchAll')->name('entreprises.all');
        Route::get('companies/all/prospects', 'CompanyController@fetchProspects')->name('prospects.all');
        Route::get('companies/prospects/paginated', 'CompanyController@fetchProspectsPaginated')->name('prospects.paginated');
        Route::get('companies/prospects/stats', 'CompanyController@fetchProspectsStats')->name('prospects.stats');
        Route::get('companies/prospects/export', 'CompanyController@exportProspects')->name('prospects.export');
        Route::get('companies/prospects/filter-options', 'CompanyController@fetchProspectsFilterOptions')->name('prospects.filter-options');
        Route::get('programs/data', 'ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('programs/data/paginated', 'ProgrammeController@fetchPaginated')->name('programmes.paginated');
        Route::get('programs/stats', 'ProgrammeController@fetchStats')->name('programmes.stats');
        Route::get('programs/filter-options', 'ProgrammeController@fetchFilterOptions')->name('programmes.filter-options');
        Route::get('folders/data', 'DossierController@fetchAll')->name('dossiers.all');
    });

Route::namespace('App\Http\Controllers\ChefFiliere')
    ->prefix('chef-filiere')
    ->middleware(['auth', 'chef.filiere'])
    ->name('chef-filiere.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/insights', 'DashboardController@getInsights')->name('dashboard.insights');
        Route::get('programmes', 'ProgrammeController@index')->name('programmes.index');
        Route::get('programmes/{token}', 'ProgrammeController@show')->name('programmes.show');
        Route::get('programs/data', 'ProgrammeController@fetchAll')->name('programmes.all');
        Route::get('programs/data/paginated', 'ProgrammeController@fetchPaginated')->name('programmes.paginated');
        Route::get('programs/stats', 'ProgrammeController@fetchStats')->name('programmes.stats');
        Route::get('programs/filter-options', 'ProgrammeController@fetchFilterOptions')->name('programmes.filter-options');
        Route::get('clients', 'ClientController@index')->name('clients.index');
        Route::get('clients-export', 'ClientController@exportClients')->name('clients.export');
        Route::get('clients/{token}', 'ClientController@show')->name('clients.show');
        Route::get('clients/{token}/besoins-produits/modifier', 'ClientController@editBesoinsProduits')->name('clients.besoins-produits.edit');
        Route::post('clients/{token}/besoins-produits', 'ClientController@updateBesoinsProduits')->name('clients.besoins-produits.update');
        Route::get('instructions/en-attente', 'InstructionController@pending')->name('instructions.pending');
        Route::get('instructions/en-cours', 'InstructionController@inProgress')->name('instructions.in-progress');
        Route::post('instructions/dossiers/{token}/pieces', 'InstructionController@storeDossierPiece')->name('instructions.dossier.pieces.store');
        Route::get('instructions/dossiers/{token}', 'InstructionController@showDossier')->name('instructions.dossier.show');
        Route::get('entreprises/{token}/pieces-exigibles', 'PieceExigibleController@index')->name('entreprises.pieces-exigibles.index');
        Route::post('entreprises/{token}/pieces-exigibles/{definition}', 'PieceExigibleController@store')->name('entreprises.pieces-exigibles.store');
        Route::get('entreprises/{token}/analyse-critique', 'AnalyseCritiqueController@show')->name('entreprises.analyse-critique.show');
        Route::get('qualifications', 'QualificationController@index')->name('qualifications.index');
        Route::get('qualifications/{token}', 'QualificationController@show')->name('qualifications.show');
        Route::post('qualifications/{token}', 'QualificationController@update')->name('qualifications.update');
        Route::post('qualifications/{token}/submit', 'QualificationController@submit')->name('qualifications.submit');
        Route::post('clients/{token}/dossier-instruction/soumettre', 'ClientController@submitInstructionBundle')->name('clients.dossier-instruction.submit');
    });

// Clôture du dossier d’instruction (fin de parcours) — dépend de la délégation de pouvoir.
Route::middleware(['auth'])
    ->prefix('instruction')
    ->name('instruction.')
    ->group(function () {
        Route::post('dossiers/{token}/closure/approve', [\App\Http\Controllers\InstructionClosureController::class, 'approve'])->name('dossiers.closure.approve');
        Route::post('dossiers/{token}/closure/reject', [\App\Http\Controllers\InstructionClosureController::class, 'reject'])->name('dossiers.closure.reject');
        // Rejet inter-pôle direction vers RISQ (renvoyer au pôle risques sans clôturer le dossier).
        Route::post('dossiers/{token}/rejeter-vers-risques', [\App\Http\Controllers\InstructionClosureController::class, 'rejectToRisques'])->name('dossiers.rejeter-vers-risques');
    });

Route::middleware(['auth', 'ca'])->group(function () {
    Route::redirect('chef-agence/dashboard', '/ca/dashboard', 301);
    Route::redirect('chef-agence/prospects', '/ca/workflow/prospects', 301);
    Route::redirect('chef-agence/instructions', '/ca/workflow/instructions', 301);
    Route::get('chef-agence/prospects/{token}', function (string $token) {
        return redirect()->route('ca.workflow.prospects.show', ['token' => $token], 301);
    });
    Route::get('chef-agence/instructions/{token}', function (string $token) {
        return redirect()->route('ca.workflow.instructions.show', ['token' => $token], 301);
    });
});

Route::namespace('App\Http\Controllers\Juridique')
    ->prefix('juridique')
    ->middleware(['auth', 'reju'])
    ->name('juridique.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/todos', 'DashboardController@getTodos')->name('dashboard.todos');
        Route::get('dossiers', [PortfolioController::class, 'dossiersIndex'])->name('dossiers.index');
        Route::get('dossiers-export', [PortfolioController::class, 'dossiersExport'])->name('dossiers.export');
        Route::get('dossiers/{token}/instruction', [PortfolioController::class, 'dossierInstructionShow'])->name('dossiers.instruction');
        Route::get('dossiers/{token}/instruction/pdf', [PortfolioController::class, 'dossierInstructionPdf'])->name('dossiers.instruction.pdf');
        Route::get('dossiers/{token}/dossier-analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueSyntheseShow'])->name('dossiers.dossier-analyse-critique');
        Route::get('dossiers/{token}/dossier-analyse-critique/pdf', [PortfolioController::class, 'dossierAnalyseCritiqueSynthesePdf'])->name('dossiers.dossier-analyse-critique.pdf');
        Route::get('dossiers/{token}/analyse-critique', [PortfolioController::class, 'dossierAnalyseCritiqueShow'])->name('dossiers.analyse-critique');
        Route::post('dossiers/{token}/assign-juridique-analyste', [PortfolioController::class, 'assignJuridiqueAnalyste'])->name('dossiers.assign-juridique-analyste');
        Route::post('dossiers/{token}/responsable-avis', [PortfolioController::class, 'storeJuridiqueResponsableAvis'])->name('dossiers.responsable-avis');
        Route::post('dossiers/{token}/soumettre-engagements', [PortfolioController::class, 'submitJuridiqueToEngagements'])->name('dossiers.soumettre-engagements');
        // Rejet intermédiaire de la soumission de l'analyste juridique (réouverture de l'étape AJ).
        Route::post('dossiers/{token}/rejeter-analyste', [PortfolioController::class, 'rejectJuridiqueAnalyste'])->name('dossiers.rejeter-analyste');
        // Rejet inter-pôle : RJU renvoie le dossier vers le pôle exploitation (réouverture REXP).
        Route::post('dossiers/{token}/rejeter-vers-exploitation', [PortfolioController::class, 'rejectJuridiqueToExploitation'])->name('dossiers.rejeter-vers-exploitation');
        Route::post('dossiers/{token}/pieces', [PortfolioController::class, 'storeDossierPiece'])->name('dossiers.pieces.store');
        Route::get('dossiers/{token}', [PortfolioController::class, 'dossierShow'])->name('dossiers.show');
        Route::get('entreprises', [PortfolioController::class, 'entreprisesIndex'])->name('entreprises.index');
        Route::get('entreprises-export', [PortfolioController::class, 'entreprisesExport'])->name('entreprises.export');
        Route::get('entreprises/{token}/engagements', [PortfolioController::class, 'entrepriseEngagementReport'])->name('entreprises.engagements');
        Route::get('entreprises/{token}/pieces', [PortfolioController::class, 'entreprisePieces'])->name('entreprises.pieces');
        Route::get('entreprises/{token}/fiche/pdf', [PortfolioController::class, 'entrepriseFichePdf'])->name('entreprises.fiche.pdf');
        Route::get('entreprises/{token}', [PortfolioController::class, 'entrepriseShow'])->name('entreprises.show');
        Route::get('prospects', 'ProspectController@index')->name('prospects.index');
        Route::get('prospects/export', 'ProspectController@exportOpenProspectsJuridique')->name('prospects.export');
        Route::get('prospects/{token}', 'ProspectController@show')->name('prospects.show');
        Route::post('prospects/{token}/avis', 'ProspectController@store')->name('prospects.avis');
    });

Route::namespace('App\Http\Controllers\Conformite')
    ->prefix('conformite')
    ->middleware(['auth', 'reconf'])
    ->name('conformite.')
    ->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('dashboard/stats', 'DashboardController@getStats')->name('dashboard.stats');
        Route::get('dashboard/todos', 'DashboardController@getTodos')->name('dashboard.todos');
        Route::get('prospects', 'ProspectController@index')->name('prospects.index');
        Route::get('prospects/export', 'ProspectController@exportOpenProspectsConformite')->name('prospects.export');
        Route::get('dossiers-traites', 'ProspectController@treatedIndex')->name('prospects.treated');
        Route::get('prospects/{token}', 'ProspectController@show')->name('prospects.show');
        Route::post('prospects/{token}/avis', 'ProspectController@store')->name('prospects.avis');
    });

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile')->middleware('auth');
Route::post('/profile', [HomeController::class, 'storeProfile'])->name('profile.store')->middleware('auth');
Route::post('/logout', [HomeController::class, 'logout'])->name('logout')->middleware('auth');

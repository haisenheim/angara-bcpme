<?php

namespace App\Http\Controllers\RoleSpace;

use App\Http\Controllers\Concerns\AppliesEntrepriseListIndexFilters;
use App\Http\Controllers\Concerns\BuildsEntrepriseQuestionnaireResults;
use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Models\FichierType;
use App\Models\User;
use App\Services\ClientEntrepriseTableExportService;
use App\Services\DossierInstructionShowPresenter;
use App\Services\DossierTableExportService;
use App\Services\InstructionAnalyseCritiqueDossierDocumentService;
use App\Services\InstructionDossierConsultationService;
use App\Services\WorkflowEmailNotificationService;
use Dompdf\Canvas;
use Dompdf\FontMetrics;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    use AppliesEntrepriseListIndexFilters;
    use BuildsEntrepriseQuestionnaireResults;
    use ResolvesRoleSpace;
    use StoresDossierPieces;

    /**
     * Requête liste entreprises avec les mêmes filtres que la page index (export inclus).
     *
     * @param  bool|null  $prospectFilter  null = tous ; true = prospects uniquement ; false = clients uniquement (non prospect)
     * @return Builder<\App\Models\Entreprise>
     */
    protected function entreprisesFilteredListQuery(Request $request, ?bool $prospectFilter = null): Builder
    {
        $structurationStatus = $prospectFilter === true
            ? null
            : DossierEntreeRelation::normalizeClientStructurationFilter($request->query('client_structuration_status'));
        $filters = $this->parsePromuClientAndAgenceGestionnaireFilters($request, true);
        if ($prospectFilter === true) {
            $filters['promu_client_from'] = null;
            $filters['promu_client_to'] = null;
        }

        $query = Entreprise::query()
            ->with(['forme', 'user', 'dossierEntreeRelation', 'agence', 'gestionnaire'])
            ->withCount('dossiers')
            ->when($prospectFilter === true, fn ($q) => $q->where('prospect', true))
            ->when($prospectFilter === false, fn ($q) => $q->where('prospect', false))
            ->when($structurationStatus, fn ($q) => $q->whereClientStructurationStatus($structurationStatus));
        $this->applyPromuAgenceGestionnaireFiltersToQuery($query, $filters, true);

        return $query;
    }

    public function entreprisesIndex(Request $request)
    {
        $space = $this->resolveSpace();
        $structurationStatus = DossierEntreeRelation::normalizeClientStructurationFilter($request->query('client_structuration_status'));
        $listeKind = in_array($space['route'], ['dg', 'dga'], true) ? 'clients' : 'all';
        $prospectFilter = $listeKind === 'clients' ? false : null;

        $entreprises = $this->entreprisesFilteredListQuery($request, $prospectFilter)->orderByDesc('id')->paginate(25)->withQueryString();

        $agenceIds = Entreprise::query()->whereNotNull('agence_id')->distinct()->pluck('agence_id');
        $gestionnaireIds = Entreprise::query()->whereNotNull('gestionnaire_id')->distinct()->pluck('gestionnaire_id');
        $agences = Agence::query()->whereIn('id', $agenceIds)->orderBy('name')->get(['id', 'name']);
        $gestionnaires = User::query()->whereIn('id', $gestionnaireIds)->orderBy('name')->get(['id', 'name']);

        return view('RoleSpace.entreprises.index', compact('space', 'entreprises', 'structurationStatus', 'agences', 'gestionnaires', 'listeKind'));
    }

    /**
     * Liste prospects (DG / DGA) : vue transverse, distincte des clients.
     */
    public function prospectsIndex(Request $request)
    {
        $space = $this->resolveSpace();
        abort_unless(in_array($space['route'], ['dg', 'dga'], true), 404);

        $structurationStatus = null;
        $listeKind = 'prospects';

        $entreprises = $this->entreprisesFilteredListQuery($request, true)->orderByDesc('id')->paginate(25)->withQueryString();

        $agenceIds = Entreprise::query()->whereNotNull('agence_id')->distinct()->pluck('agence_id');
        $gestionnaireIds = Entreprise::query()->whereNotNull('gestionnaire_id')->distinct()->pluck('gestionnaire_id');
        $agences = Agence::query()->whereIn('id', $agenceIds)->orderBy('name')->get(['id', 'name']);
        $gestionnaires = User::query()->whereIn('id', $gestionnaireIds)->orderBy('name')->get(['id', 'name']);

        return view('RoleSpace.entreprises.index', compact('space', 'entreprises', 'structurationStatus', 'agences', 'gestionnaires', 'listeKind'));
    }

    public function entreprisesExport(Request $request)
    {
        $space = $this->resolveSpace();
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $prospectFilter = in_array($space['route'], ['dg', 'dga'], true) ? false : null;
        $items = $this->entreprisesFilteredListQuery($request, $prospectFilter)->orderByDesc('id')->get();
        $rows = ClientEntrepriseTableExportService::rowsPortfolio($items);
        $suffix = $prospectFilter === false ? ' — clients' : ' — liste entreprises / clients';
        $title = ($space['title'] ?? 'Angara').$suffix;
        $slug = ($prospectFilter === false ? 'clients-' : 'entreprises-').($space['route'] ?? 'espace');

        return ClientEntrepriseTableExportService::download(
            $rows,
            ClientEntrepriseTableExportService::headersPortfolio(),
            $format,
            $slug,
            $title,
        );
    }

    public function prospectsExport(Request $request)
    {
        $space = $this->resolveSpace();
        abort_unless(in_array($space['route'], ['dg', 'dga'], true), 404);

        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $items = $this->entreprisesFilteredListQuery($request, true)->orderByDesc('id')->get();
        $rows = ClientEntrepriseTableExportService::rowsPortfolio($items);
        $title = ($space['title'] ?? 'Angara').' — prospects';

        return ClientEntrepriseTableExportService::download(
            $rows,
            ClientEntrepriseTableExportService::headersPortfolio(),
            $format,
            'prospects-'.($space['route'] ?? 'espace'),
            $title,
        );
    }

    public function entrepriseShow(string $token)
    {
        $space = $this->resolveSpace();

        $item = Entreprise::query()->where('token', $token)->first();

        if (! $item) {
            // Souvent le même format SHA-1 que le token entreprise : éviter 404 si l’URL utilise le token dossier.
            $dossier = Dossier::query()
                ->where('token', $token)
                ->with('entreprise')
                ->first();
            $entrepriseToken = $dossier?->entreprise?->token;
            if ($entrepriseToken) {
                return redirect()->route($space['route'].'.entreprises.show', $entrepriseToken);
            }

            abort(404);
        }

        $this->assertPortfolioEntrepriseAccess($item, $space);

        $item->load([
            'user',
            'forme',
            'filiere',
            'branche',
            'produit',
            'produits.filiere',
            'produits.branche',
            'appuis.type',
            'critereAvis.user',
            'village',
            'quartier',
            'arrondissement',
            'departement',
            'region',
            'agence.representation',
            'tiers.person',
            'tiers.company.produit',
            'dossiers.programme',
            'dossiers.analyste',
            'dossiers.gestionnaire',
            'dossierEntreeRelation.qualificationUser',
            'dossierEntreeRelation.programmesSubmittedBy',
            'dossierEntreeRelation.qualificationValidatedByAgenceUser',
            'dossierEntreeRelation.instructionValidatedBy',
            'dossierEntreeRelation.programmeSelections.programme',
            'dossierEntreeRelation.programmeSelections.instructionDossier',
            'dossierAnalyseCritique',
            'juridiqueAvisUser',
            'conformiteAvisUser',
            'promuClientUser',
            'prospectRejectedUser',
        ]);

        $mr = $this->buildQuestionnaireResults($item);
        $checklist = $item->piecesExigiblesChecklist();

        return view('RoleSpace.entreprises.show', compact('space', 'item', 'mr', 'checklist'));
    }

    /**
     * Fiche entreprise / prospect — export PDF (côté serveur).
     */
    public function entrepriseFichePdf(string $token)
    {
        $space = $this->resolveSpace();

        $item = Entreprise::query()->where('token', $token)->firstOrFail();
        $this->assertPortfolioEntrepriseAccess($item, $space);

        $item->load([
            'user',
            'forme',
            'filiere',
            'branche',
            'produit',
            'produits.filiere',
            'produits.branche',
            'appuis.type',
            'critereAvis.user',
            'village',
            'quartier',
            'arrondissement',
            'departement',
            'region',
            'agence.representation',
            'gestionnaire',
            'tiers.person',
            'tiers.company.produit',
            'dossiers.programme',
            'dossiers.analyste',
            'dossiers.gestionnaire',
            'dossierEntreeRelation.qualificationUser',
            'dossierEntreeRelation.programmesSubmittedBy',
            'dossierEntreeRelation.qualificationValidatedByAgenceUser',
            'dossierEntreeRelation.instructionValidatedBy',
            'dossierEntreeRelation.programmeSelections.programme',
            'dossierEntreeRelation.programmeSelections.instructionDossier',
            'dossierAnalyseCritique',
            'juridiqueAvisUser',
            'conformiteAvisUser',
            'promuClientUser',
            'prospectRejectedUser',
            'sites.region',
            'sites.departement',
            'sites.arrondissement',
            'sites.village',
            'sites.quartier',
            'equipeMembres.site',
            'equipeMembres.cniFichier',
            'reponses.question',
            'reponses.choice',
        ]);

        $mr = $this->buildQuestionnaireResults($item);
        $checklist = $item->piecesExigiblesChecklist();

        $logoData = '';
        $logoPath = public_path('img/logo-bcpme.png');
        if (is_readable($logoPath)) {
            $logoData = base64_encode((string) file_get_contents($logoPath));
        }
        $generatedAt = now();

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('RoleSpace.entreprises.fiche_pdf', [
            'space' => $space,
            'item' => $item,
            'mr' => $mr,
            'checklist' => $checklist,
            'logoData' => $logoData,
            'generatedAt' => $generatedAt,
        ]);
        $pdf->setCallbacks([
            [
                'event' => 'end_document',
                'f' => function (int $pageNumber, int $pageCount, Canvas $canvas, FontMetrics $fontMetrics): void {
                    $font = $fontMetrics->get_font('DejaVu Sans', 'normal');
                    $size = 8;
                    $color = [0.35, 0.35, 0.35];
                    $w = $canvas->get_width();
                    $h = $canvas->get_height();
                    $y = $h - 28;
                    $pageLabel = 'Page '.$pageNumber.' / '.$pageCount;
                    $tw = $canvas->get_text_width($pageLabel, $font, $size);
                    $canvas->text($w - $tw - 18, $y, $pageLabel, $font, $size, $color);
                    $canvas->text(18, $y, 'BC-PME — Angara', $font, $size, $color);
                },
            ],
        ]);

        $nameSlug = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) ($item->name ?: $item->token));
        $filename = ($item->prospect ? 'fiche-prospect-' : 'fiche-client-').$nameSlug.'.pdf';

        return $pdf->download($filename);
    }

    protected function assertPortfolioEntrepriseAccess(Entreprise $item, array $space): void
    {
        if (($space['route'] ?? '') === 'analyste-juridique') {
            $allowed = Dossier::query()
                ->where('entreprise_id', $item->id)
                ->where('juridique_analyste_user_id', auth()->id())
                ->whereNotNull('juridique_instruction_submitted_at')
                ->exists();
            if (! $allowed) {
                abort(403);
            }
        }

        if (($space['route'] ?? '') === 'analyste-credit') {
            $allowed = Dossier::query()
                ->where('entreprise_id', $item->id)
                ->where('reng_analyste_credit_user_id', auth()->id())
                ->whereNotNull('juridique_submitted_to_engagements_at')
                ->exists();
            if (! $allowed) {
                abort(403);
            }
        }

        if (($space['route'] ?? '') === 'analyste-risques') {
            $allowed = Dossier::query()
                ->where('entreprise_id', $item->id)
                ->where('rerx_analyste_risques_user_id', auth()->id())
                ->whereNotNull('reng_submitted_to_risques_at')
                ->exists();
            if (! $allowed) {
                abort(403);
            }
        }

        // DG / DGA : consultation transverse de toutes les fiches prospects et clients (pas de restriction dossier instruction).
    }

    public function entreprisePieces(string $token)
    {
        $space = $this->resolveSpace();
        $entreprise = Entreprise::query()->where('token', $token)->first();
        if (! $entreprise) {
            $dossier = Dossier::query()->where('token', $token)->with('entreprise')->first();
            $entrepriseToken = $dossier?->entreprise?->token;
            if ($entrepriseToken) {
                return redirect()->route($space['route'].'.entreprises.pieces', $entrepriseToken);
            }
            abort(404);
        }

        if (($space['route'] ?? '') === 'analyste-juridique') {
            $allowed = Dossier::query()
                ->where('entreprise_id', $entreprise->id)
                ->where('juridique_analyste_user_id', auth()->id())
                ->whereNotNull('juridique_instruction_submitted_at')
                ->exists();
            if (! $allowed) {
                abort(403);
            }
        }

        if (($space['route'] ?? '') === 'analyste-credit') {
            $allowed = Dossier::query()
                ->where('entreprise_id', $entreprise->id)
                ->where('reng_analyste_credit_user_id', auth()->id())
                ->whereNotNull('juridique_submitted_to_engagements_at')
                ->exists();
            if (! $allowed) {
                abort(403);
            }
        }

        if (($space['route'] ?? '') === 'analyste-risques') {
            $allowed = Dossier::query()
                ->where('entreprise_id', $entreprise->id)
                ->where('rerx_analyste_risques_user_id', auth()->id())
                ->whereNotNull('reng_submitted_to_risques_at')
                ->exists();
            if (! $allowed) {
                abort(403);
            }
        }

        // DG / DGA : même périmètre que la fiche entreprise (consultation transverse).

        $checklist = $entreprise->piecesExigiblesChecklist();

        return view('RoleSpace.entreprises.pieces', compact('space', 'entreprise', 'checklist'));
    }

    public function dossiersIndex()
    {
        $space = $this->resolveSpace();
        if (in_array($space['route'], ['dg', 'dga'], true)) {
            $rn = (string) (request()->route()?->getName() ?? '');
            if ($rn === $space['route'].'.dossiers.index') {
                return redirect()->route($space['route'].'.dossiers.valides-chef-agence');
            }
        }
        $dossiersFilter = null;
        $routeName = (string) (request()->route()?->getName() ?? '');
        $dossiersVue = 'default';
        if (in_array($space['route'], ['dg', 'dga'], true)) {
            $dossiersVue = match ($routeName) {
                $space['route'].'.dossiers.en-attente-direction' => 'direction_pending',
                default => 'valides_chef_agence',
            };
        }
        $query = $this->dossiersFilteredListQuery(request(), $space, $dossiersVue, $dossiersFilter);
        $dossiers = $query->orderByDesc('id')->paginate(25)->withQueryString();

        return view('RoleSpace.dossiers.index', compact('space', 'dossiers', 'dossiersFilter', 'dossiersVue'));
    }

    /**
     * Requête liste dossiers avec filtres (export inclus).
     *
     * @param  array<string, mixed>  $space
     * @return Builder<Dossier>
     */
    protected function dossiersFilteredListQuery(Request $request, array $space, string $dossiersVue, ?string &$dossiersFilter): Builder
    {
        $with = ['entreprise', 'programme', 'analyste', 'gestionnaire'];
        if ($space['route'] === 'respexp') {
            $with[] = 'exploitationAnalysteAssignedBy';
        }
        if ($space['route'] === 'juridique') {
            $with[] = 'juridiqueInstructionSubmittedBy';
        }
        if (in_array($space['route'], ['juridique', 'analyste-juridique'], true)) {
            $with[] = 'juridiqueAnalysteUser';
        }
        if (in_array($space['route'], ['reng', 'analyste-credit', 'rerx'], true)) {
            $with[] = 'rengAnalysteCreditUser';
        }
        if (in_array($space['route'], ['rerx', 'analyste-risques', 'dg', 'dga'], true)) {
            $with[] = 'rerxAnalysteRisquesUser';
        }
        if (in_array($space['route'], ['dg', 'dga'], true)) {
            $with[] = 'instructionProgrammes.programme';
        }

        $query = Dossier::query()->with($with);

        // Contraintes de périmètre (workflow par espace)
        if ($space['route'] === 'respexp' && $request->query('filter') === 'a_affecter') {
            $query->whereNull('analyste_id');
            $dossiersFilter = 'a_affecter';
        }
        if ($space['route'] === 'juridique') {
            $query->whereNotNull('juridique_instruction_submitted_at');
        }
        if ($space['route'] === 'analyste-juridique') {
            $query->where('juridique_analyste_user_id', auth()->id())
                ->whereNotNull('juridique_instruction_submitted_at');
        }
        if ($space['route'] === 'reng') {
            $query->whereNotNull('juridique_submitted_to_engagements_at');
        }
        if ($space['route'] === 'analyste-credit') {
            $query->where('reng_analyste_credit_user_id', auth()->id())
                ->whereNotNull('juridique_submitted_to_engagements_at');
        }
        if ($space['route'] === 'rerx') {
            $query->whereNotNull('reng_submitted_to_risques_at');
        }
        if ($space['route'] === 'analyste-risques') {
            $query->where('rerx_analyste_risques_user_id', auth()->id())
                ->whereNotNull('reng_submitted_to_risques_at');
        }
        if (in_array($space['route'], ['dg', 'dga'], true)) {
            if ($dossiersVue === 'direction_pending') {
                $query->awaitingDirectionGeneralConclusion();
            } else {
                $query->instructionValidesParChefAgence();
            }
        }

        // Filtres UI
        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->whereHas('entreprise', fn (Builder $qq) => $qq->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('programme', fn (Builder $qq) => $qq->where('name', 'like', '%'.$search.'%'));
            });
        }

        if ($programmeId = $request->query('programme_id')) {
            $query->where('programme_id', $programmeId);
        }
        if ($gestionnaireId = $request->query('gestionnaire_id')) {
            $query->where('gestionnaire_id', $gestionnaireId);
        }
        if ($analysteId = $request->query('analyste_id')) {
            $query->where('analyste_id', $analysteId);
        }
        if ($state = $request->query('instruction_state')) {
            if ($state === 'pending') {
                $query->doesntHave('indicateurs');
            } elseif ($state === 'in_progress') {
                $query->whereHas('indicateurs');
            }
        }
        if ($statut = $request->query('instruction_statut')) {
            $allowed = \App\Services\DossierInstructionStatutService::allCodes();
            if (in_array($statut, $allowed, true)) {
                $query->whereInstructionStatut($statut);
            }
        }
        if ($createdFrom = $request->query('created_from')) {
            $query->where('created_at', '>=', \Carbon\Carbon::parse($createdFrom)->startOfDay());
        }
        if ($createdTo = $request->query('created_to')) {
            $query->where('created_at', '<=', \Carbon\Carbon::parse($createdTo)->endOfDay());
        }

        return $query;
    }

    public function dossiersExport(Request $request)
    {
        $space = $this->resolveSpace();
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $dossiersVue = (string) $request->query('vue', 'default');
        if (! in_array($dossiersVue, ['default', 'direction_pending', 'valides_chef_agence'], true)) {
            $dossiersVue = 'default';
        }
        $dossiersFilter = null;
        $items = $this->dossiersFilteredListQuery($request, $space, $dossiersVue, $dossiersFilter)->orderByDesc('id')->get();

        $rows = DossierTableExportService::rowsRoleSpace($items);
        $title = ($space['title'] ?? 'Angara').' — liste dossiers';

        return DossierTableExportService::download(
            $rows,
            $format,
            'dossiers-'.($space['route'] ?? 'espace'),
            $title,
        );
    }

    public function dossierShow(string $token)
    {
        $space = $this->resolveSpace();
        $with = [
            'entreprise',
            'programme',
            'instructionProgrammes.programme',
            'analyste',
            'gestionnaire',
            'agence',
            'indicateurs',
            'reponses',
            'chefFiliereSubmittedToAgenceBy',
            'instructionAgenceValidatedBy',
            'instructionAgenceRejectedBy',
        ];
        $hubSpace = in_array($space['route'], ['respexp', 'juridique', 'analyste-juridique', 'reng', 'analyste-credit', 'rerx', 'analyste-risques', 'dg', 'dga'], true);
        if ($hubSpace) {
            $with[] = 'exploitationAvisCreditUser';
            $with[] = 'exploitationEngagementsDecisionUser';
            $with[] = 'exploitationAnalysteAssignedBy';
            $with[] = 'exploitationAnalysteTransmittedToExploitationBy';
            $with[] = 'instructionCaTransmittedToExploitationBy';
            $with[] = 'juridiqueInstructionSubmittedBy';
        }
        if (in_array($space['route'], ['juridique', 'analyste-juridique'], true)) {
            $with[] = 'juridiqueAnalysteUser';
            $with[] = 'juridiqueAnalysteAssignedBy';
            $with[] = 'juridiqueAnalysteSubmittedToRejuBy';
            $with[] = 'juridiqueSubmittedToEngagementsBy';
        }
        if (in_array($space['route'], ['reng', 'analyste-credit', 'rerx'], true)) {
            $with[] = 'rengAnalysteCreditUser';
            $with[] = 'rengAnalysteCreditAssignedBy';
            $with[] = 'rengAnalysteCreditSubmittedBy';
            $with[] = 'rengSubmittedToRisquesBy';
            $with[] = 'juridiqueSubmittedToEngagementsBy';
        }
        if ($space['route'] === 'analyste-credit') {
            $with[] = 'juridiqueAnalysteUser';
            $with[] = 'juridiqueAnalysteAssignedBy';
            $with[] = 'juridiqueAnalysteSubmittedToRejuBy';
            $with[] = 'rerxAnalysteRisquesUser';
            $with[] = 'rerxAnalysteRisquesAssignedBy';
            $with[] = 'rerxAnalysteRisquesSubmittedBy';
            $with[] = 'rerxSubmittedToDirectionBy';
        }
        if (in_array($space['route'], ['rerx', 'analyste-risques', 'dg', 'dga'], true)) {
            $with[] = 'rerxAnalysteRisquesUser';
            $with[] = 'rerxAnalysteRisquesAssignedBy';
            $with[] = 'rerxAnalysteRisquesSubmittedBy';
            $with[] = 'rerxSubmittedToDirectionBy';
        }

        $with[] = 'fichiersDossier.type';
        $with[] = 'fichiersDossier.uploadedBy';

        $dossier = $this->dossierQueryForCurrentSpace($token)->with($with)->firstOrFail();

        $instructionConsultation = app(InstructionDossierConsultationService::class)->build($dossier);

        $analystesExploitation = collect();
        $analystesJuridique = collect();
        $analystesCredit = collect();
        $analystesRisques = collect();

        if ($space['route'] === 'respexp') {
            $profilInstructionId = (int) config('angara.role_analyste_financier', 17);
            if ($profilInstructionId < 1) {
                $profilInstructionId = 17;
            }

            // Tous les utilisateurs au profil instruction : même liste pour 1re affectation ou réaffectation
            $analystesExploitation = User::query()
                ->where('role_id', $profilInstructionId)
                ->where(function ($q) {
                    $q->where('active', true)
                        ->orWhere('active', 1)
                        ->orWhereNull('active');
                })
                ->with('agence:id,name')
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'agence_id']);
        }

        if ($space['route'] === 'juridique') {
            $profilAjId = (int) config('angara.role_analyste_juridique', 19);
            if ($profilAjId < 1) {
                $profilAjId = 19;
            }
            $analystesJuridique = User::query()
                ->where('role_id', $profilAjId)
                ->where(function ($q) {
                    $q->where('active', true)
                        ->orWhere('active', 1)
                        ->orWhereNull('active');
                })
                ->with('agence:id,name')
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'agence_id']);
        }

        if ($space['route'] === 'reng') {
            $profilAcId = (int) config('angara.role_analyste_credit', 20);
            if ($profilAcId < 1) {
                $profilAcId = 20;
            }
            $analystesCredit = User::query()
                ->where('role_id', $profilAcId)
                ->where(function ($q) {
                    $q->where('active', true)
                        ->orWhere('active', 1)
                        ->orWhereNull('active');
                })
                ->with('agence:id,name')
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'agence_id']);
        }

        if ($space['route'] === 'rerx') {
            $profilArId = (int) config('angara.role_analyste_risques', 18);
            if ($profilArId < 1) {
                $profilArId = 18;
            }
            $analystesRisques = User::query()
                ->where('role_id', $profilArId)
                ->where(function ($q) {
                    $q->where('active', true)
                        ->orWhere('active', 1)
                        ->orWhereNull('active');
                })
                ->with('agence:id,name')
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'agence_id']);
        }

        $exploitationSteps = $hubSpace
            ? $dossier->exploitationWorkflowSteps()
            : [];

        $respexpInstructionLocked = $space['route'] === 'respexp'
            && (bool) $dossier->analyste_id
            && ! $dossier->isInstructionVisibleToResponsableExploitation();

        $fichierTypes = FichierType::query()->orderBy('name')->get(['id', 'name']);

        $piecesModalId = 'dossierPieceUploadModal_'.preg_replace('/\W+/', '_', $space['route']);

        $delegation = app(\App\Services\InstructionDelegationService::class);
        $canCloseInstruction = $delegation->userCanCloseInstruction(auth()->user(), $dossier);
        $instructionClosureRuleDescription = $delegation->describeRuleForInstructionClosure($dossier);
        $instructionClosureStatutLabel = $delegation->instructionClosureStatutLabel($dossier);

        return view('RoleSpace.dossiers.show', compact(
            'space',
            'dossier',
            'analystesExploitation',
            'analystesJuridique',
            'analystesCredit',
            'analystesRisques',
            'exploitationSteps',
            'respexpInstructionLocked',
            'instructionConsultation',
            'fichierTypes',
            'piecesModalId',
            'canCloseInstruction',
            'instructionClosureRuleDescription',
            'instructionClosureStatutLabel',
        ));
    }

    /**
     * Requête dossier filtrée comme pour l’affichage (périmètre par espace).
     */
    protected function dossierQueryForCurrentSpace(string $token): Builder
    {
        $space = $this->resolveSpace();
        $dossierQuery = Dossier::query()->where('token', $token);

        if ($space['route'] === 'juridique') {
            $dossierQuery->whereNotNull('juridique_instruction_submitted_at');
        }

        if ($space['route'] === 'analyste-juridique') {
            $dossierQuery
                ->where('juridique_analyste_user_id', auth()->id())
                ->whereNotNull('juridique_instruction_submitted_at');
        }

        if ($space['route'] === 'reng') {
            $dossierQuery->whereNotNull('juridique_submitted_to_engagements_at');
        }

        if ($space['route'] === 'analyste-credit') {
            $dossierQuery
                ->where('reng_analyste_credit_user_id', auth()->id())
                ->whereNotNull('juridique_submitted_to_engagements_at');
        }

        if ($space['route'] === 'rerx') {
            $dossierQuery->whereNotNull('reng_submitted_to_risques_at');
        }

        if ($space['route'] === 'analyste-risques') {
            $dossierQuery
                ->where('rerx_analyste_risques_user_id', auth()->id())
                ->whereNotNull('reng_submitted_to_risques_at');
        }

        if (in_array($space['route'], ['dg', 'dga'], true)) {
            $dossierQuery->instructionValidesParChefAgence();
        }

        return $dossierQuery;
    }

    public function storeDossierPiece(Request $request, string $token): RedirectResponse
    {
        $space = $this->resolveSpace();
        $dossier = $this->dossierQueryForCurrentSpace($token)->firstOrFail();
        $redirectRoute = $space['route'].'.dossiers.show';

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route($redirectRoute, $dossier->token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        return $this->completeDossierPieceUpload($request, $dossier, $redirectRoute, $dossier->token);
    }

    public function assignRerxAnalysteRisques(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('reng_submitted_to_risques_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if ($dossier->isSubmittedToDirectionFromRerx()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['rerx_analyste_risques_user_id' => 'Le dossier a déjà été transmis à la direction.']);
        }
        if ($dossier->isRerxAnalysteRisquesSubmittedToRerx()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['rerx_analyste_risques_user_id' => 'L’analyste risques a déjà soumis son travail : l’affectation ne peut plus être modifiée.']);
        }

        $profilId = (int) config('angara.role_analyste_risques', 18);
        if ($profilId < 1) {
            $profilId = 18;
        }

        $validated = $request->validate([
            'rerx_analyste_risques_user_id' => 'required|integer|exists:users,id',
        ]);

        $analyste = User::query()->whereKey($validated['rerx_analyste_risques_user_id'])->firstOrFail();

        if ((int) $analyste->role_id !== $profilId) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['rerx_analyste_risques_user_id' => 'L’utilisateur choisi n’est pas un analyste risques (profil '.$profilId.').']);
        }
        if (! $analyste->active) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['rerx_analyste_risques_user_id' => 'Ce compte est inactif.']);
        }

        $dossier->rerx_analyste_risques_user_id = $analyste->id;
        $dossier->rerx_analyste_risques_assigned_at = now();
        $dossier->rerx_analyste_risques_assigned_by_user_id = auth()->id();
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Affectation de dossier — pôle risques',
            title: 'Un dossier vous a été affecté',
            body: "Vous avez été affecté(e) sur un dossier.\n\nMerci de consulter le dossier et d’effectuer les actions attendues dans votre espace.",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('analyste-risques.dossiers.show', $dossier->token),
            event: 'assign_rerx_analyste_risques'
        );
        $mailer->notifyUser($analyste, auth()->user(), $payload, $ctx);

        return redirect()
            ->route('rerx.dossiers.show', $token)
            ->with('success', 'Dossier affecté à '.$analyste->name.'.');
    }

    public function storeRerxResponsableAvis(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('reng_submitted_to_risques_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if (! $dossier->isRerxAnalysteRisquesSubmittedToRerx()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['rerx_responsable_avis' => 'L’analyste risques doit d’abord soumettre son analyse et son avis.']);
        }
        if ($dossier->isSubmittedToDirectionFromRerx()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['rerx_responsable_avis' => 'Le dossier a déjà été transmis à la direction.']);
        }

        $validated = $request->validate([
            'rerx_responsable_avis' => 'nullable|string|max:65535',
        ]);

        $dossier->rerx_responsable_avis = $validated['rerx_responsable_avis'] ?? null;
        $dossier->rerx_responsable_avis_at = now();
        $dossier->rerx_responsable_avis_by_user_id = auth()->id();
        $dossier->save();

        return redirect()
            ->route('rerx.dossiers.show', $token)
            ->with('success', 'Avis du responsable risques enregistré.');
    }

    public function submitRerxToDirection(string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('reng_submitted_to_risques_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if (! $dossier->isRerxAnalysteRisquesSubmittedToRerx()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['direction' => 'L’analyste risques doit d’abord soumettre son dossier.']);
        }
        if (! $dossier->hasRerxResponsableAvisFilled()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['direction' => 'Renseignez et enregistrez votre avis avant la transmission à la direction (DG & DGA).']);
        }
        if ($dossier->isSubmittedToDirectionFromRerx()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['direction' => 'Le dossier a déjà été transmis à la direction.']);
        }

        $dossier->rerx_submitted_to_direction_at = now();
        $dossier->rerx_submitted_to_direction_by_user_id = auth()->id();
        // Réouverture suite à rejet inter-pôle (Direction vers RISQ) : on réinitialise les marqueurs.
        $dossier->direction_rejected_to_risques_at = null;
        $dossier->direction_rejected_to_risques_by_user_id = null;
        $dossier->direction_rejected_to_risques_motif = null;
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $dgPayload = $mailer->buildPayload(
            subject: 'Transmission de dossier — direction (DG)',
            title: 'Un dossier a été transmis à la direction',
            body: "Un dossier d’instruction a été transmis à la direction (DG & DGA).\n\nMerci de consulter le dossier et d’effectuer les actions attendues.",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('dg.dossiers.show', $dossier->token),
            event: 'submit_rerx_to_direction'
        );
        $dgaPayload = $mailer->buildPayload(
            subject: 'Transmission de dossier — direction (DGA)',
            title: 'Un dossier a été transmis à la direction',
            body: "Un dossier d’instruction a été transmis à la direction (DG & DGA).\n\nMerci de consulter le dossier et d’effectuer les actions attendues.",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('dga.dossiers.show', $dossier->token),
            event: 'submit_rerx_to_direction'
        );

        $dgRecipients = $mailer->recipientsByRole((int) config('angara.role_dg', 4));
        $dgaRecipients = $mailer->recipientsByRole((int) config('angara.role_dga', 5));
        $mailer->notifyUsers($dgRecipients, auth()->user(), $dgPayload, $ctx);
        $mailer->notifyUsers($dgaRecipients, auth()->user(), $dgaPayload, $ctx);

        return redirect()
            ->route('rerx.dossiers.show', $token)
            ->with('success', 'Dossier transmis au directeur général et au directeur général adjoint.');
    }

    public function assignJuridiqueAnalyste(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_instruction_submitted_at')
            ->firstOrFail();

        if ($dossier->isSubmittedToEngagementsFromJuridique()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['juridique_analyste_user_id' => 'Le dossier a déjà été transmis au responsable engagements.']);
        }
        if ($dossier->isJuridiqueAnalysteAvisSubmittedToReju()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['juridique_analyste_user_id' => 'L’analyste juridique a déjà soumis son avis : l’affectation ne peut plus être modifiée.']);
        }

        $profilAjId = (int) config('angara.role_analyste_juridique', 19);
        if ($profilAjId < 1) {
            $profilAjId = 19;
        }

        $validated = $request->validate([
            'juridique_analyste_user_id' => 'required|integer|exists:users,id',
        ]);

        $analyste = User::query()->whereKey($validated['juridique_analyste_user_id'])->firstOrFail();

        if ((int) $analyste->role_id !== $profilAjId) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['juridique_analyste_user_id' => 'L’utilisateur choisi n’est pas un analyste juridique (profil '.$profilAjId.').']);
        }
        if (! $analyste->active) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['juridique_analyste_user_id' => 'Ce compte est inactif.']);
        }

        $dossier->juridique_analyste_user_id = $analyste->id;
        $dossier->juridique_analyste_assigned_at = now();
        $dossier->juridique_analyste_assigned_by_user_id = auth()->id();
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Affectation de dossier — pôle juridique',
            title: 'Un dossier vous a été affecté',
            body: "Vous avez été affecté(e) sur un dossier.\n\nMerci de consulter le dossier et de préparer votre avis.",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('analyste-juridique.dossiers.show', $dossier->token),
            event: 'assign_juridique_analyste'
        );
        $mailer->notifyUser($analyste, auth()->user(), $payload, $ctx);

        $msg = 'Dossier affecté à '.$analyste->name.'.';

        return redirect()
            ->route('juridique.dossiers.show', $token)
            ->with('success', $msg);
    }

    public function storeJuridiqueResponsableAvis(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_instruction_submitted_at')
            ->firstOrFail();

        if (! $dossier->isJuridiqueAnalysteAvisSubmittedToReju()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['juridique_responsable_avis' => 'L’analyste juridique doit d’abord soumettre son avis.']);
        }
        if ($dossier->isSubmittedToEngagementsFromJuridique()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['juridique_responsable_avis' => 'Le dossier a déjà été transmis au responsable engagements.']);
        }

        $validated = $request->validate([
            'juridique_responsable_avis' => 'nullable|string|max:65535',
        ]);

        $dossier->juridique_responsable_avis = $validated['juridique_responsable_avis'] ?? null;
        $dossier->juridique_responsable_avis_at = now();
        $dossier->juridique_responsable_avis_by_user_id = auth()->id();
        $dossier->save();

        return redirect()
            ->route('juridique.dossiers.show', $token)
            ->with('success', 'Avis du responsable juridique enregistré.');
    }

    public function submitJuridiqueToEngagements(string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_instruction_submitted_at')
            ->firstOrFail();

        if (! $dossier->isJuridiqueAnalysteAvisSubmittedToReju()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['engagements' => 'L’analyste juridique doit d’abord soumettre son avis.']);
        }
        if (! $dossier->hasJuridiqueResponsableAvisFilled()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['engagements' => 'Renseignez et enregistrez votre avis de responsable juridique avant la transmission.']);
        }
        if ($dossier->isSubmittedToEngagementsFromJuridique()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['engagements' => 'Le dossier a déjà été transmis au responsable engagements.']);
        }

        $dossier->juridique_submitted_to_engagements_at = now();
        $dossier->juridique_submitted_to_engagements_by_user_id = auth()->id();
        // Réouverture suite à rejet inter-pôle (RENG vers RJU) : on réinitialise les marqueurs.
        $dossier->engagements_rejected_to_juridique_at = null;
        $dossier->engagements_rejected_to_juridique_by_user_id = null;
        $dossier->engagements_rejected_to_juridique_motif = null;
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Transmission de dossier — responsable engagements',
            title: 'Un dossier a été transmis au responsable engagements',
            body: "Un dossier vient d’être transmis au pôle engagements.\n\nMerci de consulter le dossier et d’effectuer les actions attendues.",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('reng.dossiers.show', $dossier->token),
            event: 'submit_juridique_to_engagements'
        );
        $recipients = $mailer->recipientsByRole((int) config('angara.role_responsable_engagements', 9));
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        return redirect()
            ->route('juridique.dossiers.show', $token)
            ->with('success', 'Dossier transmis au responsable engagements.');
    }

    public function assignRengAnalysteCredit(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if ($dossier->isSubmittedToRisquesFromReng()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['reng_analyste_credit_user_id' => 'Le dossier a déjà été transmis au responsable risques.']);
        }
        if ($dossier->isRengAnalysteCreditSubmittedToReng()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['reng_analyste_credit_user_id' => 'L’analyste crédit a déjà soumis son travail : l’affectation ne peut plus être modifiée.']);
        }

        $profilId = (int) config('angara.role_analyste_credit', 20);
        if ($profilId < 1) {
            $profilId = 20;
        }

        $validated = $request->validate([
            'reng_analyste_credit_user_id' => 'required|integer|exists:users,id',
        ]);

        $analyste = User::query()->whereKey($validated['reng_analyste_credit_user_id'])->firstOrFail();

        if ((int) $analyste->role_id !== $profilId) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['reng_analyste_credit_user_id' => 'L’utilisateur choisi n’est pas un analyste crédit (profil '.$profilId.').']);
        }
        if (! $analyste->active) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['reng_analyste_credit_user_id' => 'Ce compte est inactif.']);
        }

        $dossier->reng_analyste_credit_user_id = $analyste->id;
        $dossier->reng_analyste_credit_assigned_at = now();
        $dossier->reng_analyste_credit_assigned_by_user_id = auth()->id();
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Affectation de dossier — engagements',
            title: 'Un dossier vous a été affecté',
            body: "Vous avez été affecté(e) sur un dossier.\n\nMerci de consulter le dossier et de préparer votre contre-analyse et avis.",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('analyste-credit.dossiers.show', $dossier->token),
            event: 'assign_reng_analyste_credit'
        );
        $mailer->notifyUser($analyste, auth()->user(), $payload, $ctx);

        return redirect()
            ->route('reng.dossiers.show', $token)
            ->with('success', 'Dossier affecté à '.$analyste->name.'.');
    }

    public function storeRengResponsableAvis(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if (! $dossier->isRengAnalysteCreditSubmittedToReng()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['reng_responsable_avis' => 'L’analyste crédit doit d’abord soumettre sa contre-analyse et son avis.']);
        }
        if ($dossier->isSubmittedToRisquesFromReng()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['reng_responsable_avis' => 'Le dossier a déjà été transmis au responsable risques.']);
        }

        $validated = $request->validate([
            'reng_responsable_avis' => 'nullable|string|max:65535',
        ]);

        $dossier->reng_responsable_avis = $validated['reng_responsable_avis'] ?? null;
        $dossier->reng_responsable_avis_at = now();
        $dossier->reng_responsable_avis_by_user_id = auth()->id();
        $dossier->save();

        return redirect()
            ->route('reng.dossiers.show', $token)
            ->with('success', 'Avis du responsable engagements enregistré.');
    }

    public function submitRengToRisques(string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if (! $dossier->isRengAnalysteCreditSubmittedToReng()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['risques' => 'L’analyste crédit doit d’abord soumettre son dossier.']);
        }
        if (! $dossier->hasRengResponsableAvisFilled()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['risques' => 'Renseignez et enregistrez votre avis avant la transmission au responsable risques.']);
        }
        if ($dossier->isSubmittedToRisquesFromReng()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['risques' => 'Le dossier a déjà été transmis au responsable risques.']);
        }

        $dossier->reng_submitted_to_risques_at = now();
        $dossier->reng_submitted_to_risques_by_user_id = auth()->id();
        // Réouverture suite à rejet inter-pôle (RISQ vers RENG) : on réinitialise les marqueurs.
        $dossier->risques_rejected_to_engagements_at = null;
        $dossier->risques_rejected_to_engagements_by_user_id = null;
        $dossier->risques_rejected_to_engagements_motif = null;
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Transmission de dossier — responsable risques',
            title: 'Un dossier a été transmis au responsable risques',
            body: "Un dossier vient d’être transmis au pôle risques.\n\nMerci de consulter le dossier et d’effectuer les actions attendues.",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('rerx.dossiers.show', $dossier->token),
            event: 'submit_reng_to_risques'
        );
        $recipients = $mailer->recipientsByRole((int) config('angara.role_responsable_risques', 12));
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        return redirect()
            ->route('reng.dossiers.show', $token)
            ->with('success', 'Dossier transmis au responsable risques.');
    }

    /**
     * Avis de crédit et validation engagements : uniquement après soumission de l’instruction par l’analyste.
     */
    private function respexpInstructionGate(Dossier $dossier): ?\Illuminate\Http\RedirectResponse
    {
        if (! $dossier->analyste_id && ! $dossier->isInstructionCaTransmittedToExploitation()) {
            return redirect()
                ->route('respexp.dossiers.show', $dossier->token)
                ->withErrors(['exploitation_avis_credit' => 'Affectez d’abord un analyste financier au dossier.']);
        }
        if (! $dossier->isInstructionVisibleToResponsableExploitation()) {
            return redirect()
                ->route('respexp.dossiers.show', $dossier->token)
                ->withErrors([
                    'exploitation_avis_credit' => 'L’instruction n’a pas encore été transmise au responsable exploitation (par l’analyste financier ou par le chef d’agence) : vous ne pouvez pas saisir d’avis ni de décision sur les engagements.',
                ]);
        }

        return null;
    }

    public function storeExploitationAvisCredit(Request $request, string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if ($redirect = $this->respexpInstructionGate($dossier)) {
            return $redirect;
        }
        if ($dossier->isSubmittedToJuridique()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['exploitation_avis_credit' => 'Le dossier a été transmis au pôle juridique : l’avis de crédit ne peut plus être modifié.']);
        }

        $validated = $request->validate([
            'exploitation_avis_credit' => 'required|string|max:65535',
        ]);

        $dossier->exploitation_avis_credit = $validated['exploitation_avis_credit'];
        $dossier->exploitation_avis_credit_at = now();
        $dossier->exploitation_avis_credit_user_id = auth()->id();
        $dossier->save();

        return redirect()
            ->route('respexp.dossiers.show', $token)
            ->with('success', 'Avis de crédit enregistré.');
    }

    public function storeExploitationEngagementsDecision(Request $request, string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if (! $dossier->analyste_id && ! $dossier->isInstructionCaTransmittedToExploitation()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['decision' => 'Affectez d’abord un analyste financier au dossier.']);
        }
        if (! $dossier->isInstructionVisibleToResponsableExploitation()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['decision' => 'L’instruction n’a pas encore été transmise au responsable exploitation (par l’analyste financier ou par le chef d’agence) : vous ne pouvez pas statuer.']);
        }
        if ($dossier->isSubmittedToJuridique()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['decision' => 'Le dossier a été transmis au pôle juridique : la décision ne peut plus être modifiée.']);
        }

        $validated = $request->validate([
            'decision' => 'required|in:accord,rejet',
            'comment' => 'nullable|string|max:65535',
        ]);

        if ($validated['decision'] === 'rejet' && ! filled(trim((string) ($validated['comment'] ?? '')))) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['comment' => 'Le motif est obligatoire en cas de rejet.'])
                ->withInput();
        }

        $dossier->exploitation_engagements_decision = $validated['decision'];
        $dossier->exploitation_engagements_decision_comment = $validated['comment'] ?? null;
        $dossier->exploitation_engagements_decision_at = now();
        $dossier->exploitation_engagements_decision_user_id = auth()->id();
        $dossier->save();

        $verb = $validated['decision'] === 'accord' ? 'accord' : 'rejet';

        return redirect()
            ->route('respexp.dossiers.show', $token)
            ->with('success', 'Décision enregistrée ('.$verb.').');
    }

    public function storeSoumettreJuridique(string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if (! $dossier->canRespexpSoumettreAuJuridique()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors([
                    'juridique' => 'Transmission impossible : vérifiez que l’instruction a été transmise par l’analyste, que l’avis de crédit est renseigné et que la validation des engagements est un accord (et que le dossier n’a pas déjà été transmis).',
                ]);
        }

        $dossier->juridique_instruction_submitted_at = now();
        $dossier->juridique_instruction_submitted_by_user_id = auth()->id();
        // Réouverture suite à rejet inter-pôle (RJU vers REXP) : on réinitialise les marqueurs.
        $dossier->juridique_rejected_to_exploitation_at = null;
        $dossier->juridique_rejected_to_exploitation_by_user_id = null;
        $dossier->juridique_rejected_to_exploitation_motif = null;
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Transmission de dossier — pôle juridique',
            title: 'Un dossier a été transmis au pôle juridique',
            body: "Un dossier vient d’être transmis au pôle juridique.\n\nMerci de consulter le dossier et d’effectuer les actions attendues (affectation analyste, avis, transmission).",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('juridique.dossiers.show', $dossier->token),
            event: 'submit_respexp_to_juridique'
        );
        $recipients = $mailer->recipientsByRole((int) config('angara.role_responsable_juridique', 10));
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        return redirect()
            ->route('respexp.dossiers.show', $token)
            ->with('success', 'Dossier transmis au pôle juridique. Il apparaît dans l’espace du responsable juridique.');
    }

    /**
     * Affecte un dossier d’instruction à un analyste financier exploitation (profil configuré).
     */
    public function assignAnalyste(Request $request, string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }

        if ($dossier->isSubmittedToJuridique()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['analyste_user_id' => 'Le dossier a été transmis au pôle juridique : l’affectation ne peut plus être modifiée.']);
        }

        $validated = $request->validate([
            'analyste_user_id' => 'required|integer|exists:users,id',
        ]);

        $analyste = User::query()->whereKey($validated['analyste_user_id'])->firstOrFail();

        if ((int) $analyste->role_id !== (int) config('angara.role_analyste_financier', 17)) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['analyste_user_id' => 'L’utilisateur choisi n’est pas un analyste financier exploitation.']);
        }
        if (! $analyste->active) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['analyste_user_id' => 'Ce compte analyste est inactif.']);
        }

        $wasAssigned = $dossier->analyste_id !== null;

        $dossier->analyste_id = $analyste->id;
        $dossier->exploitation_analyste_assigned_at = now();
        $dossier->exploitation_analyste_assigned_by_user_id = auth()->id();
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Affectation de dossier — instruction analyste financier',
            title: 'Un dossier vous a été affecté',
            body: "Vous avez été affecté(e) sur un dossier d’instruction.\n\nMerci de consulter le dossier et de démarrer l’instruction. Une fois terminé, soumettez au responsable exploitation.",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('analyste.dossiers.show', $dossier->token),
            event: 'assign_exploitation_analyste_financier'
        );
        $mailer->notifyUser($analyste, auth()->user(), $payload, $ctx);

        $msg = $wasAssigned
            ? 'Dossier réaffecté à '.$analyste->name.'.'
            : 'Dossier affecté à '.$analyste->name.'. L’analyste le verra dans son espace pour l’instruction.';

        return redirect()
            ->route('respexp.dossiers.show', $token)
            ->with('success', $msg);
    }

    /**
     * Grille de notation et synthèse du travail d’instruction (lecture seule), après soumission par l’analyste.
     */
    public function dossierInstructionShow(string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();
        if ($redirect = $this->redirectUnlessCanViewAnalystInstructionWork($dossier)) {
            return $redirect;
        }
        $dossier->loadMissing([
            'exploitationAnalysteTransmittedToExploitationBy',
            'instructionCaTransmittedToExploitationBy',
            'fichiersDossier.type',
            'fichiersDossier.uploadedBy',
        ]);
        $space = $this->resolveSpace();
        $data = app(DossierInstructionShowPresenter::class)->presentForDossier($dossier);

        return view('RoleSpace.dossiers.instruction_detail', array_merge(compact('space'), $data));
    }

    /**
     * Dossier d'instruction complet — export PDF (côté serveur).
     */
    public function dossierInstructionPdf(string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();
        if ($redirect = $this->redirectUnlessCanViewAnalystInstructionWork($dossier)) {
            return $redirect;
        }
        $dossier->loadMissing([
            'entreprise',
            'programme',
            'instructionProgrammes.programme',
            'fichiersDossier.type',
            'fichiersDossier.uploadedBy',
            'exploitationAnalysteTransmittedToExploitationBy',
            'instructionCaTransmittedToExploitationBy',
            'analyste',
        ]);

        $space = $this->resolveSpace();
        $data = app(DossierInstructionShowPresenter::class)->presentForDossier($dossier);

        $logoData = '';
        $logoPath = public_path('img/logo-bcpme.png');
        if (is_readable($logoPath)) {
            $logoData = base64_encode((string) file_get_contents($logoPath));
        }
        $generatedAt = now();

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('RoleSpace.dossiers.instruction_detail_pdf', array_merge($data, [
            'space' => $space,
            'logoData' => $logoData,
            'generatedAt' => $generatedAt,
        ]));
        $pdf->setCallbacks([
            [
                'event' => 'end_document',
                'f' => function (int $pageNumber, int $pageCount, Canvas $canvas, FontMetrics $fontMetrics): void {
                    $font = $fontMetrics->get_font('DejaVu Sans', 'normal');
                    $size = 8;
                    $color = [0.35, 0.35, 0.35];
                    $w = $canvas->get_width();
                    $h = $canvas->get_height();
                    $y = $h - 28;
                    $pageLabel = 'Page '.$pageNumber.' / '.$pageCount;
                    $tw = $canvas->get_text_width($pageLabel, $font, $size);
                    $canvas->text($w - $tw - 18, $y, $pageLabel, $font, $size, $color);
                    $canvas->text(18, $y, 'BC-PME — Angara', $font, $size, $color);
                },
            ],
        ]);

        $nameSlug = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) ($dossier->entreprise?->name ?: $dossier->token));
        $filename = 'dossier-instruction-complet-'.$nameSlug.'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Grille d’analyse critique rédigée par l’analyste (lecture seule).
     */
    public function dossierAnalyseCritiqueShow(string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();
        if ($redirect = $this->redirectUnlessCanViewAnalystInstructionWork($dossier)) {
            return $redirect;
        }
        $dossier->loadMissing(['fichiersDossier.type', 'fichiersDossier.uploadedBy']);
        $space = $this->resolveSpace();
        $item = $dossier;

        return view('RoleSpace.dossiers.analyse_critique', compact('space', 'item'));
    }

    private function redirectUnlessCanViewAnalystInstructionWork(Dossier $dossier): ?RedirectResponse
    {
        $space = $this->resolveSpace();
        $back = $space['route'].'.dossiers.show';

        if ($space['route'] === 'juridique' && ! $dossier->juridique_instruction_submitted_at) {
            return redirect()
                ->route($back, $dossier->token)
                ->with('error', 'Ce dossier n’est pas disponible dans l’espace juridique (transmission au pôle juridique non enregistrée).');
        }

        if ($space['route'] === 'analyste-juridique') {
            if (! $dossier->juridique_instruction_submitted_at) {
                return redirect()
                    ->route($back, $dossier->token)
                    ->with('error', 'Ce dossier n’est pas disponible.');
            }
            if ((int) $dossier->juridique_analyste_user_id !== (int) auth()->id()) {
                return redirect()
                    ->route('analyste-juridique.dossiers.index')
                    ->with('error', 'Ce dossier ne vous est pas affecté.');
            }
        }

        if ($space['route'] === 'analyste-credit') {
            if (! $dossier->juridique_submitted_to_engagements_at) {
                return redirect()
                    ->route($back, $dossier->token)
                    ->with('error', 'Ce dossier n’est pas encore au pôle engagements.');
            }
            if ((int) $dossier->reng_analyste_credit_user_id !== (int) auth()->id()) {
                return redirect()
                    ->route('analyste-credit.dossiers.index')
                    ->with('error', 'Ce dossier ne vous est pas affecté.');
            }
        }

        if ($space['route'] === 'reng') {
            if (! $dossier->juridique_submitted_to_engagements_at) {
                return redirect()
                    ->route($back, $dossier->token)
                    ->with('error', 'Ce dossier n’est pas encore au pôle engagements.');
            }
        }

        if ($space['route'] === 'rerx') {
            if (! $dossier->reng_submitted_to_risques_at) {
                return redirect()
                    ->route($back, $dossier->token)
                    ->with('error', 'Ce dossier n’a pas encore été transmis au pôle risques.');
            }
        }

        if ($space['route'] === 'analyste-risques') {
            if (! $dossier->reng_submitted_to_risques_at) {
                return redirect()
                    ->route($back, $dossier->token)
                    ->with('error', 'Ce dossier n’est pas encore au pôle risques.');
            }
            if ((int) $dossier->rerx_analyste_risques_user_id !== (int) auth()->id()) {
                return redirect()
                    ->route('analyste-risques.dossiers.index')
                    ->with('error', 'Ce dossier ne vous est pas affecté.');
            }
        }

        if (in_array($space['route'], ['dg', 'dga'], true)) {
            if (! $dossier->isInstructionValidatedByAgence() || $dossier->isInstructionRejectedByAgence()) {
                return redirect()
                    ->route($back, $dossier->token)
                    ->with('error', 'Ce dossier n’est pas accessible : validation du chef d’agence sur le dossier d’instruction requise.');
            }
        }

        if (! $dossier->isInstructionVisibleToResponsableExploitation()) {
            return redirect()
                ->route($back, $dossier->token)
                ->with('error', 'Le dossier doit d’abord être transmis au responsable exploitation (par l’analyste financier ou par le chef d’agence) avant consultation de la grille et du travail d’instruction.');
        }

        if (! $dossier->analyste_id && ! $dossier->isInstructionCaTransmittedToExploitation()) {
            return redirect()
                ->route($back, $dossier->token)
                ->with('error', 'Aucun analyste n’est affecté à ce dossier : le contenu d’instruction n’est pas disponible.');
        }

        return null;
    }

    /**
     * Dossier d’analyse critique : analyse critique de l’analyste financier (rubriques alignées sur la fiche dossier).
     */
    public function dossierAnalyseCritiqueSyntheseShow(string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();
        if ($redirect = $this->redirectUnlessCanViewAnalystInstructionWork($dossier)) {
            return $redirect;
        }
        $space = $this->resolveSpace();
        $item = $dossier;
        $doc = app(InstructionAnalyseCritiqueDossierDocumentService::class)->build($dossier);

        return view('RoleSpace.dossiers.dossier_analyse_critique', compact('space', 'item', 'doc'));
    }

    public function dossierAnalyseCritiqueSynthesePdf(string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();
        if ($redirect = $this->redirectUnlessCanViewAnalystInstructionWork($dossier)) {
            return $redirect;
        }
        $doc = app(InstructionAnalyseCritiqueDossierDocumentService::class)->build($dossier);

        $logoData = '';
        $logoPath = public_path('img/logo-bcpme.png');
        if (is_readable($logoPath)) {
            $logoData = base64_encode((string) file_get_contents($logoPath));
        }

        $generatedAt = now();

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('RoleSpace.dossiers.dossier_analyse_critique_pdf', [
            'item' => $dossier,
            'doc' => $doc,
            'logoData' => $logoData,
            'generatedAt' => $generatedAt,
        ]);
        $pdf->setCallbacks([
            [
                'event' => 'end_document',
                'f' => function (int $pageNumber, int $pageCount, Canvas $canvas, FontMetrics $fontMetrics): void {
                    $font = $fontMetrics->get_font('DejaVu Sans', 'normal');
                    $size = 8;
                    $color = [0.35, 0.35, 0.35];
                    $w = $canvas->get_width();
                    $h = $canvas->get_height();
                    $y = $h - 28;
                    $pageLabel = 'Page '.$pageNumber.' / '.$pageCount;
                    $tw = $canvas->get_text_width($pageLabel, $font, $size);
                    $canvas->text($w - $tw - 18, $y, $pageLabel, $font, $size, $color);
                    $canvas->text(18, $y, 'BC-PME — Angara', $font, $size, $color);
                },
            ],
        ]);
        $filename = 'dossier-analyse-critique-'.preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $dossier->token).'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Rejet par le REXP de la soumission de l'analyste financier (réouverture de l'étape AF).
     *
     * Effets :
     * - Trace l'horodatage, l'auteur et le motif obligatoire du rejet.
     * - L'avis et la grille de l'analyste financier deviennent à nouveau modifiables.
     * - L'avis crédit éventuellement saisi par le REXP est conservé mais devra être confirmé / mis à jour.
     */
    public function rejectExploitationAnalyste(Request $request, string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }
        if (! $dossier->isInstructionTransmittedToExploitationByAnalysteFinancier()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['rejet_analyste' => 'Aucune soumission de l’analyste financier en attente de décision.']);
        }
        if ($dossier->isSubmittedToJuridique()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['rejet_analyste' => 'Le dossier a été transmis au pôle juridique : la soumission de l’analyste ne peut plus être rejetée.']);
        }

        $validated = $request->validate(
            ['rejet_motif' => 'required|string|max:5000'],
            ['rejet_motif.required' => 'Le motif du rejet est obligatoire.']
        );

        if (strlen(trim(strip_tags((string) $validated['rejet_motif']))) === 0) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['rejet_motif' => 'Le motif du rejet est obligatoire.'])
                ->withInput();
        }

        $dossier->exploitation_analyste_rejected_at = now();
        $dossier->exploitation_analyste_rejected_by_user_id = auth()->id();
        $dossier->exploitation_analyste_reject_motif = $validated['rejet_motif'];
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Soumission rejetée — analyste financier',
            title: 'Votre soumission a été rejetée par le responsable exploitation',
            body: "Le responsable exploitation a rejeté votre soumission. Vous pouvez modifier votre travail et le retransmettre.\n\nMotif : ".$validated['rejet_motif'],
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('analyste.dossiers.show', $dossier->token),
            event: 'reject_exploitation_analyste'
        );
        if ($dossier->analyste_id) {
            $analyste = User::query()->whereKey((int) $dossier->analyste_id)->first();
            if ($analyste) {
                $mailer->notifyUser($analyste, auth()->user(), $payload, $ctx);
            }
        }

        return redirect()
            ->route('respexp.dossiers.show', $token)
            ->with('success', 'Soumission de l’analyste financier rejetée. Il peut désormais corriger et retransmettre.');
    }

    /**
     * Rejet par le RJU de la soumission de l'analyste juridique (réouverture de l'étape AJ).
     */
    public function rejectJuridiqueAnalyste(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_instruction_submitted_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }
        if (! $dossier->isJuridiqueAnalysteAvisSubmittedToReju()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['rejet_analyste' => 'Aucune soumission de l’analyste juridique en attente de décision.']);
        }
        if ($dossier->isSubmittedToEngagementsFromJuridique()) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['rejet_analyste' => 'Le dossier a été transmis au pôle engagements : la soumission de l’analyste ne peut plus être rejetée.']);
        }

        $validated = $request->validate(
            ['rejet_motif' => 'required|string|max:5000'],
            ['rejet_motif.required' => 'Le motif du rejet est obligatoire.']
        );

        if (strlen(trim(strip_tags((string) $validated['rejet_motif']))) === 0) {
            return redirect()
                ->route('juridique.dossiers.show', $token)
                ->withErrors(['rejet_motif' => 'Le motif du rejet est obligatoire.'])
                ->withInput();
        }

        $dossier->juridique_analyste_rejected_at = now();
        $dossier->juridique_analyste_rejected_by_user_id = auth()->id();
        $dossier->juridique_analyste_reject_motif = $validated['rejet_motif'];
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Avis rejeté — analyste juridique',
            title: 'Votre avis a été rejeté par le responsable juridique',
            body: "Le responsable juridique a rejeté votre avis. Vous pouvez le modifier et le retransmettre.\n\nMotif : ".$validated['rejet_motif'],
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('analyste-juridique.dossiers.show', $dossier->token),
            event: 'reject_juridique_analyste'
        );
        if ($dossier->juridique_analyste_user_id) {
            $analyste = User::query()->whereKey((int) $dossier->juridique_analyste_user_id)->first();
            if ($analyste) {
                $mailer->notifyUser($analyste, auth()->user(), $payload, $ctx);
            }
        }

        return redirect()
            ->route('juridique.dossiers.show', $token)
            ->with('success', 'Avis de l’analyste juridique rejeté. Il peut désormais le corriger et le retransmettre.');
    }

    /**
     * Rejet par le RENG de la soumission de l'analyste crédit (réouverture de l'étape AC).
     */
    public function rejectRengAnalysteCredit(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }
        if (! $dossier->isRengAnalysteCreditSubmittedToReng()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['rejet_analyste' => 'Aucune soumission de l’analyste crédit en attente de décision.']);
        }
        if ($dossier->isSubmittedToRisquesFromReng()) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['rejet_analyste' => 'Le dossier a été transmis au pôle risques : la soumission de l’analyste ne peut plus être rejetée.']);
        }

        $validated = $request->validate(
            ['rejet_motif' => 'required|string|max:5000'],
            ['rejet_motif.required' => 'Le motif du rejet est obligatoire.']
        );

        if (strlen(trim(strip_tags((string) $validated['rejet_motif']))) === 0) {
            return redirect()
                ->route('reng.dossiers.show', $token)
                ->withErrors(['rejet_motif' => 'Le motif du rejet est obligatoire.'])
                ->withInput();
        }

        $dossier->reng_analyste_credit_rejected_at = now();
        $dossier->reng_analyste_credit_rejected_by_user_id = auth()->id();
        $dossier->reng_analyste_credit_reject_motif = $validated['rejet_motif'];
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Soumission rejetée — analyste crédit',
            title: 'Votre soumission a été rejetée par le responsable engagements',
            body: "Le responsable engagements a rejeté votre soumission. Vous pouvez la modifier et la retransmettre.\n\nMotif : ".$validated['rejet_motif'],
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('analyste-credit.dossiers.show', $dossier->token),
            event: 'reject_reng_analyste_credit'
        );
        if ($dossier->reng_analyste_credit_user_id) {
            $analyste = User::query()->whereKey((int) $dossier->reng_analyste_credit_user_id)->first();
            if ($analyste) {
                $mailer->notifyUser($analyste, auth()->user(), $payload, $ctx);
            }
        }

        return redirect()
            ->route('reng.dossiers.show', $token)
            ->with('success', 'Soumission de l’analyste crédit rejetée. Il peut désormais corriger et retransmettre.');
    }

    /**
     * Rejet par le RISQ de la soumission de l'analyste risques (réouverture de l'étape AR).
     */
    public function rejectRerxAnalysteRisques(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('reng_submitted_to_risques_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }
        if (! $dossier->isRerxAnalysteRisquesSubmittedToRerx()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['rejet_analyste' => 'Aucune soumission de l’analyste risques en attente de décision.']);
        }
        if ($dossier->isSubmittedToDirectionFromRerx()) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['rejet_analyste' => 'Le dossier a été transmis à la direction : la soumission de l’analyste ne peut plus être rejetée.']);
        }

        $validated = $request->validate(
            ['rejet_motif' => 'required|string|max:5000'],
            ['rejet_motif.required' => 'Le motif du rejet est obligatoire.']
        );

        if (strlen(trim(strip_tags((string) $validated['rejet_motif']))) === 0) {
            return redirect()
                ->route('rerx.dossiers.show', $token)
                ->withErrors(['rejet_motif' => 'Le motif du rejet est obligatoire.'])
                ->withInput();
        }

        $dossier->rerx_analyste_risques_rejected_at = now();
        $dossier->rerx_analyste_risques_rejected_by_user_id = auth()->id();
        $dossier->rerx_analyste_risques_reject_motif = $validated['rejet_motif'];
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Soumission rejetée — analyste risques',
            title: 'Votre soumission a été rejetée par le responsable risques',
            body: "Le responsable risques a rejeté votre soumission. Vous pouvez la modifier et la retransmettre.\n\nMotif : ".$validated['rejet_motif'],
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('analyste-risques.dossiers.show', $dossier->token),
            event: 'reject_rerx_analyste_risques'
        );
        if ($dossier->rerx_analyste_risques_user_id) {
            $analyste = User::query()->whereKey((int) $dossier->rerx_analyste_risques_user_id)->first();
            if ($analyste) {
                $mailer->notifyUser($analyste, auth()->user(), $payload, $ctx);
            }
        }

        return redirect()
            ->route('rerx.dossiers.show', $token)
            ->with('success', 'Soumission de l’analyste risques rejetée. Il peut désormais corriger et retransmettre.');
    }

    /**
     * Rejet inter-pôle : RJU renvoie le dossier vers le pôle exploitation (réouverture REXP).
     *
     * Effets :
     * - Verrouillage RJU : le rejet n'est possible qu'avant transmission au pôle engagements.
     * - L'avis crédit du REXP, sa décision sur les engagements et sa transmission au juridique sont à nouveau modifiables.
     */
    public function rejectJuridiqueToExploitation(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_instruction_submitted_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()->route('juridique.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }
        if ($dossier->isSubmittedToEngagementsFromJuridique()) {
            return redirect()->route('juridique.dossiers.show', $token)
                ->withErrors(['rejet_inter_pole' => 'Le dossier est déjà transmis au pôle engagements : il ne peut plus être renvoyé au pôle exploitation.']);
        }
        if ($dossier->isJuridiqueRejectedToExploitation()) {
            return redirect()->route('juridique.dossiers.show', $token)
                ->withErrors(['rejet_inter_pole' => 'Le dossier a déjà été renvoyé au pôle exploitation : en attente de retransmission par le REXP.']);
        }

        $validated = $request->validate(
            ['rejet_motif' => 'required|string|max:5000'],
            ['rejet_motif.required' => 'Le motif du rejet inter-pôle est obligatoire.']
        );
        if (strlen(trim(strip_tags((string) $validated['rejet_motif']))) === 0) {
            return redirect()->route('juridique.dossiers.show', $token)
                ->withErrors(['rejet_motif' => 'Le motif du rejet inter-pôle est obligatoire.'])->withInput();
        }

        $dossier->juridique_rejected_to_exploitation_at = now();
        $dossier->juridique_rejected_to_exploitation_by_user_id = auth()->id();
        $dossier->juridique_rejected_to_exploitation_motif = $validated['rejet_motif'];
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Rejet inter-pôle — dossier renvoyé au pôle exploitation',
            title: 'Le responsable juridique vous renvoie le dossier',
            body: "Le responsable juridique a rejeté le dossier et vous le renvoie pour révision. Vous pouvez modifier votre avis et votre décision sur les engagements puis retransmettre.\n\nMotif : ".$validated['rejet_motif'],
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('respexp.dossiers.show', $dossier->token),
            event: 'reject_juridique_to_exploitation'
        );
        $recipients = $mailer->recipientsByRole((int) config('angara.role_responsable_exploitation', 6));
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        return redirect()->route('juridique.dossiers.show', $token)
            ->with('success', 'Dossier renvoyé au pôle exploitation. Le responsable exploitation pourra modifier et retransmettre.');
    }

    /**
     * Rejet inter-pôle : RENG renvoie le dossier vers le pôle juridique (réouverture RJU).
     */
    public function rejectEngagementsToJuridique(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()->route('reng.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }
        if ($dossier->isSubmittedToRisquesFromReng()) {
            return redirect()->route('reng.dossiers.show', $token)
                ->withErrors(['rejet_inter_pole' => 'Le dossier est déjà transmis au pôle risques : il ne peut plus être renvoyé au pôle juridique.']);
        }
        if ($dossier->isEngagementsRejectedToJuridique()) {
            return redirect()->route('reng.dossiers.show', $token)
                ->withErrors(['rejet_inter_pole' => 'Le dossier a déjà été renvoyé au pôle juridique : en attente de retransmission par le RJU.']);
        }

        $validated = $request->validate(
            ['rejet_motif' => 'required|string|max:5000'],
            ['rejet_motif.required' => 'Le motif du rejet inter-pôle est obligatoire.']
        );
        if (strlen(trim(strip_tags((string) $validated['rejet_motif']))) === 0) {
            return redirect()->route('reng.dossiers.show', $token)
                ->withErrors(['rejet_motif' => 'Le motif du rejet inter-pôle est obligatoire.'])->withInput();
        }

        $dossier->engagements_rejected_to_juridique_at = now();
        $dossier->engagements_rejected_to_juridique_by_user_id = auth()->id();
        $dossier->engagements_rejected_to_juridique_motif = $validated['rejet_motif'];
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Rejet inter-pôle — dossier renvoyé au pôle juridique',
            title: 'Le responsable engagements vous renvoie le dossier',
            body: "Le responsable engagements a rejeté le dossier et vous le renvoie pour révision. Vous pouvez modifier votre avis puis retransmettre.\n\nMotif : ".$validated['rejet_motif'],
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('juridique.dossiers.show', $dossier->token),
            event: 'reject_engagements_to_juridique'
        );
        $recipients = $mailer->recipientsByRole((int) config('angara.role_responsable_juridique', 8));
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        return redirect()->route('reng.dossiers.show', $token)
            ->with('success', 'Dossier renvoyé au pôle juridique. Le responsable juridique pourra modifier et retransmettre.');
    }

    /**
     * Rejet inter-pôle : RISQ renvoie le dossier vers le pôle engagements (réouverture RENG).
     */
    public function rejectRisquesToEngagements(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('reng_submitted_to_risques_at')
            ->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            return redirect()->route('rerx.dossiers.show', $token)
                ->with('info', 'Ce dossier d’instruction est clos : aucune modification n’est possible.');
        }
        if ($dossier->isSubmittedToDirectionFromRerx()) {
            return redirect()->route('rerx.dossiers.show', $token)
                ->withErrors(['rejet_inter_pole' => 'Le dossier est déjà transmis à la direction : il ne peut plus être renvoyé au pôle engagements.']);
        }
        if ($dossier->isRisquesRejectedToEngagements()) {
            return redirect()->route('rerx.dossiers.show', $token)
                ->withErrors(['rejet_inter_pole' => 'Le dossier a déjà été renvoyé au pôle engagements : en attente de retransmission par le RENG.']);
        }

        $validated = $request->validate(
            ['rejet_motif' => 'required|string|max:5000'],
            ['rejet_motif.required' => 'Le motif du rejet inter-pôle est obligatoire.']
        );
        if (strlen(trim(strip_tags((string) $validated['rejet_motif']))) === 0) {
            return redirect()->route('rerx.dossiers.show', $token)
                ->withErrors(['rejet_motif' => 'Le motif du rejet inter-pôle est obligatoire.'])->withInput();
        }

        $dossier->risques_rejected_to_engagements_at = now();
        $dossier->risques_rejected_to_engagements_by_user_id = auth()->id();
        $dossier->risques_rejected_to_engagements_motif = $validated['rejet_motif'];
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Rejet inter-pôle — dossier renvoyé au pôle engagements',
            title: 'Le responsable risques vous renvoie le dossier',
            body: "Le responsable risques a rejeté le dossier et vous le renvoie pour révision. Vous pouvez modifier votre avis puis retransmettre.\n\nMotif : ".$validated['rejet_motif'],
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('reng.dossiers.show', $dossier->token),
            event: 'reject_risques_to_engagements'
        );
        $recipients = $mailer->recipientsByRole((int) config('angara.role_responsable_engagements', 10));
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        return redirect()->route('rerx.dossiers.show', $token)
            ->with('success', 'Dossier renvoyé au pôle engagements. Le responsable engagements pourra modifier et retransmettre.');
    }
}

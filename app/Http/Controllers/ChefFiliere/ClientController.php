<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Concerns\AppliesEntrepriseListIndexFilters;
use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\DossierEntreeRelationProgramme;
use App\Models\DossierInstructionProgramme;
use App\Models\Entreprise;
use App\Models\EntrepriseAppui;
use App\Models\EntrepriseProduit;
use App\Models\Instruction\Critere as InstructionCritere;
use App\Models\Produit;
use App\Models\Programme;
use App\Models\QuestionSousCritere;
use App\Models\Service;
use App\Models\User;
use App\Services\AnalyseCritiqueService;
use App\Services\ClientEntrepriseTableExportService;
use App\Services\WorkflowEmailNotificationService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    use AppliesEntrepriseListIndexFilters;
    use AuthorizesAgenceEntreprise;

    /**
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\Entreprise>
     */
    private function filteredChefFiliereClientsQuery(Request $request)
    {
        $structurationStatus = DossierEntreeRelation::normalizeClientStructurationFilter($request->query('client_structuration_status'));
        $filters = $this->parsePromuClientAndAgenceGestionnaireFilters($request, true);
        $agenceAuthId = (int) auth()->user()->agence_id;

        $query = Entreprise::query()
            ->where('agence_id', $agenceAuthId)
            ->whereNotNull('promu_client_at')
            ->with(['agence', 'dossierEntreeRelation', 'gestionnaire'])
            ->when($structurationStatus, fn ($q) => $q->whereClientStructurationStatus($structurationStatus));

        $this->applyPromuAgenceGestionnaireFiltersToQuery(
            $query,
            $filters,
            true,
            static fn (?int $id) => ($id && (int) $id === $agenceAuthId) ? $id : null,
        );

        return $query;
    }

    public function index(Request $request)
    {
        $structurationStatus = DossierEntreeRelation::normalizeClientStructurationFilter($request->query('client_structuration_status'));
        $items = $this->filteredChefFiliereClientsQuery($request)->orderByDesc('promu_client_at')->get();

        $agenceAuthId = (int) auth()->user()->agence_id;
        $gestionnaireIds = Entreprise::query()
            ->where('agence_id', $agenceAuthId)
            ->whereNotNull('promu_client_at')
            ->whereNotNull('gestionnaire_id')
            ->distinct()
            ->pluck('gestionnaire_id');
        $gestionnaires = User::query()
            ->whereIn('id', $gestionnaireIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('ChefFiliere.clients.index', compact('items', 'structurationStatus', 'gestionnaires'));
    }

    public function exportClients(Request $request)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $items = $this->filteredChefFiliereClientsQuery($request)->orderByDesc('promu_client_at')->get();
        $rows = ClientEntrepriseTableExportService::rowsChefFiliereClients($items);

        return ClientEntrepriseTableExportService::download(
            $rows,
            ClientEntrepriseTableExportService::headersChefFiliere(),
            $format,
            'chef-filiere-clients',
            'Chef de filière — clients',
        );
    }

    public function show(string $token)
    {
        $item = $this->entrepriseForAgence($token);
        $item->load([
            'dossierEntreeRelation.qualificationUser',
            'dossierEntreeRelation.programmesSubmittedBy',
            'dossierEntreeRelation.instructionValidatedBy',
            'dossierEntreeRelation.qualificationValidatedByAgenceUser',
            'dossierEntreeRelation.instructionBundleRejectedBy',
            'dossierEntreeRelation.programmeSelections.programme',
            'dossierEntreeRelation.programmeSelections.instructionDossier',
            'promuClientUser',
            'prospectRejectedUser',
            'produit',
            'filiere',
            'branche',
            'produits.filiere',
            'produits.branche',
            'appuis.type',
            'dossiers.programme',
            'dossiers.instructionProgrammes.programme',
            'dossiers.chefFiliereSubmittedToAgenceBy',
            'dossiers.instructionAgenceValidatedBy',
            'dossiers.instructionAgenceRejectedBy',
            'arrondissement',
            'departement',
            'region',
            'forme',
            'village',
            'quartier',
            'agence.representation',
            'tiers.person',
            'tiers.company.produit',
            'juridiqueAvisUser',
            'conformiteAvisUser',
        ]);

        $mr = $this->buildQuestionnaireResults($item);
        $checklist = $item->piecesExigiblesChecklist();

        $eer = $item->dossierEntreeRelation;
        $bundleEditDossier = null;
        $bundleAvailableProgrammes = collect();
        if ($eer && $eer->qualification_validated_by_agence_at) {
            // En cas de rejet par le chef d'agence, on autorise le chef de filière à corriger
            // le dernier dossier multi-programmes rejeté (mise à jour + resoumission).
            $bundleEditDossier = $item->dossiers
                ->filter(fn ($d) => $d instanceof Dossier && $d->instructionProgrammes?->isNotEmpty())
                ->first(fn ($d) => $d->isInstructionRejectedByAgence());

            $excludeId = $bundleEditDossier?->id ? (int) $bundleEditDossier->id : null;
            $taken = $this->assignedProgrammeIdsForEntreprise($item, $excludeId);
            $bundleAvailableProgrammes = Programme::query()
                ->orderBy('name')
                ->when(count($taken) > 0, fn ($q) => $q->whereNotIn('id', $taken))
                ->get(['id', 'name']);
        }

        Session::put('chef_filiere_dossier_consulte_'.$token, true);

        return view('ChefFiliere.clients.show', compact('item', 'mr', 'checklist', 'bundleAvailableProgrammes', 'bundleEditDossier'));
    }

    public function editBesoinsProduits(string $token)
    {
        $item = $this->entrepriseForAgence($token);
        $item->load(['produit', 'produits', 'appuis.type']);

        $produitsListe = Produit::query()
            ->with(['filiere', 'branche'])
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'filiere_id', 'branche_id']);

        $appuisFinanciers = Service::query()->with('type')->where('financier', 1)->orderBy('name')->get();
        $appuisNonFinanciers = Service::query()->with('type')->where('financier', 0)->orderBy('name')->get();

        $selectedFinanciers = $item->appuis->where('financier', 1)->pluck('id')->all();
        $selectedNonFinanciers = $item->appuis->where('financier', 0)->pluck('id')->all();
        $selectedSecondaires = $item->produits->pluck('id')->all();

        return view('ChefFiliere.clients.besoins_produits_edit', compact(
            'item',
            'produitsListe',
            'appuisFinanciers',
            'appuisNonFinanciers',
            'selectedFinanciers',
            'selectedNonFinanciers',
            'selectedSecondaires',
        ));
    }

    public function updateBesoinsProduits(Request $request, string $token)
    {
        $item = $this->entrepriseForAgence($token);

        $request->merge([
            'produit_id' => $request->filled('produit_id') ? (int) $request->input('produit_id') : null,
        ]);

        $validated = $request->validate([
            'produit_id' => 'nullable|integer',
            'produit_year_start' => 'nullable|integer|min:0|max:200',
            'autres' => 'nullable|array',
            'autres.*' => 'integer',
            'appuisf' => 'nullable|array',
            'appuisf.*' => 'integer',
            'appuisnf' => 'nullable|array',
            'appuisnf.*' => 'integer',
        ]);

        $mainId = isset($validated['produit_id']) ? (int) $validated['produit_id'] : null;
        $central = 'central_app_mysql';

        if ($mainId && ! Produit::on($central)->whereKey($mainId)->exists()) {
            return redirect()
                ->back()
                ->withErrors(['produit_id' => 'Produit principal invalide.'])
                ->withInput();
        }

        $autres = $this->normalizeSelectionInput($request->input('autres', []));
        foreach ($autres as $pid) {
            if (! Produit::on($central)->whereKey($pid)->exists()) {
                return redirect()
                    ->back()
                    ->withErrors(['autres' => 'Un produit secondaire est invalide.'])
                    ->withInput();
            }
        }
        if ($mainId) {
            $autres = array_values(array_unique(array_filter($autres, fn (int $id) => $id !== $mainId)));
        }

        $afs = $this->normalizeSelectionInput($request->input('appuisf', []));
        foreach ($afs as $sid) {
            if (! Service::on($central)->whereKey($sid)->where('financier', 1)->exists()) {
                return redirect()
                    ->back()
                    ->withErrors(['appuisf' => 'Un besoin (appui financier) est invalide.'])
                    ->withInput();
            }
        }
        $anfs = $this->normalizeSelectionInput($request->input('appuisnf', []));
        foreach ($anfs as $sid) {
            if (! Service::on($central)->whereKey($sid)->where('financier', 0)->exists()) {
                return redirect()
                    ->back()
                    ->withErrors(['appuisnf' => 'Un besoin (appui non financier) est invalide.'])
                    ->withInput();
            }
        }

        DB::connection($central)->transaction(function () use ($item, $validated, $mainId, $autres, $request, $central) {
            $entreprise = Entreprise::on($central)->whereKey($item->id)->lockForUpdate()->firstOrFail();

            $entreprise->produit_id = $mainId ?: null;
            $entreprise->produit_year_start = $validated['produit_year_start'] ?? null;

            if ($mainId) {
                $produit = Produit::on($central)->find($mainId);
                if ($produit && Schema::connection($central)->hasColumn('entreprises', 'filiere_id')) {
                    $entreprise->filiere_id = $produit->filiere_id;
                }
                if ($produit && Schema::connection($central)->hasColumn('entreprises', 'branche_id')) {
                    $entreprise->branche_id = $produit->branche_id;
                }
            }

            $entreprise->save();

            $this->syncAppuisEtProduitsSecondaires($entreprise, $request, $autres, $central);
        });

        return redirect()
            ->route('chef-filiere.clients.show', $token)
            ->with('success', 'Besoins, produit principal et produits secondaires ont été mis à jour.');
    }

    /**
     * Création / mise à jour d’un dossier d’instruction multi-programmes et soumission au chef d’agence.
     */
    public function submitInstructionBundle(Request $request, string $token, AnalyseCritiqueService $analyseCritiqueService)
    {
        $item = $this->entrepriseForAgence($token);
        $item->load(['dossiers.instructionProgrammes']);

        $eer = DossierEntreeRelation::query()->where('entreprise_id', $item->id)->first();
        if (! $eer || $eer->qualification_validated_by_agence_at === null) {
            return redirect()
                ->route('chef-filiere.clients.show', $token)
                ->with('info', 'La structuration doit être validée par le chef d\'agence avant la composition du dossier d\'instruction.');
        }

        $validated = $request->validate([
            'dossier_id' => 'nullable|integer|min:1',
            'lignes' => 'required|array|min:1',
            'lignes.*.programme_id' => 'required|integer|exists:programmes,id',
            'lignes.*.budget_appui_financier' => 'nullable|numeric|min:0',
            'lignes.*.budget_appui_non_financier' => 'nullable|numeric|min:0',
            'engagements_sollicites_total' => 'required|numeric|min:0',
            'engagements_en_cours_total' => 'required|numeric|min:0',
        ]);

        $lignes = collect($validated['lignes'])
            ->map(function (array $row) {
                return [
                    'programme_id' => (int) $row['programme_id'],
                    'budget_appui_financier' => isset($row['budget_appui_financier']) ? (float) $row['budget_appui_financier'] : 0.0,
                    'budget_appui_non_financier' => isset($row['budget_appui_non_financier']) ? (float) $row['budget_appui_non_financier'] : 0.0,
                ];
            })
            ->unique('programme_id')
            ->values();

        if ($lignes->count() !== count($validated['lignes'])) {
            return redirect()
                ->route('chef-filiere.clients.show', $token)
                ->with('info', 'Chaque programme ne peut figurer qu\'une seule fois.');
        }

        foreach ($lignes as $l) {
            $existingSel = DossierEntreeRelationProgramme::query()
                ->where('dossier_entree_relation_id', $eer->id)
                ->where('programme_id', $l['programme_id'])
                ->first();
            if ($existingSel && $existingSel->statut === DossierEntreeRelationProgramme::STATUT_VALIDE) {
                return redirect()
                    ->route('chef-filiere.clients.show', $token)
                    ->with('info', 'Un programme sélectionné est déjà validé sur un autre dossier d\'instruction.');
            }
        }

        $central = 'central_app_mysql';

        $editDossierId = isset($validated['dossier_id']) ? (int) $validated['dossier_id'] : null;
        $editDossier = null;
        if ($editDossierId) {
            $editDossier = Dossier::on($central)
                ->whereKey($editDossierId)
                ->where('entreprise_id', $item->id)
                ->with('instructionProgrammes')
                ->first();
            if (! $editDossier || ! $editDossier->isInstructionRejectedByAgence()) {
                return redirect()
                    ->route('chef-filiere.clients.show', $token)
                    ->with('info', 'Ce dossier ne peut pas être corrigé (état invalide).');
            }
            if ($editDossier->isInstructionClosed() || $editDossier->isInstructionCaTransmittedToExploitation()) {
                return redirect()
                    ->route('chef-filiere.clients.show', $token)
                    ->with('info', 'Ce dossier est déjà dans un état avancé et ne peut plus être corrigé.');
            }
        }

        $taken = $this->assignedProgrammeIdsForEntreprise($item, $editDossier?->id ? (int) $editDossier->id : null);
        $conflict = $lignes->pluck('programme_id')->first(fn (int $pid) => in_array($pid, $taken, true));
        if ($conflict !== null) {
            return redirect()
                ->route('chef-filiere.clients.show', $token)
                ->with('info', 'Un ou plusieurs programmes sont déjà associés à un autre dossier d\'instruction pour ce client.');
        }

        foreach ($lignes as $l) {
            if ($l['budget_appui_financier'] <= 0 && $l['budget_appui_non_financier'] <= 0) {
                return redirect()
                    ->route('chef-filiere.clients.show', $token)
                    ->with('info', 'Renseignez au moins un budget (appui financier ou non financier) pour chaque programme.');
            }
        }

        $dossier = null;
        $abortInfo = null;

        $submittedByUserId = (int) auth()->id();

        $maxAttempts = 3;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                DB::connection($central)->transaction(function () use ($item, $lignes, &$dossier, &$abortInfo, $central, $submittedByUserId, $validated, $editDossier) {
                    $eerLocked = DossierEntreeRelation::query()
                        ->where('entreprise_id', $item->id)
                        ->lockForUpdate()
                        ->first();

                    if (! $eerLocked || $eerLocked->qualification_validated_by_agence_at === null) {
                        $abortInfo = 'La structuration doit être validée par le chef d\'agence.';

                        return;
                    }

                    $sorted = $lignes->sortBy('programme_id')->values();

                    if ($editDossier) {
                        $dLocked = Dossier::on($central)->whereKey($editDossier->id)->lockForUpdate()->firstOrFail();
                        if (! $dLocked->isInstructionRejectedByAgence()) {
                            $abortInfo = 'Ce dossier n’est plus en rejet (état modifié).';

                            return;
                        }

                        // Mise à jour du dossier existant (correction + resoumission).
                        $dLocked->engagements_sollicites_total = $validated['engagements_sollicites_total'];
                        $dLocked->engagements_en_cours_total = $validated['engagements_en_cours_total'];
                        $dLocked->instruction_agence_rejected_at = null;
                        $dLocked->instruction_agence_rejected_by_user_id = null;
                        $dLocked->instruction_agence_reject_motif = null;
                        $dLocked->instruction_agence_validated_at = null;
                        $dLocked->instruction_agence_validated_by_user_id = null;
                        $dLocked->instruction_agence_closing_note = null;
                        $dLocked->save();

                        DossierInstructionProgramme::query()->where('dossier_id', $dLocked->id)->delete();
                        $dossier = $dLocked;
                    } else {
                        $dossier = Dossier::on($central)->create([
                            'entreprise_id' => $item->id,
                            'programme_id' => null,
                            'token' => sha1('instruction-bundle-'.$item->id.'-'.microtime(true)),
                            'gestionnaire_id' => $item->gestionnaire_id ?: $item->user_id ?: auth()->id(),
                            'agence_id' => $item->agence_id,
                            'representation_id' => $item->representation_id,
                            'active' => 0,
                            'engagements_sollicites_total' => $validated['engagements_sollicites_total'],
                            'engagements_en_cours_total' => $validated['engagements_en_cours_total'],
                        ]);
                    }

                    foreach ($sorted as $idx => $l) {
                        $pid = (int) $l['programme_id'];
                        DossierInstructionProgramme::query()->create([
                            'dossier_id' => $dossier->id,
                            'programme_id' => $pid,
                            'budget_appui_financier' => $l['budget_appui_financier'],
                            'budget_appui_non_financier' => $l['budget_appui_non_financier'],
                            'sort_order' => $idx,
                        ]);

                        $bf = $l['budget_appui_financier'];
                        $bnf = $l['budget_appui_non_financier'];
                        $type = ($bf > 0 && $bnf > 0)
                            ? DossierEntreeRelationProgramme::TYPE_MIXTE
                            : ($bf > 0 ? DossierEntreeRelationProgramme::TYPE_FINANCIER : DossierEntreeRelationProgramme::TYPE_NON_FINANCIER);
                        $notes = sprintf(
                            'Budgets d\'appui proposés — financier : %s XAF ; non financier : %s XAF',
                            number_format($bf, 0, ',', ' '),
                            number_format($bnf, 0, ',', ' ')
                        );

                        DossierEntreeRelationProgramme::query()->updateOrCreate(
                            [
                                'dossier_entree_relation_id' => $eerLocked->id,
                                'programme_id' => $pid,
                            ],
                            [
                                'type_appui' => $type,
                                'notes' => $notes,
                                'statut' => DossierEntreeRelationProgramme::STATUT_SOUMIS,
                                'instruction_dossier_id' => $dossier->id,
                                'submitted_at' => now(),
                                'validated_at' => null,
                            ]
                        );
                    }

                    $pids = $sorted->pluck('programme_id')->all();
                    DossierEntreeRelationProgramme::query()
                        ->where('dossier_entree_relation_id', $eerLocked->id)
                        ->where('instruction_dossier_id', $dossier->id)
                        ->whereNotIn('programme_id', $pids)
                        ->delete();

                    $submittedAt = now();
                    $dossier->update([
                        'chef_filiere_submitted_to_agence_at' => $submittedAt,
                        'chef_filiere_submitted_to_agence_by_user_id' => $submittedByUserId,
                    ]);
                });

                break;
            } catch (QueryException $e) {
                if ($attempt < $maxAttempts && $this->isMysqlLockContention($e)) {
                    usleep(100000 * $attempt);

                    continue;
                }

                throw $e;
            }
        }

        if ($abortInfo !== null) {
            return redirect()
                ->route('chef-filiere.clients.show', $token)
                ->with('info', $abortInfo);
        }

        if ($dossier) {
            $dossier->loadMissing('instructionProgrammes.programme');
            $analyseCritiqueService->syncInstructionDossier(
                $dossier,
                'Dossier d\'instruction multi-programmes créé et soumis au chef d\'agence (chef de filière). Programmes : '.$dossier->programmesLabel().'.'
            );

            $mailer = app(WorkflowEmailNotificationService::class);
            $ctx = $mailer->contextForDossier($dossier);
            $payload = $mailer->buildPayload(
                subject: 'Transmission de dossier — chef d’agence',
                title: 'Un dossier d’instruction vous a été transmis',
                body: "Un dossier d’instruction multi-programmes vient d’être soumis par le chef de filière.\n\nMerci de consulter le dossier et de valider ou rejeter la transmission.",
                ctaLabel: 'Ouvrir la transmission',
                ctaUrl: route('ca.workflow.instruction-dossiers.show', $dossier->token),
                event: 'submit_instruction_bundle_to_ca'
            );
            $recipients = $mailer->recipientsByRole((int) config('angara.role_chef_agence', 15), $item->agence_id ? (int) $item->agence_id : null);
            $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);
        }

        return redirect()
            ->route('chef-filiere.clients.show', $token)
            ->with('success', 'Dossier d\'instruction enregistré et transmis au chef d\'agence pour validation.');
    }

    /**
     * @return list<int>
     */
    private function assignedProgrammeIdsForEntreprise(Entreprise $item, ?int $excludeDossierId): array
    {
        $ids = collect();
        foreach ($item->dossiers as $d) {
            if ($excludeDossierId !== null && (int) $d->id === (int) $excludeDossierId) {
                continue;
            }
            if ($d->instruction_agence_rejected_at !== null) {
                continue;
            }

            $hasDip = $d->relationLoaded('instructionProgrammes')
                ? $d->instructionProgrammes->isNotEmpty()
                : $d->instructionProgrammes()->exists();

            if ($hasDip) {
                if (! $d->locksInstructionProgrammesForComposition()) {
                    continue;
                }
                if ($d->relationLoaded('instructionProgrammes')) {
                    $ids = $ids->merge($d->instructionProgrammes->pluck('programme_id'));
                } else {
                    $ids = $ids->merge(
                        DossierInstructionProgramme::query()->where('dossier_id', $d->id)->pluck('programme_id')
                    );
                }

                continue;
            }

            if ($d->programme_id) {
                $ids->push((int) $d->programme_id);
            }
        }

        return $ids->unique()->filter()->values()->all();
    }

    /**
     * Verrou d’attente MySQL (1205) ou deadlock (1213) — nouvelle tentative utile sous charge concurrente.
     */
    private function isMysqlLockContention(QueryException $e): bool
    {
        $errno = (int) ($e->errorInfo[1] ?? 0);

        return $errno === 1205 || $errno === 1213;
    }

    private function buildQuestionnaireResults(Entreprise $item)
    {
        $reponses = $item->reponses()->with(['question', 'choice'])->get();

        return $reponses->groupBy('critere_id')->map(function ($items, $critereId) {
            $critere = InstructionCritere::find($critereId);

            return [
                'critere' => $critere,
                'items' => $items->groupBy('sous_critere_id')->map(function ($group, $sousCritereId) {
                    $sousCritere = QuestionSousCritere::find($sousCritereId);

                    return [
                        'sous_critere' => $sousCritere,
                        'items' => $group,
                    ];
                }),
            ];
        });
    }

    /**
     * @param  list<int>  $produitsSecondairesIds  déjà exclus du produit principal
     */
    private function syncAppuisEtProduitsSecondaires(Entreprise $entreprise, Request $request, array $produitsSecondairesIds, string $central = 'central_app_mysql'): void
    {
        $anfs = $this->normalizeSelectionInput($request->input('appuisnf', []));
        $afs = $this->normalizeSelectionInput($request->input('appuisf', []));

        EntrepriseAppui::on($central)->where('entreprise_id', $entreprise->id)->delete();
        EntrepriseProduit::on($central)->where('entreprise_id', $entreprise->id)->delete();

        foreach ($afs as $a) {
            EntrepriseAppui::on($central)->create([
                'entreprise_id' => $entreprise->id,
                'service_id' => $a,
            ]);
        }
        foreach ($anfs as $a) {
            EntrepriseAppui::on($central)->create([
                'entreprise_id' => $entreprise->id,
                'service_id' => $a,
            ]);
        }
        foreach ($produitsSecondairesIds as $a) {
            EntrepriseProduit::on($central)->create([
                'entreprise_id' => $entreprise->id,
                'produit_id' => $a,
            ]);
        }
    }

    /**
     * @param  array<string|int>|string|null  $raw
     * @return list<int>
     */
    private function normalizeSelectionInput(array|string|null $raw): array
    {
        if (is_string($raw)) {
            $raw = $raw === '' ? [] : explode(',', $raw);
        }

        if (! is_array($raw)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($value) {
            if ($value === null || $value === '') {
                return null;
            }

            return (int) $value;
        }, $raw), fn ($value) => $value !== null && $value > 0));
    }
}

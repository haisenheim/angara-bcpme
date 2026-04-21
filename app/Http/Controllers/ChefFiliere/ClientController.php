<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\DossierEntreeRelationProgramme;
use App\Models\Entreprise;
use App\Models\Instruction\Critere as InstructionCritere;
use App\Models\Programme;
use App\Models\QuestionSousCritere;
use App\Services\AnalyseCritiqueService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    use AuthorizesAgenceEntreprise;

    public function index()
    {
        $items = Entreprise::query()
            ->where('agence_id', auth()->user()->agence_id)
            ->whereNotNull('promu_client_at')
            ->with(['agence', 'dossierEntreeRelation'])
            ->orderByDesc('promu_client_at')
            ->get();

        return view('ChefFiliere.clients.index', compact('items'));
    }

    public function show(string $token)
    {
        $item = $this->entrepriseForAgence($token);
        $item->load([
            'dossierEntreeRelation.qualificationUser',
            'dossierEntreeRelation.programmesSubmittedBy',
            'dossierEntreeRelation.instructionValidatedBy',
            'dossierEntreeRelation.qualificationValidatedByAgenceUser',
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
        $enrollableProgrammes = collect();
        if ($eer && $eer->qualification_validated_by_agence_at) {
            $taken = $item->dossiers->pluck('programme_id')->filter()->all();
            $enrollableProgrammes = Programme::query()
                ->orderBy('name')
                ->when(count($taken) > 0, fn ($q) => $q->whereNotIn('id', $taken))
                ->get(['id', 'name']);
        }

        Session::put('chef_filiere_dossier_consulte_'.$token, true);

        return view('ChefFiliere.clients.show', compact('item', 'mr', 'checklist', 'enrollableProgrammes'));
    }

    public function enrollProgramme(Request $request, string $token, AnalyseCritiqueService $analyseCritiqueService)
    {
        $item = $this->entrepriseForAgence($token);

        $eer = DossierEntreeRelation::query()->where('entreprise_id', $item->id)->first();
        if (! $eer || $eer->qualification_validated_by_agence_at === null) {
            return redirect()
                ->route('chef-filiere.clients.show', $token)
                ->with('info', 'La qualification doit être validée par le chef d\'agence avant toute inscription à un programme.');
        }

        $validated = $request->validate([
            'programme_id' => 'required|integer|exists:programmes,id',
            'type_appui' => 'nullable|in:financier,non_financier,mixte',
            'notes' => 'nullable|string|max:5000',
        ]);

        $pid = (int) $validated['programme_id'];

        $central = 'central_app_mysql';

        if (Dossier::on($central)->where('entreprise_id', $item->id)->where('programme_id', $pid)->exists()) {
            return redirect()
                ->route('chef-filiere.clients.show', $token)
                ->with('info', 'Ce client possède déjà un dossier d\'instruction pour ce programme.');
        }

        if ($eer->programmeSelections()->where('programme_id', $pid)->exists()) {
            return redirect()
                ->route('chef-filiere.clients.show', $token)
                ->with('info', 'Ce programme est déjà lié à l\'entrée en relation.');
        }

        $dossier = null;
        $abortInfo = null;

        $maxAttempts = 3;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                DB::connection($central)->transaction(function () use ($item, $validated, $pid, &$dossier, &$abortInfo, $central) {
                    $eerLocked = DossierEntreeRelation::query()
                        ->where('entreprise_id', $item->id)
                        ->lockForUpdate()
                        ->first();

                    if (! $eerLocked || $eerLocked->qualification_validated_by_agence_at === null) {
                        $abortInfo = 'La qualification doit être validée par le chef d\'agence avant toute inscription à un programme.';

                        return;
                    }

                    if (Dossier::on($central)->where('entreprise_id', $item->id)->where('programme_id', $pid)->exists()) {
                        $abortInfo = 'Ce client possède déjà un dossier d\'instruction pour ce programme.';

                        return;
                    }

                    if (DossierEntreeRelationProgramme::query()
                        ->where('dossier_entree_relation_id', $eerLocked->id)
                        ->where('programme_id', $pid)
                        ->exists()) {
                        $abortInfo = 'Ce programme est déjà lié à l\'entrée en relation.';

                        return;
                    }

                    $dossier = Dossier::on($central)->create([
                        'entreprise_id' => $item->id,
                        'programme_id' => $pid,
                        'token' => sha1('instruction-'.$item->id.'-'.$pid.'-'.microtime(true)),
                        'gestionnaire_id' => $item->gestionnaire_id ?: $item->user_id ?: auth()->id(),
                        'agence_id' => $item->agence_id,
                        'representation_id' => $item->representation_id,
                        'active' => 0,
                    ]);

                    DossierEntreeRelationProgramme::query()->create([
                        'dossier_entree_relation_id' => $eerLocked->id,
                        'programme_id' => $pid,
                        'type_appui' => $validated['type_appui'] ?? DossierEntreeRelationProgramme::TYPE_FINANCIER,
                        'notes' => $validated['notes'] ?? null,
                        'statut' => DossierEntreeRelationProgramme::STATUT_VALIDE,
                        'instruction_dossier_id' => $dossier->id,
                        'validated_at' => now(),
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
            $analyseCritiqueService->syncInstructionDossier(
                $dossier,
                'Dossier d\'instruction créé suite à l\'inscription du client au programme (chef de filière).'
            );
        }

        return redirect()
            ->route('chef-filiere.clients.show', $token)
            ->with('success', 'Client inscrit au programme : un dossier d\'instruction a été créé.');
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
}

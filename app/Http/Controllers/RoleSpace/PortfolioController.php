<?php

namespace App\Http\Controllers\RoleSpace;

use App\Http\Controllers\Concerns\BuildsEntrepriseQuestionnaireResults;
use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\User;
use App\Services\DossierInstructionShowPresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    use BuildsEntrepriseQuestionnaireResults;
    use ResolvesRoleSpace;

    public function entreprisesIndex()
    {
        $space = $this->resolveSpace();
        $entreprises = Entreprise::query()
            ->with(['forme', 'user'])
            ->withCount('dossiers')
            ->orderByDesc('id')
            ->paginate(25);

        return view('RoleSpace.entreprises.index', compact('space', 'entreprises'));
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

        if (in_array($space['route'] ?? '', ['dg', 'dga'], true)) {
            $allowed = Dossier::query()
                ->where('entreprise_id', $item->id)
                ->whereNotNull('rerx_submitted_to_direction_at')
                ->exists();
            if (! $allowed) {
                abort(403);
            }
        }

        $item->load([
            'user',
            'forme',
            'filiere',
            'branche',
            'produit',
            'produits.filiere',
            'produits.branche',
            'appuis.type',
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

        if (in_array($space['route'] ?? '', ['dg', 'dga'], true)) {
            $allowed = Dossier::query()
                ->where('entreprise_id', $entreprise->id)
                ->whereNotNull('rerx_submitted_to_direction_at')
                ->exists();
            if (! $allowed) {
                abort(403);
            }
        }

        $checklist = $entreprise->piecesExigiblesChecklist();

        return view('RoleSpace.entreprises.pieces', compact('space', 'entreprise', 'checklist'));
    }

    public function dossiersIndex()
    {
        $space = $this->resolveSpace();
        $dossiersFilter = null;
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
        $query = Dossier::query()->with($with);

        if ($space['route'] === 'respexp' && request('filter') === 'a_affecter') {
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
            $query->whereNotNull('rerx_submitted_to_direction_at');
        }

        $dossiers = $query->orderByDesc('id')->paginate(25);

        return view('RoleSpace.dossiers.index', compact('space', 'dossiers', 'dossiersFilter'));
    }

    public function dossierShow(string $token)
    {
        $space = $this->resolveSpace();
        $with = [
            'entreprise',
            'programme',
            'analyste',
            'gestionnaire',
            'agence',
            'indicateurs',
            'reponses',
        ];
        $hubSpace = in_array($space['route'], ['respexp', 'juridique', 'analyste-juridique', 'reng', 'analyste-credit', 'rerx', 'analyste-risques', 'dg', 'dga'], true);
        if ($hubSpace) {
            $with[] = 'exploitationAvisCreditUser';
            $with[] = 'exploitationEngagementsDecisionUser';
            $with[] = 'exploitationAnalysteAssignedBy';
            $with[] = 'exploitationInstructionSubmittedBy';
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

        $dossierQuery = Dossier::query()
            ->with($with)
            ->where('token', $token);

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
            $dossierQuery->whereNotNull('rerx_submitted_to_direction_at');
        }

        $dossier = $dossierQuery->firstOrFail();

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
            && ! $dossier->isInstructionSubmittedToExploitation();

        return view('RoleSpace.dossiers.show', compact('space', 'dossier', 'analystesExploitation', 'analystesJuridique', 'analystesCredit', 'analystesRisques', 'exploitationSteps', 'respexpInstructionLocked'));
    }

    public function assignRerxAnalysteRisques(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('reng_submitted_to_risques_at')
            ->firstOrFail();

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
        $dossier->save();

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
        $dossier->save();

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
        $dossier->save();

        return redirect()
            ->route('reng.dossiers.show', $token)
            ->with('success', 'Dossier transmis au responsable risques.');
    }

    /**
     * Avis de crédit et validation engagements : uniquement après soumission de l’instruction par l’analyste.
     */
    private function respexpInstructionGate(Dossier $dossier): ?\Illuminate\Http\RedirectResponse
    {
        if (! $dossier->analyste_id) {
            return redirect()
                ->route('respexp.dossiers.show', $dossier->token)
                ->withErrors(['exploitation_avis_credit' => 'Affectez d’abord un analyste financier au dossier.']);
        }
        if (! $dossier->isInstructionSubmittedToExploitation()) {
            return redirect()
                ->route('respexp.dossiers.show', $dossier->token)
                ->withErrors([
                    'exploitation_avis_credit' => 'L’analyste n’a pas encore soumis le dossier pour validation : vous ne pouvez pas saisir d’avis ni de décision sur les engagements tant que l’instruction n’est pas transmise.',
                ]);
        }

        return null;
    }

    public function storeExploitationAvisCredit(Request $request, string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();
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

        if (! $dossier->analyste_id) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['decision' => 'Affectez d’abord un analyste financier au dossier.']);
        }
        if (! $dossier->isInstructionSubmittedToExploitation()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors(['decision' => 'L’analyste n’a pas encore soumis le dossier pour validation : vous ne pouvez pas statuer tant que l’instruction n’est pas transmise.']);
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

        if (! $dossier->canRespexpSoumettreAuJuridique()) {
            return redirect()
                ->route('respexp.dossiers.show', $token)
                ->withErrors([
                    'juridique' => 'Transmission impossible : vérifiez que l’instruction a été transmise par l’analyste, que l’avis de crédit est renseigné et que la validation des engagements est un accord (et que le dossier n’a pas déjà été transmis).',
                ]);
        }

        $dossier->juridique_instruction_submitted_at = now();
        $dossier->juridique_instruction_submitted_by_user_id = auth()->id();
        $dossier->save();

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
        $space = $this->resolveSpace();
        $data = app(DossierInstructionShowPresenter::class)->presentForDossier($dossier);

        return view('RoleSpace.dossiers.instruction_detail', array_merge(compact('space'), $data));
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
            if (! $dossier->rerx_submitted_to_direction_at) {
                return redirect()
                    ->route($back, $dossier->token)
                    ->with('error', 'Ce dossier n’a pas encore été transmis par le responsable risques.');
            }
        }

        if (! $dossier->analyste_id) {
            return redirect()
                ->route($back, $dossier->token)
                ->with('error', 'Aucun analyste n’est affecté à ce dossier : le contenu d’instruction n’est pas disponible.');
        }
        if (! $dossier->isInstructionSubmittedToExploitation()) {
            return redirect()
                ->route($back, $dossier->token)
                ->with('error', 'Le chargé d’instruction doit d’abord soumettre le dossier pour validation avant que vous puissiez consulter la grille de notation et l’ensemble du travail réalisé.');
        }

        return null;
    }
}

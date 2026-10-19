<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Services\AnalyseCritiqueService;
use App\Services\Inertia\StructurationPresenter;
use App\Services\WorkflowEmailNotificationService;
use App\Support\Inertia\Badge;
use App\Support\Inertia\DataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;

class QualificationController extends Controller
{
    public function __construct(private readonly AnalyseCritiqueService $analyseCritiqueService) {}

    public function index(Request $request)
    {
        $agenceId = auth()->user()->agence_id;
        $search = trim((string) $request->query('q', ''));
        $items = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->where(function ($q) {
                $q->whereDoesntHave('dossierEntreeRelation')
                    ->orWhereHas('dossierEntreeRelation', fn ($e) => $e->whereNull('programmes_submitted_at'));
            })
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', "%$search%")->orWhere('rccm', 'like', "%$search%")))
            ->with(['agence', 'dossierEntreeRelation'])
            ->orderByDesc('promu_client_at')
            ->paginate(25)
            ->withQueryString();

        $table = DataTable::make($items)
            ->search('Rechercher un client (nom, RCCM)…')
            ->columns([
                ['key' => 'client', 'label' => 'Client'],
                ['key' => 'agence', 'label' => 'Agence'],
                ['key' => 'promu', 'label' => 'Promu client le'],
                ['key' => 'statut', 'label' => 'État structuration'],
            ])
            ->row(fn (Entreprise $e) => [
                'href' => route('chef-filiere.clients.show', $e->token),
                'cells' => [
                    'client' => ['title' => $e->name, 'subtitle' => $e->rccm],
                    'agence' => $e->agence?->name,
                    'promu' => $e->promu_client_at ? Carbon::parse($e->promu_client_at)->format('d/m/Y H:i') : null,
                    'statut' => Badge::fromPresentation(DossierEntreeRelation::clientStructurationPresentation($e->dossierEntreeRelation)),
                ],
            ])
            ->empty('Aucune structuration en attente.');

        return Inertia::render('Workflow/Index', [
            'title' => 'Structurations en attente',
            'subtitle' => 'Clients promus dont la structuration n\'est pas encore soumise au chef d\'agence. Ouvrez le dossier client pour compléter la structuration.',
            'countLabel' => 'client(s)',
            'table' => $table->toArray(),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('chef-filiere.dashboard')],
                ['label' => 'Structurations en attente'],
            ],
        ]);
    }

    public function show(string $token)
    {
        if (! Session::get('chef_filiere_dossier_consulte_'.$token)) {
            return redirect()->route('chef-filiere.clients.show', $token)
                ->with('info', 'Veuillez consulter le dossier client complet avant d\'accéder à la structuration.');
        }

        $item = Entreprise::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->whereNotNull('promu_client_at')
            ->with([
                'agence',
                'tiers.person',
                'tiers.company',
                'dossierEntreeRelation.programmeSelections.programme',
                'programmes',
                'promuClientUser',
                'prospectRejectedUser',
            ])
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);

        $lockedPendingCa = $eer->programmes_submitted_at && $eer->qualification_validated_by_agence_at === null;
        $lockedAfterValidation = (bool) $eer->qualification_validated_by_agence_at;
        $qualificationEditable = ! $lockedPendingCa && ! $lockedAfterValidation;

        $eer->loadMissing('programmeSelections.programme');

        return Inertia::render('Structuration/Show', app(StructurationPresenter::class)->qualification(
            $item, $eer, $qualificationEditable, $lockedPendingCa, $lockedAfterValidation
        ) + [
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('chef-filiere.dashboard')],
                ['label' => 'Structurations', 'href' => route('chef-filiere.qualifications.index')],
                ['label' => Str::limit($item->name, 40)],
            ],
        ]);
    }

    public function update(Request $request, string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->whereNotNull('promu_client_at')
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);

        if ($eer->programmes_submitted_at && $eer->qualification_validated_by_agence_at === null) {
            Session::flash('info', 'Structuration transmise au chef d\'agence : modification impossible en attendant la validation.');

            return redirect()->route('chef-filiere.qualifications.show', $token);
        }

        if ($eer->qualification_validated_by_agence_at) {
            Session::flash('info', 'La structuration a été validée par le chef d\'agence. Les inscriptions aux programmes se font depuis la fiche client.');

            return redirect()->route('chef-filiere.clients.show', $token);
        }

        $data = $request->validate([
            'analyse_strategique' => 'nullable|string', // longueur illimitée (colonne LONGTEXT)
            'analyse_operationnelle' => 'nullable|string', // longueur illimitée (colonne LONGTEXT)
            'analyse_eligibilite' => 'nullable|string', // longueur illimitée (colonne LONGTEXT)
            'identification_besoins' => 'nullable|string', // longueur illimitée (colonne LONGTEXT)
            'qualification_notes' => 'nullable|string', // longueur illimitée (colonne LONGTEXT)
            'besoin_financement' => 'nullable|boolean',
            'besoin_accompagnement' => 'nullable|boolean',
            'besoin_structuration' => 'nullable|boolean',
        ]);

        $eer->fill($data);
        $eer->besoin_financement = $request->boolean('besoin_financement');
        $eer->besoin_accompagnement = $request->boolean('besoin_accompagnement');
        $eer->besoin_structuration = $request->boolean('besoin_structuration');
        $eer->qualification_completed_at = now();
        $eer->qualification_user_id = auth()->id();
        $eer->statut = DossierEntreeRelation::STATUT_QUALIFIE;
        $eer->save();

        $this->analyseCritiqueService->syncChefFiliereQualification($item, $eer);

        $mots = collect(['analyse_strategique', 'analyse_operationnelle', 'analyse_eligibilite', 'identification_besoins', 'qualification_notes'])
            ->sum(fn ($champ) => \App\Support\WordCount::of($eer->{$champ}));
        Session::flash('success', 'Structuration et identification des besoins enregistrées ('.\App\Support\WordCount::format($mots).').');

        return redirect()->route('chef-filiere.qualifications.show', $token);
    }

    public function submit(string $token)
    {
        if ($r = $this->redirectIfDossierNonConsulte($token)) {
            return $r;
        }

        $item = Entreprise::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->whereNotNull('promu_client_at')
            ->with('dossierEntreeRelation')
            ->firstOrFail();

        $eer = $this->getOrCreateEer($item);
        if ($eer->qualification_completed_at === null) {
            Session::flash('info', 'Complétez d’abord la structuration du client.');

            return redirect()->route('chef-filiere.qualifications.show', $token);
        }

        if ($eer->programmes_submitted_at) {
            Session::flash('info', 'La structuration a déjà été transmise au chef d\'agence.');

            return redirect()->route('chef-filiere.qualifications.show', $token);
        }

        $eer->programmes_submitted_at = now();
        $eer->programmes_submitted_by_user_id = auth()->id();
        $eer->statut = DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION;
        $eer->instruction_validation_status = DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION;
        $eer->qualification_rejected_by_agence_at = null;
        $eer->qualification_rejected_by_agence_user_id = null;
        $eer->qualification_reject_motif = null;
        $eer->save();

        $this->analyseCritiqueService->syncChefFiliereQualification($item, $eer);

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForEntreprise($item);
        $payload = $mailer->buildPayload(
            subject: 'Transmission de structuration — chef d’agence',
            title: 'Une structuration client a été transmise',
            body: "La structuration d’un client vient d’être soumise par le chef de filière pour validation.\n\nMerci de consulter la transmission et de valider ou rejeter la structuration.",
            ctaLabel: 'Ouvrir la transmission',
            ctaUrl: route('ca.workflow.instructions.show', $eer->token),
            event: 'submit_qualification_to_ca'
        );
        $recipients = $mailer->recipientsByRole(
            (int) config('angara.role_chef_agence', 15),
            $item->agence_id ? (int) $item->agence_id : null
        );
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        Session::flash('success', 'Structuration soumise au chef d\'agence pour validation. Après validation, vous constituerez le dossier d\'instruction (plusieurs programmes et budgets d\'appui) depuis la fiche client.');

        return redirect()->route('chef-filiere.qualifications.show', $token);
    }

    private function redirectIfDossierNonConsulte(string $token): ?\Illuminate\Http\RedirectResponse
    {
        if (! Session::get('chef_filiere_dossier_consulte_'.$token)) {
            return redirect()->route('chef-filiere.clients.show', $token)
                ->with('info', 'Veuillez consulter le dossier client complet avant la structuration.');
        }

        return null;
    }

    private function getOrCreateEer(Entreprise $entreprise): DossierEntreeRelation
    {
        return DossierEntreeRelation::firstOrCreate(
            ['entreprise_id' => $entreprise->id],
            [
                'token' => sha1('eer-'.$entreprise->id.'-'.microtime(true)),
                'statut' => DossierEntreeRelation::STATUT_CLIENT_VALIDE,
            ]
        );
    }
}

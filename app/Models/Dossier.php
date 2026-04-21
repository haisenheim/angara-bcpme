<?php

namespace App\Models;

use App\Helpers\DossierHelper;
use App\Models\Instruction\Reponse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Dossier extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'exploitation_avis_credit_at' => 'datetime',
        'exploitation_engagements_decision_at' => 'datetime',
        'exploitation_analyste_assigned_at' => 'datetime',
        'exploitation_instruction_submitted_at' => 'datetime',
        'juridique_instruction_submitted_at' => 'datetime',
        'juridique_analyste_assigned_at' => 'datetime',
        'juridique_analyste_submitted_to_reju_at' => 'datetime',
        'juridique_submitted_to_engagements_at' => 'datetime',
        'reng_analyste_credit_assigned_at' => 'datetime',
        'reng_analyste_credit_submitted_at' => 'datetime',
        'reng_submitted_to_risques_at' => 'datetime',
        'rerx_analyste_risques_assigned_at' => 'datetime',
        'rerx_analyste_risques_submitted_at' => 'datetime',
        'rerx_submitted_to_direction_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function entreprise(){
        return $this->belongsTo('App\Models\Entreprise');
    }

    public function programme(){
        return $this->belongsTo('App\Models\Programme');
    }

    public function gestionnaire(){
        return $this->belongsTo('App\Models\User','gestionnaire_id');
    }

    public function analyste(){
        return $this->belongsTo('App\Models\User','analyste_id');
    }

    public function exploitationAvisCreditUser()
    {
        return $this->belongsTo(User::class, 'exploitation_avis_credit_user_id');
    }

    public function exploitationEngagementsDecisionUser()
    {
        return $this->belongsTo(User::class, 'exploitation_engagements_decision_user_id');
    }

    /** Utilisateur (ex. REXP) qui a effectué l’affectation à l’analyste financier. */
    public function exploitationAnalysteAssignedBy()
    {
        return $this->belongsTo(User::class, 'exploitation_analyste_assigned_by_user_id');
    }

    /** Analyste ayant soumis le dossier au REXP après instruction terminée. */
    public function exploitationInstructionSubmittedBy()
    {
        return $this->belongsTo(User::class, 'exploitation_instruction_submitted_by_user_id');
    }

    /** Responsable exploitation ayant transmis le dossier au pôle juridique. */
    public function juridiqueInstructionSubmittedBy()
    {
        return $this->belongsTo(User::class, 'juridique_instruction_submitted_by_user_id');
    }

    public function juridiqueAnalysteUser()
    {
        return $this->belongsTo(User::class, 'juridique_analyste_user_id');
    }

    public function juridiqueAnalysteAssignedBy()
    {
        return $this->belongsTo(User::class, 'juridique_analyste_assigned_by_user_id');
    }

    public function juridiqueAnalysteSubmittedToRejuBy()
    {
        return $this->belongsTo(User::class, 'juridique_analyste_submitted_to_reju_by_user_id');
    }

    public function juridiqueSubmittedToEngagementsBy()
    {
        return $this->belongsTo(User::class, 'juridique_submitted_to_engagements_by_user_id');
    }

    public function rengAnalysteCreditUser()
    {
        return $this->belongsTo(User::class, 'reng_analyste_credit_user_id');
    }

    public function rengAnalysteCreditAssignedBy()
    {
        return $this->belongsTo(User::class, 'reng_analyste_credit_assigned_by_user_id');
    }

    public function rengAnalysteCreditSubmittedBy()
    {
        return $this->belongsTo(User::class, 'reng_analyste_credit_submitted_by_user_id');
    }

    public function rengSubmittedToRisquesBy()
    {
        return $this->belongsTo(User::class, 'reng_submitted_to_risques_by_user_id');
    }

    public function rerxAnalysteRisquesUser()
    {
        return $this->belongsTo(User::class, 'rerx_analyste_risques_user_id');
    }

    public function rerxAnalysteRisquesAssignedBy()
    {
        return $this->belongsTo(User::class, 'rerx_analyste_risques_assigned_by_user_id');
    }

    public function rerxAnalysteRisquesSubmittedBy()
    {
        return $this->belongsTo(User::class, 'rerx_analyste_risques_submitted_by_user_id');
    }

    public function rerxSubmittedToDirectionBy()
    {
        return $this->belongsTo(User::class, 'rerx_submitted_to_direction_by_user_id');
    }

    /**
     * Le responsable exploitation peut consulter le travail d’instruction et statuer (avis, validation)
     * uniquement après soumission par l’analyste.
     */
    public function isInstructionSubmittedToExploitation(): bool
    {
        return $this->exploitation_instruction_submitted_at !== null;
    }

    public function isSubmittedToJuridique(): bool
    {
        return $this->juridique_instruction_submitted_at !== null;
    }

    public function isJuridiqueAnalysteAvisSubmittedToReju(): bool
    {
        return $this->juridique_analyste_submitted_to_reju_at !== null;
    }

    public function isSubmittedToEngagementsFromJuridique(): bool
    {
        return $this->juridique_submitted_to_engagements_at !== null;
    }

    public function hasJuridiqueAnalysteAvisFilled(): bool
    {
        $raw = (string) ($this->juridique_analyste_avis ?? '');

        return strlen(trim(strip_tags($raw))) > 0;
    }

    public function hasJuridiqueResponsableAvisFilled(): bool
    {
        $raw = (string) ($this->juridique_responsable_avis ?? '');

        return strlen(trim(strip_tags($raw))) > 0;
    }

    public function isRengAnalysteCreditSubmittedToReng(): bool
    {
        return $this->reng_analyste_credit_submitted_at !== null;
    }

    public function isSubmittedToRisquesFromReng(): bool
    {
        return $this->reng_submitted_to_risques_at !== null;
    }

    public function hasRengResponsableAvisFilled(): bool
    {
        $raw = (string) ($this->reng_responsable_avis ?? '');

        return strlen(trim(strip_tags($raw))) > 0;
    }

    public function isRerxAnalysteRisquesSubmittedToRerx(): bool
    {
        return $this->rerx_analyste_risques_submitted_at !== null;
    }

    public function isSubmittedToDirectionFromRerx(): bool
    {
        return $this->rerx_submitted_to_direction_at !== null;
    }

    public function hasRerxResponsableAvisFilled(): bool
    {
        $raw = (string) ($this->rerx_responsable_avis ?? '');

        return strlen(trim(strip_tags($raw))) > 0;
    }

    /** Analyse des risques et avis analyste risques : requis avant soumission au responsable risques. */
    public function hasRerxAnalysteRisquesBundleFilledForSubmit(): bool
    {
        foreach (['rerx_analyse_risques', 'rerx_analyste_risques_avis'] as $attr) {
            $raw = (string) ($this->{$attr} ?? '');
            if (strlen(trim(strip_tags($raw))) === 0) {
                return false;
            }
        }

        return true;
    }

    /** Contre-analyse et avis analyste crédit : requis avant soumission au resp. engagements. */
    public function hasRengAnalysteCreditBundleFilledForSubmit(): bool
    {
        foreach (['reng_contre_analyse', 'reng_analyste_credit_avis'] as $attr) {
            $raw = (string) ($this->{$attr} ?? '');
            if (strlen(trim(strip_tags($raw))) === 0) {
                return false;
            }
        }

        return true;
    }

    /** Avis de crédit exploitable (hors balises vides type Summernote). */
    public function hasExploitationAvisCreditFilled(): bool
    {
        $raw = (string) ($this->exploitation_avis_credit ?? '');

        return strlen(trim(strip_tags($raw))) > 0;
    }

    /**
     * Le REXP peut transmettre au pôle juridique : instruction déjà reçue, avis de crédit renseigné, accord sur les engagements.
     */
    public function canRespexpSoumettreAuJuridique(): bool
    {
        if ($this->isSubmittedToJuridique()) {
            return false;
        }
        if (! $this->isInstructionSubmittedToExploitation()) {
            return false;
        }
        if (! $this->hasExploitationAvisCreditFilled()) {
            return false;
        }
        if ($this->exploitation_engagements_decision !== 'accord') {
            return false;
        }

        return true;
    }

    public function agence(){
        return $this->belongsTo('App\Models\Agence','agence_id');
    }

    public function representation(){
        return $this->belongsTo('App\Models\Representation','represenantion_id');
    }

    public function indicateurs(){
        return $this->hasMany('App\Models\Instruction\IndicateurFinancier','dossier_id');
    }



    public function reponses()
    {
        return $this->hasMany(Reponse::class);
    }

    /**
     * Entrées d’avis dans le dossier d’analyse critique liées à ce dossier d’instruction.
     */
    public function analyseCritiqueAvisMentions()
    {
        return $this->hasMany(AnalyseCritiqueAvis::class, 'instruction_dossier_id');
    }

    public function dossierEntreeRelationProgrammes()
    {
        return $this->hasMany(DossierEntreeRelationProgramme::class, 'instruction_dossier_id');
    }

    // Accessor pour 'name'
    public function getNameAttribute()
    {
        return $this->entreprise->name . ' ' . $this->programme->name;
    }

    // Accessor pour 'variations'
    public function getVariationsAttribute()
    {
        try {
            return DossierHelper::getVariations($this);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getStatusAttribute()
    {
        $indicateurs = $this->indicateurs;
        $reponses = $this->reponses;
        if($indicateurs->count() > 0){
            return [
                'status' => true,
                'name' => "En cours d'instruction",
                'code' => 1,
            ];
        }
        return [
            'status' => false,
            'name' => 'En attente d\'instruction',
            'code' => 0,
        ];
    }

    // Accessor pour 'note'
    public function getNoteAttribute()
    {
        $indicateurs = $this->indicateurs;
        $reponses = $this->reponses;
        $nql = 0;

        if ($reponses) {
            $nql = $reponses->sum('value');
        }

        $variations = [];
        $nf = 0;

        if ($indicateurs->count() > 0) {
            $annees = $indicateurs->pluck('annee')->sort()->toArray();
            for ($i = 0; $i < count($annees) - 1; $i++) {
                $n = $annees[$i + 1];
                $n_1 = $annees[$i];
                $indicateur_n = $indicateurs->where('annee', $n)->first();
                $indicateur_n_1 = $indicateurs->where('annee', $n_1)->first();

                if ($indicateur_n && $indicateur_n_1) {
                    $gap = [
                        'ca' => $this->calculateVariation($indicateur_n_1->ca, $indicateur_n->ca),
                        'marge_commerciale' => $this->calculateVariation($indicateur_n_1->marge_commerciale, $indicateur_n->marge_commerciale),
                        'va' => $this->calculateVariation($indicateur_n_1->va, $indicateur_n->va),
                        // Ajouter les autres variables ici...
                    ];
                    $variations[] = [
                        'periode' => "$n_1-$n",
                        'variation' => $gap,
                    ];
                }
            }

            // Exemple d'exercice
           /* $exercices = $indicateurs->map(function ($value) {
                return $value->serialize();
            }); */

           // $exercices = $indicateurs;

            $nf = $indicateurs[0]['notation']['note'];
        }

        return round($nf + $nql);
    }

    // Accessor pour 'state'
    public function getStateAttribute()
    {
        if ($this->indicateurs->count() > 0) {
            return [
                'status' => true,
                'name' => "En cours d'instruction",
            ];
        }

        return [
            'status' => false,
            'name' => 'En attente d\'instruction',
        ];
    }

    // Méthode pour calculer les variations
    private function calculateVariation($previous, $current)
    {
        return $previous != 0 ? round(($current - $previous) * 100 / $previous) : 0;
    }

    /**
     * Étapes affichées côté responsable exploitation (parcours dossier d’instruction).
     */
    public function exploitationWorkflowSteps(): array
    {
        $assigned = (bool) $this->analyste_id;
        $indCount = $this->relationLoaded('indicateurs')
            ? $this->indicateurs->count()
            : $this->indicateurs()->count();
        $repCount = $this->relationLoaded('reponses')
            ? $this->reponses->count()
            : $this->reponses()->count();
        $instructionStarted = $indCount > 0 || $repCount > 0;
        $submitted = $this->isInstructionSubmittedToExploitation();
        $avisDone = $this->hasExploitationAvisCreditFilled();
        $validationDone = filled($this->exploitation_engagements_decision);
        $juridiqueDone = $this->isSubmittedToJuridique();

        return [
            ['key' => 'assign', 'label' => 'Cotation — analyste financier', 'done' => $assigned],
            ['key' => 'instruction', 'label' => 'Instruction (grilles, engagements…)', 'done' => $instructionStarted],
            ['key' => 'submit', 'label' => 'Soumission à l’exploitation (analyste)', 'done' => $submitted],
            ['key' => 'avis', 'label' => 'Avis de crédit (REXP)', 'done' => $avisDone],
            ['key' => 'validation', 'label' => 'Validation dossier des engagements', 'done' => $validationDone],
            ['key' => 'juridique', 'label' => 'Transmission pôle juridique', 'done' => $juridiqueDone],
        ];
    }

    /**
     * Chronologie des affectations, avis, validations et transmissions (pour synthèse dossier).
     *
     * @return Collection<int, array{at: ?Carbon, sort: int, label: string, actor: ?User, body_html: ?string, kind: string}>
     */
    public function instructionWorkflowHistoryTimeline(): Collection
    {
        $rows = [];
        $n = 0;
        $push = function (
            ?Carbon $at,
            string $label,
            $actor = null,
            ?string $bodyHtml = null,
            string $kind = 'event'
        ) use (&$rows, &$n) {
            if ($at === null) {
                return;
            }
            $rows[] = [
                'at' => $at,
                'sort' => $n++,
                'label' => $label,
                'actor' => $actor,
                'body_html' => $bodyHtml,
                'kind' => $kind,
            ];
        };

        $htmlFilled = fn (?string $h): bool => strlen(trim(strip_tags((string) $h))) > 0;

        $push($this->exploitation_analyste_assigned_at, 'Cotation du dossier (affectation du chargé d’instruction)', $this->exploitationAnalysteAssignedBy, null, 'affectation');
        $push($this->exploitation_instruction_submitted_at, 'Instruction transmise au responsable exploitation', $this->exploitationInstructionSubmittedBy, null, 'transmission');

        if ($this->exploitation_avis_credit_at && $htmlFilled($this->exploitation_avis_credit)) {
            $push($this->exploitation_avis_credit_at, 'Avis de crédit — responsable exploitation', $this->exploitationAvisCreditUser, $this->exploitation_avis_credit, 'avis');
        } elseif ($this->exploitation_avis_credit_at) {
            $push($this->exploitation_avis_credit_at, 'Avis de crédit — responsable exploitation (saisi)', $this->exploitationAvisCreditUser, null, 'avis');
        }

        if ($this->exploitation_engagements_decision_at) {
            $dec = $this->exploitation_engagements_decision === 'accord' ? 'Accord' : ($this->exploitation_engagements_decision === 'rejet' ? 'Rejet' : (string) $this->exploitation_engagements_decision);
            $comment = $this->exploitation_engagements_decision_comment
                ? '<p class="mb-0 small"><strong>Commentaire :</strong> '.e($this->exploitation_engagements_decision_comment).'</p>'
                : null;
            $push($this->exploitation_engagements_decision_at, 'Décision sur le dossier des engagements — '.$dec, $this->exploitationEngagementsDecisionUser, $comment, 'validation');
        }

        $push($this->juridique_instruction_submitted_at, 'Transmission du dossier au pôle juridique', $this->juridiqueInstructionSubmittedBy, null, 'transmission');
        $push($this->juridique_analyste_assigned_at, 'Affectation de l’analyste juridique', $this->juridiqueAnalysteAssignedBy, null, 'affectation');

        if ($this->juridique_analyste_submitted_to_reju_at && $htmlFilled($this->juridique_analyste_avis)) {
            $push($this->juridique_analyste_submitted_to_reju_at, 'Avis de l’analyste juridique', $this->juridiqueAnalysteSubmittedToRejuBy, $this->juridique_analyste_avis, 'avis');
        } elseif ($this->juridique_analyste_submitted_to_reju_at) {
            $push($this->juridique_analyste_submitted_to_reju_at, 'Avis de l’analyste juridique (transmis au responsable juridique)', $this->juridiqueAnalysteSubmittedToRejuBy, null, 'avis');
        }

        if ($this->juridique_submitted_to_engagements_at) {
            $push($this->juridique_submitted_to_engagements_at, 'Transmission du dossier au responsable engagements', $this->juridiqueSubmittedToEngagementsBy, null, 'transmission');
            if ($htmlFilled($this->juridique_responsable_avis)) {
                $push($this->juridique_submitted_to_engagements_at, 'Avis du responsable juridique', $this->juridiqueSubmittedToEngagementsBy, $this->juridique_responsable_avis, 'avis');
            }
        }

        $push($this->reng_analyste_credit_assigned_at, 'Affectation de l’analyste crédit (responsable engagements)', $this->rengAnalysteCreditAssignedBy, null, 'affectation');

        if ($this->reng_analyste_credit_submitted_at) {
            $parts = [];
            if ($htmlFilled($this->reng_contre_analyse)) {
                $parts[] = '<p class="small fw-semibold mb-1">Contre-analyse</p><div class="border rounded p-2 bg-light small rich-text-rendered mb-2">'.$this->reng_contre_analyse.'</div>';
            }
            if ($htmlFilled($this->reng_analyste_credit_avis)) {
                $parts[] = '<p class="small fw-semibold mb-1">Avis analyste crédit</p><div class="border rounded p-2 bg-light small rich-text-rendered mb-2">'.$this->reng_analyste_credit_avis.'</div>';
            }
            if ($htmlFilled($this->reng_etat_engagements_client)) {
                $parts[] = '<p class="small fw-semibold mb-1">État des engagements du client</p><div class="border rounded p-2 bg-light small rich-text-rendered">'.$this->reng_etat_engagements_client.'</div>';
            }
            $body = count($parts) ? implode('', $parts) : null;
            $push($this->reng_analyste_credit_submitted_at, 'Soumission au responsable engagements (analyste crédit)', $this->rengAnalysteCreditSubmittedBy, $body, 'avis');
        }

        if ($htmlFilled($this->reng_responsable_avis)) {
            $atAvisRe = $this->reng_submitted_to_risques_at ?? $this->reng_analyste_credit_submitted_at;
            if ($atAvisRe) {
                $push($atAvisRe, 'Avis du responsable engagements', null, $this->reng_responsable_avis, 'avis');
            }
        }

        if ($this->reng_submitted_to_risques_at) {
            $push($this->reng_submitted_to_risques_at, 'Transmission du dossier au responsable risques', $this->rengSubmittedToRisquesBy, null, 'transmission');
        }

        $push($this->rerx_analyste_risques_assigned_at, 'Affectation de l’analyste risques', $this->rerxAnalysteRisquesAssignedBy, null, 'affectation');

        if ($this->rerx_analyste_risques_submitted_at) {
            $rxParts = [];
            if ($htmlFilled($this->rerx_analyse_risques)) {
                $rxParts[] = '<p class="small fw-semibold mb-1">Analyse des risques</p><div class="border rounded p-2 bg-light small rich-text-rendered mb-2">'.$this->rerx_analyse_risques.'</div>';
            }
            if ($htmlFilled($this->rerx_analyste_risques_avis)) {
                $rxParts[] = '<p class="small fw-semibold mb-1">Avis analyste risques</p><div class="border rounded p-2 bg-light small rich-text-rendered">'.$this->rerx_analyste_risques_avis.'</div>';
            }
            $rxBody = count($rxParts) ? implode('', $rxParts) : null;
            $push($this->rerx_analyste_risques_submitted_at, 'Soumission au responsable risques (analyste risques)', $this->rerxAnalysteRisquesSubmittedBy, $rxBody, 'avis');
        }

        if ($htmlFilled($this->rerx_responsable_avis)) {
            $atRerx = $this->rerx_submitted_to_direction_at ?? $this->rerx_analyste_risques_submitted_at;
            if ($atRerx) {
                $push($atRerx, 'Avis du responsable risques', null, $this->rerx_responsable_avis, 'avis');
            }
        }

        if ($this->rerx_submitted_to_direction_at) {
            $push($this->rerx_submitted_to_direction_at, 'Transmission à la direction (DG &amp; DGA)', $this->rerxSubmittedToDirectionBy, null, 'transmission');
        }

        return collect($rows)
            ->sort(function (array $a, array $b) {
                $ta = $a['at'] instanceof Carbon ? $a['at']->timestamp : 0;
                $tb = $b['at'] instanceof Carbon ? $b['at']->timestamp : 0;

                return $ta <=> $tb ?: $a['sort'] <=> $b['sort'];
            })
            ->values();
    }


}

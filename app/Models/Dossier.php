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
        'exploitation_analyste_transmitted_to_exploitation_at' => 'datetime',
        'juridique_instruction_submitted_at' => 'datetime',
        'juridique_analyste_assigned_at' => 'datetime',
        'juridique_analyste_submitted_to_reju_at' => 'datetime',
        'juridique_submitted_to_engagements_at' => 'datetime',
        'reng_analyste_credit_assigned_at' => 'datetime',
        'reng_analyste_credit_submitted_at' => 'datetime',
        'reng_submitted_to_risques_at' => 'datetime',
        'reng_responsable_avis_at' => 'datetime',
        'rerx_analyste_risques_assigned_at' => 'datetime',
        'rerx_analyste_risques_submitted_at' => 'datetime',
        'rerx_submitted_to_direction_at' => 'datetime',
        'rerx_responsable_avis_at' => 'datetime',
        'chef_filiere_submitted_to_agence_at' => 'datetime',
        'instruction_agence_validated_at' => 'datetime',
        'instruction_agence_rejected_at' => 'datetime',
        'instruction_closure_validated_at' => 'datetime',
        'instruction_closure_rejected_at' => 'datetime',
        'exploitation_analyste_instruction_avis_saved_at' => 'datetime',
        'conclusions_ca_saved_at' => 'datetime',
        'instruction_grille_last_edited_at' => 'datetime',
        'instruction_agence_ca_avis_saved_at' => 'datetime',
        'instruction_ca_transmitted_to_exploitation_at' => 'datetime',
        'juridique_analyste_avis_saved_at' => 'datetime',
        'juridique_responsable_avis_at' => 'datetime',
        'engagements_sollicites_total' => 'decimal:2',
        'engagements_en_cours_total' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function entreprise(){
        return $this->belongsTo('App\Models\Entreprise');
    }

    /** Fichiers (PDF / images) déposés sur ce dossier avec type et traçabilité. */
    public function fichiersDossier()
    {
        return $this->hasMany(Fichier::class, 'dossier_id')->orderByDesc('uploaded_at');
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

    /** Analyste financier ayant transmis le dossier au responsable exploitation (rubriques + grille). */
    public function exploitationAnalysteTransmittedToExploitationBy()
    {
        return $this->belongsTo(User::class, 'exploitation_analyste_transmitted_to_exploitation_by_user_id');
    }

    /** Chef de filière : transmission du dossier d’instruction multi-programmes au chef d’agence (avant validation / rejet). */
    public function chefFiliereSubmittedToAgenceBy()
    {
        return $this->belongsTo(User::class, 'chef_filiere_submitted_to_agence_by_user_id');
    }

    public function instructionAgenceValidatedBy()
    {
        return $this->belongsTo(User::class, 'instruction_agence_validated_by_user_id');
    }

    public function instructionAgenceRejectedBy()
    {
        return $this->belongsTo(User::class, 'instruction_agence_rejected_by_user_id');
    }

    /** Dernier enregistrement de l’avis du chef d’agence après validation de la transmission. */
    public function instructionAgenceCaAvisSavedBy()
    {
        return $this->belongsTo(User::class, 'instruction_agence_ca_avis_saved_by_user_id');
    }

    /** Chef d’agence : transmission du dossier d’instruction (avec avis) au responsable exploitation. */
    public function instructionCaTransmittedToExploitationBy()
    {
        return $this->belongsTo(User::class, 'instruction_ca_transmitted_to_exploitation_by_user_id');
    }

    public function isInstructionCaTransmittedToExploitation(): bool
    {
        return $this->instruction_ca_transmitted_to_exploitation_at !== null;
    }

    /** Avis du chef d’agence (post-validation transmission) : contenu exploitable hors balises. */
    public function hasInstructionAgenceCaAvisSubstance(): bool
    {
        return strlen(trim(strip_tags((string) ($this->instruction_agence_ca_avis ?? '')))) > 0;
    }

    /**
     * Le responsable exploitation peut agir (avis crédit, validation engagements, etc.) après
     * l’une ou l’autre des transmissions : {@see isInstructionTransmittedToExploitationByChefAgence()}
     * ou {@see isInstructionTransmittedToExploitationByAnalysteFinancier()}.
     */
    public function isInstructionVisibleToResponsableExploitation(): bool
    {
        return $this->isInstructionTransmittedToExploitationByAnalysteFinancier()
            || $this->isInstructionTransmittedToExploitationByChefAgence();
    }

    /** Soumis par le chef de filière et en attente de validation / rejet par le chef d’agence. */
    public function isInstructionPendingAgenceValidation(): bool
    {
        return $this->chef_filiere_submitted_to_agence_at !== null
            && $this->instruction_agence_validated_at === null
            && $this->instruction_agence_rejected_at === null;
    }

    public function isInstructionValidatedByAgence(): bool
    {
        return $this->instruction_agence_validated_at !== null;
    }

    public function isInstructionRejectedByAgence(): bool
    {
        return $this->instruction_agence_rejected_at !== null
            && $this->instruction_agence_validated_at === null;
    }

    /** Dossier d’instruction clos (fin de parcours) par décision habilitée (délégation de pouvoir / direction). */
    public function isInstructionClosed(): bool
    {
        return $this->instruction_closure_validated_at !== null || $this->instruction_closure_rejected_at !== null;
    }

    public function isInstructionClosureValidated(): bool
    {
        return $this->instruction_closure_validated_at !== null;
    }

    public function isInstructionClosureRejected(): bool
    {
        return $this->instruction_closure_rejected_at !== null && $this->instruction_closure_validated_at === null;
    }

    public function instructionClosureValidatedBy()
    {
        return $this->belongsTo(User::class, 'instruction_closure_validated_by_user_id');
    }

    public function instructionClosureRejectedBy()
    {
        return $this->belongsTo(User::class, 'instruction_closure_rejected_by_user_id');
    }

    /**
     * Conclusions enregistrées dans conclusions_ca (souvent saisies côté CA / direction) : absentes ou vides hors balises.
     */
    public function lacksMeaningfulDirectionConclusionsCa(): bool
    {
        return strlen(trim(strip_tags((string) ($this->conclusions_ca ?? '')))) === 0;
    }

    /**
     * Transmis par le responsable risques à la direction, sans conclusions direction significatives.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<Dossier>  $query
     * @return \Illuminate\Database\Eloquent\Builder<Dossier>
     */
    public function scopeAwaitingDirectionGeneralConclusion($query)
    {
        return $query->whereNotNull('rerx_submitted_to_direction_at')
            ->whereNotNull('instruction_agence_validated_at')
            ->whereNull('instruction_agence_rejected_at')
            ->where(function ($q) {
                $q->whereNull('conclusions_ca')
                    ->orWhereRaw('TRIM(conclusions_ca) = ?', [''])
                    ->orWhereIn('conclusions_ca', [
                        '<p><br></p>',
                        '<p></p>',
                        '<br>',
                    ]);
            });
    }

    /**
     * Dossiers d’instruction ayant reçu la validation du chef d’agence (hors rejet).
     *
     * @param  \Illuminate\Database\Eloquent\Builder<Dossier>  $query
     * @return \Illuminate\Database\Eloquent\Builder<Dossier>
     */
    public function scopeInstructionValidesParChefAgence($query)
    {
        return $query->whereNotNull('instruction_agence_validated_at')
            ->whereNull('instruction_agence_rejected_at');
    }

    /**
     * Les programmes liés à ce dossier sont indisponibles pour une nouvelle composition
     * (sauf si le dossier a été rejeté par le chef d’agence).
     */
    public function locksInstructionProgrammesForComposition(): bool
    {
        if ($this->instruction_agence_rejected_at !== null) {
            return false;
        }
        if ($this->instruction_agence_validated_at !== null) {
            return true;
        }

        return $this->chef_filiere_submitted_to_agence_at !== null;
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

    public function juridiqueAnalysteAvisSavedBy()
    {
        return $this->belongsTo(User::class, 'juridique_analyste_avis_saved_by_user_id');
    }

    public function juridiqueResponsableAvisBy()
    {
        return $this->belongsTo(User::class, 'juridique_responsable_avis_by_user_id');
    }

    public function juridiqueSubmittedToEngagementsBy()
    {
        return $this->belongsTo(User::class, 'juridique_submitted_to_engagements_by_user_id');
    }

    public function instructionGrilleLastEditedBy()
    {
        return $this->belongsTo(User::class, 'instruction_grille_last_edited_by_user_id');
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

    public function rengResponsableAvisBy()
    {
        return $this->belongsTo(User::class, 'reng_responsable_avis_by_user_id');
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

    public function rerxResponsableAvisBy()
    {
        return $this->belongsTo(User::class, 'rerx_responsable_avis_by_user_id');
    }

    public function conclusionsCaSavedBy()
    {
        return $this->belongsTo(User::class, 'conclusions_ca_saved_by_user_id');
    }

    /**
     * Rubriques Summernote saisies par l’analyste financier sur le dossier (grille + DSF), ordre métier.
     *
     * @var array<string, string>
     */
    public const EXPLOITATION_AF_INSTRUCTION_SECTIONS = [
        'exploitation_af_breves_donnees_client' => 'Brèves données générales actualisées sur le client',
        'exploitation_af_analyse_critique_ensemble' => 'Analyse critique d’ensemble',
        'exploitation_af_analyse_financiere_client' => 'Analyse financière du client',
        'exploitation_af_appuis_proposes' => 'Appuis financiers et non financiers proposés',
        'exploitation_af_analyse_risque_remboursement' => 'Analyse du risque et de la capacité de remboursement du client',
        'exploitation_af_rentabilite_relation' => 'Rentabilité de la relation pour l’établissement',
        'exploitation_af_conclusions_recommandations' => 'Conclusions motivées, recommandations de l’analyste financier',
    ];

    /** @return array<int, string> */
    public static function exploitationAfInstructionSectionColumns(): array
    {
        return array_keys(self::EXPLOITATION_AF_INSTRUCTION_SECTIONS);
    }

    public function afInstructionSectionHasSubstance(string $column): bool
    {
        return strlen(trim(strip_tags((string) ($this->{$column} ?? '')))) > 0;
    }

    public function hasAnyExploitationAfInstructionSectionFilled(): bool
    {
        foreach (self::exploitationAfInstructionSectionColumns() as $col) {
            if ($this->afInstructionSectionHasSubstance($col)) {
                return true;
            }
        }

        return false;
    }

    /** Les sept rubriques sont renseignées (hors balises vides). */
    public function hasExploitationAfInstructionSectionsComplete(): bool
    {
        foreach (self::exploitationAfInstructionSectionColumns() as $col) {
            if (! $this->afInstructionSectionHasSubstance($col)) {
                return false;
            }
        }

        return true;
    }

    /** Soumission au REXP : les sept rubriques Summernote sont obligatoires. */
    public function canSubmitAnalysteInstructionToExploitation(): bool
    {
        return $this->hasExploitationAfInstructionSectionsComplete();
    }

    /** Assemble les rubriques pour l’affichage chronologique / consultation (HTML). */
    public function compileExploitationAnalysteInstructionAvisFromAfSections(): string
    {
        $parts = [];
        foreach (self::EXPLOITATION_AF_INSTRUCTION_SECTIONS as $column => $label) {
            $html = (string) ($this->{$column} ?? '');
            if (! $this->afInstructionSectionHasSubstance($column)) {
                continue;
            }
            $parts[] = '<h6 class="small text-uppercase text-muted mb-2">'.e($label).'</h6>'
                . '<div class="mb-4 rich-text-rendered">'.$html.'</div>';
        }

        return implode('', $parts);
    }

    /**
     * Met à jour {@see $exploitation_analyste_instruction_avis} à partir des rubriques lorsqu’au moins une est saisie.
     */
    public function syncExploitationAnalysteInstructionAvisFromAfSections(): void
    {
        if ($this->hasAnyExploitationAfInstructionSectionFilled()) {
            $this->exploitation_analyste_instruction_avis = $this->compileExploitationAnalysteInstructionAvisFromAfSections();

            return;
        }
        $this->exploitation_analyste_instruction_avis = null;
    }

    /** Contenu utile pour la consultation (rubriques et/ou agrégat compilé). */
    public function hasExploitationAnalysteInstructionAvisSubstance(): bool
    {
        return $this->hasAnyExploitationAfInstructionSectionFilled()
            || strlen(trim(strip_tags((string) ($this->exploitation_analyste_instruction_avis ?? '')))) > 0;
    }

    /**
     * Transmission au REXP par le chef d’agence (après validation de la transmission par le chef de filière).
     * Colonnes : {@see $instruction_ca_transmitted_to_exploitation_at}, {@see $instruction_ca_transmitted_to_exploitation_by_user_id}.
     */
    public function isInstructionTransmittedToExploitationByChefAgence(): bool
    {
        return $this->isInstructionCaTransmittedToExploitation();
    }

    /**
     * Transmission au REXP par l’analyste financier (saisie des rubriques + grille de notation).
     * Colonnes : {@see $exploitation_analyste_transmitted_to_exploitation_at}, {@see $exploitation_analyste_transmitted_to_exploitation_by_user_id}.
     */
    public function isInstructionTransmittedToExploitationByAnalysteFinancier(): bool
    {
        return $this->exploitation_analyste_transmitted_to_exploitation_at !== null;
    }

    /**
     * Alias historique : soumission « instruction » côté analyste (distincte de la transmission chef d’agence).
     */
    public function isInstructionSubmittedToExploitation(): bool
    {
        return $this->isInstructionTransmittedToExploitationByAnalysteFinancier();
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
        if (! $this->isInstructionVisibleToResponsableExploitation()) {
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

    /**
     * Programmes rattachés au dossier d’instruction (budgets par programme).
     */
    public function instructionProgrammes()
    {
        return $this->hasMany(DossierInstructionProgramme::class, 'dossier_id')->orderBy('sort_order')->orderBy('id');
    }

    /** Signataires distincts (réf. programme) pour affichage liste CA. */
    public function signatairesLabel(): string
    {
        if ($this->relationLoaded('instructionProgrammes') && $this->instructionProgrammes->isNotEmpty()) {
            $this->instructionProgrammes->loadMissing('programme');
            $s = $this->instructionProgrammes->map(fn (DossierInstructionProgramme $dip) => $dip->programme?->signataire)->filter()->unique()->values();

            return $s->isNotEmpty() ? $s->implode(' · ') : '—';
        }

        return $this->programme?->signataire ?? '—';
    }

    /** Libellé des programmes (multi-programme ou programme_id historique). */
    public function programmesLabel(): string
    {
        if ($this->relationLoaded('instructionProgrammes') && $this->instructionProgrammes->isNotEmpty()) {
            $this->instructionProgrammes->loadMissing('programme');

            return $this->instructionProgrammes->pluck('programme.name')->filter()->implode(', ');
        }

        if ($this->instructionProgrammes()->exists()) {
            return $this->instructionProgrammes()->with('programme')->get()->pluck('programme.name')->filter()->implode(', ')
                ?: ($this->programme?->name ?? '—');
        }

        return $this->programme?->name ?? '—';
    }

    // Accessor pour 'name'
    public function getNameAttribute()
    {
        $entrepriseName = $this->entreprise->name ?? '';
        if ($this->relationLoaded('instructionProgrammes') && $this->instructionProgrammes->isNotEmpty()) {
            $pl = $this->programmesLabel();

            return trim($entrepriseName.($pl !== '—' ? ' — '.$pl : ''));
        }
        $p = $this->programme?->name ?? '';

        return trim($entrepriseName.' '.$p);
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
        $submitted = $this->isInstructionVisibleToResponsableExploitation();
        $avisDone = $this->hasExploitationAvisCreditFilled();
        $validationDone = filled($this->exploitation_engagements_decision);
        $juridiqueDone = $this->isSubmittedToJuridique();

        return [
            ['key' => 'assign', 'label' => 'Cotation — analyste financier', 'done' => $assigned],
            ['key' => 'instruction', 'label' => 'Instruction (grilles, engagements…)', 'done' => $instructionStarted],
            ['key' => 'submit', 'label' => 'Réception instruction (transmission CA et/ou analyste financier)', 'done' => $submitted],
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

        $push($this->chef_filiere_submitted_to_agence_at, 'Transmission du dossier d’instruction au chef d’agence (chef de filière)', $this->chefFiliereSubmittedToAgenceBy, null, 'transmission');
        $valClotureBody = null;
        if ($this->instruction_agence_closing_note) {
            $valClotureBody = '<p class="mb-0 small"><strong>Note :</strong> '.e($this->instruction_agence_closing_note).'</p>';
        }
        $push($this->instruction_agence_validated_at, 'Validation du dossier d’instruction (chef d’agence)', $this->instructionAgenceValidatedBy, $valClotureBody, 'validation');
        if ($this->instruction_agence_ca_avis_saved_at && $htmlFilled((string) ($this->instruction_agence_ca_avis ?? ''))) {
            $push(
                $this->instruction_agence_ca_avis_saved_at,
                'Avis du chef d’agence (après validation de la transmission)',
                $this->instructionAgenceCaAvisSavedBy,
                $this->instruction_agence_ca_avis,
                'avis'
            );
        }
        if ($this->instruction_ca_transmitted_to_exploitation_at) {
            $push(
                $this->instruction_ca_transmitted_to_exploitation_at,
                'Transmission au responsable exploitation (chef d’agence)',
                $this->instructionCaTransmittedToExploitationBy,
                '<p class="mb-0 small">Le dossier d’instruction et l’avis du chef d’agence sont transmis au responsable exploitation pour la suite du parcours.</p>',
                'transmission'
            );
        }
        if ($this->instruction_agence_rejected_at) {
            $motifParts = [];
            if ($this->instruction_agence_reject_motif) {
                $motifParts[] = '<p class="mb-0 small"><strong>Motif :</strong> '.e($this->instruction_agence_reject_motif).'</p>';
            }
            if ($this->instruction_agence_closing_note) {
                $motifParts[] = '<p class="mb-0 small"><strong>Note :</strong> '.e($this->instruction_agence_closing_note).'</p>';
            }
            $motifHtml = count($motifParts) ? implode('', $motifParts) : null;
            $push($this->instruction_agence_rejected_at, 'Rejet du dossier d’instruction (chef d’agence)', $this->instructionAgenceRejectedBy, $motifHtml, 'validation');
        }

        // Clôture dossier d’instruction (fin de parcours) — dépend de la délégation de pouvoir.
        $instructionClosureBody = null;
        if ($this->instruction_closure_note) {
            $instructionClosureBody = '<p class="mb-0 small"><strong>Note :</strong> '.e($this->instruction_closure_note).'</p>';
        }
        $push($this->instruction_closure_validated_at, 'Clôture du dossier d’instruction', $this->instructionClosureValidatedBy, $instructionClosureBody, 'validation');

        if ($this->instruction_closure_rejected_at) {
            $motifParts = [];
            if ($this->instruction_closure_reject_motif) {
                $motifParts[] = '<p class="mb-0 small"><strong>Motif :</strong> '.e($this->instruction_closure_reject_motif).'</p>';
            }
            if ($this->instruction_closure_note) {
                $motifParts[] = '<p class="mb-0 small"><strong>Note :</strong> '.e($this->instruction_closure_note).'</p>';
            }
            $motifHtml = count($motifParts) ? implode('', $motifParts) : null;
            $push($this->instruction_closure_rejected_at, 'Rejet de clôture du dossier d’instruction', $this->instructionClosureRejectedBy, $motifHtml, 'validation');
        }

        $push($this->exploitation_analyste_assigned_at, 'Cotation du dossier (affectation du chargé d’instruction)', $this->exploitationAnalysteAssignedBy, null, 'affectation');
        $push(
            $this->exploitation_analyste_transmitted_to_exploitation_at,
            'Transmission au responsable exploitation (analyste financier — rubriques et grille)',
            $this->exploitationAnalysteTransmittedToExploitationBy,
            null,
            'transmission'
        );

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

        if ($this->juridique_analyste_avis_saved_at && $htmlFilled($this->juridique_analyste_avis)) {
            $isDraft = ! $this->juridique_analyste_submitted_to_reju_at
                || ($this->juridique_analyste_avis_saved_at instanceof Carbon
                    && $this->juridique_analyste_submitted_to_reju_at instanceof Carbon
                    && $this->juridique_analyste_avis_saved_at->greaterThan($this->juridique_analyste_submitted_to_reju_at));

            if ($isDraft) {
                $push($this->juridique_analyste_avis_saved_at, 'Mise à jour de l’avis analyste juridique', $this->juridiqueAnalysteAvisSavedBy, $this->juridique_analyste_avis, 'avis');
            }
        }

        if ($this->juridique_analyste_submitted_to_reju_at && $htmlFilled($this->juridique_analyste_avis)) {
            $push($this->juridique_analyste_submitted_to_reju_at, 'Avis de l’analyste juridique', $this->juridiqueAnalysteSubmittedToRejuBy, $this->juridique_analyste_avis, 'avis');
        } elseif ($this->juridique_analyste_submitted_to_reju_at) {
            $push($this->juridique_analyste_submitted_to_reju_at, 'Avis de l’analyste juridique (transmis au responsable juridique)', $this->juridiqueAnalysteSubmittedToRejuBy, null, 'avis');
        }

        if ($this->juridique_responsable_avis_at && $htmlFilled($this->juridique_responsable_avis)) {
            $push($this->juridique_responsable_avis_at, 'Avis du responsable juridique', $this->juridiqueResponsableAvisBy, $this->juridique_responsable_avis, 'avis');
        }

        if ($this->juridique_submitted_to_engagements_at) {
            $push($this->juridique_submitted_to_engagements_at, 'Transmission du dossier au responsable engagements', $this->juridiqueSubmittedToEngagementsBy, null, 'transmission');
        }

        if ($this->instruction_grille_last_edited_at) {
            $push($this->instruction_grille_last_edited_at, 'Mise à jour de la grille d’instruction (analyste financier)', $this->instructionGrilleLastEditedBy, null, 'event');
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
            $body = count($parts) ? implode('', $parts) : null;
            $push($this->reng_analyste_credit_submitted_at, 'Soumission au responsable engagements (analyste crédit)', $this->rengAnalysteCreditSubmittedBy, $body, 'avis');
        }

        if ($htmlFilled($this->reng_responsable_avis)) {
            $atAvisRe = $this->reng_responsable_avis_at ?? $this->reng_submitted_to_risques_at ?? $this->reng_analyste_credit_submitted_at;
            if ($atAvisRe) {
                $push($atAvisRe, 'Avis du responsable engagements', $this->rengResponsableAvisBy, $this->reng_responsable_avis, 'avis');
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
            $atRerx = $this->rerx_responsable_avis_at ?? $this->rerx_submitted_to_direction_at ?? $this->rerx_analyste_risques_submitted_at;
            if ($atRerx) {
                $push($atRerx, 'Avis du responsable risques', $this->rerxResponsableAvisBy, $this->rerx_responsable_avis, 'avis');
            }
        }

        if ($this->rerx_submitted_to_direction_at) {
            $push($this->rerx_submitted_to_direction_at, 'Transmission à la direction (DG &amp; DGA)', $this->rerxSubmittedToDirectionBy, null, 'transmission');
        }

        if ($this->conclusions_ca_saved_at && $htmlFilled((string) ($this->conclusions_ca ?? ''))) {
            $push($this->conclusions_ca_saved_at, 'Conclusions direction (DG / DGA)', $this->conclusionsCaSavedBy, (string) $this->conclusions_ca, 'avis');
        }

        return collect($rows)
            ->sort(function (array $a, array $b) {
                $ta = $a['at'] instanceof Carbon ? $a['at']->timestamp : 0;
                $tb = $b['at'] instanceof Carbon ? $b['at']->timestamp : 0;

                return $ta <=> $tb ?: $a['sort'] <=> $b['sort'];
            })
            ->values();
    }

    /**
     * Organisation métier où se situe actuellement le dossier (agence ou entité).
     *
     * @return array{type: 'agence'|'entite', key: string, label: string, agence_id: ?int, entity_route: ?string}
     */
    public function currentOrganisation(): array
    {
        if ($this->isInstructionPendingAgenceValidation()) {
            $aid = $this->agence_id ? (int) $this->agence_id : null;

            return [
                'type' => 'agence',
                'key' => $aid ? ('agence:'.$aid) : 'agence',
                'label' => $this->agence?->name ?? 'Agence',
                'agence_id' => $aid,
                'entity_route' => null,
            ];
        }

        if ($this->rerx_submitted_to_direction_at !== null) {
            return [
                'type' => 'entite',
                'key' => 'entite:direction_generale',
                'label' => 'Direction générale',
                'agence_id' => null,
                'entity_route' => 'dg',
            ];
        }

        if ($this->reng_submitted_to_risques_at !== null) {
            return [
                'type' => 'entite',
                'key' => 'entite:pole_risques',
                'label' => 'Pôle risques',
                'agence_id' => null,
                'entity_route' => 'rerx',
            ];
        }

        if ($this->juridique_submitted_to_engagements_at !== null) {
            return [
                'type' => 'entite',
                'key' => 'entite:pole_engagements',
                'label' => 'Pôle engagements',
                'agence_id' => null,
                'entity_route' => 'reng',
            ];
        }

        if ($this->juridique_instruction_submitted_at !== null) {
            return [
                'type' => 'entite',
                'key' => 'entite:pole_juridique',
                'label' => 'Pôle juridique',
                'agence_id' => null,
                'entity_route' => 'juridique',
            ];
        }

        return [
            'type' => 'entite',
            'key' => 'entite:pole_exploitation',
            'label' => 'Pôle exploitation',
            'agence_id' => null,
            'entity_route' => 'respexp',
        ];
    }

    public function currentOrganisationKey(): string
    {
        return (string) ($this->currentOrganisation()['key'] ?? '');
    }

    public function currentOrganisationLabel(): string
    {
        return (string) ($this->currentOrganisation()['label'] ?? '');
    }

    public function currentEntityRoute(): ?string
    {
        return $this->currentOrganisation()['entity_route'] ?? null;
    }

    public function scopeInCurrentEntity($query, string $entityRoute)
    {
        return match ($entityRoute) {
            'dg', 'dga' => $query->whereNotNull('rerx_submitted_to_direction_at'),
            'rerx' => $query->whereNotNull('reng_submitted_to_risques_at')
                ->whereNull('rerx_submitted_to_direction_at'),
            'reng' => $query->whereNotNull('juridique_submitted_to_engagements_at')
                ->whereNull('reng_submitted_to_risques_at'),
            'juridique' => $query->whereNotNull('juridique_instruction_submitted_at')
                ->whereNull('juridique_submitted_to_engagements_at'),
            'respexp' => $query->whereNull('juridique_instruction_submitted_at'),
            default => $query,
        };
    }


}

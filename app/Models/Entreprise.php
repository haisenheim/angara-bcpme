<?php

namespace App\Models;

use App\Models\Engagement\EngagementLigne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Entreprise extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $connection = 'central_app_mysql';

    protected function casts(): array
    {
        return [
            'prospect_submitted_at' => 'datetime',
            'juridique_avis_at' => 'datetime',
            'conformite_avis_at' => 'datetime',
            'promu_client_at' => 'datetime',
            'prospect_rejected_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function tiers()
    {
        return $this->hasMany('App\Models\Tier', 'entreprise_id');
    }

    public function engagementLignes(): HasMany
    {
        return $this->hasMany(EngagementLigne::class, 'entreprise_id');
    }

    public function dossierEntreeRelation()
    {
        return $this->hasOne(DossierEntreeRelation::class);
    }

    public function dossierAnalyseCritique()
    {
        return $this->hasOne(DossierAnalyseCritique::class);
    }

    public function piecesExigibles()
    {
        return $this->hasMany(EntreprisePieceExigible::class);
    }

    /**
     * Pour chaque pièce paramétrée (active), indique si un fichier a été rattaché.
     *
     * @return Collection<int, array{definition: PieceExigibleDefinition, fourni: bool, entreprise_piece: ?EntreprisePieceExigible}>
     */
    public function piecesExigiblesChecklist(): Collection
    {
        $definitions = PieceExigibleDefinition::query()
            ->where('active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $parDef = $this->piecesExigibles()->with('fichier')->get()->keyBy('piece_exigible_definition_id');

        return $definitions->map(function (PieceExigibleDefinition $def) use ($parDef) {
            $ligne = $parDef->get($def->id);

            return [
                'definition' => $def,
                'fourni' => $ligne !== null && $ligne->isProvided(),
                'entreprise_piece' => $ligne,
            ];
        });
    }

    public function reponses()
    {
        return $this->hasMany('App\Models\QuestionAnswer', 'entreprise_id');
    }

    public function critereAvis(): HasMany
    {
        return $this->hasMany(EntrepriseCritereAvis::class, 'entreprise_id');
    }

    public function answers()
    {
        return $this->hasMany('App\Models\Instruction\Scoring\Individual\DossierChoice', 'dossier_id');
    }

    public function dossiers()
    {
        return $this->hasMany('App\Models\Dossier', 'entreprise_id');
    }

    public function fichiers()
    {
        return $this->hasMany('App\Models\Fichier', 'entreprise_id');
    }

    public function produit()
    {
        return $this->belongsTo('App\Models\Produit'); // produit principale
    }

    public function produits()
    {
        return $this->belongsToMany('App\Models\Produit', 'entreprise_produits'); // autres produits
    }

    public function appuis()
    {
        return $this->belongsToMany('App\Models\Service', 'entreprise_appuis'); // autres produits
    }

    public function elements()
    {
        return $this->hasMany('App\Models\EntrepriseElementConstitutif', 'entreprise_id'); // autres produits
    }

    public function filiere()
    {
        return $this->belongsTo('App\Models\Filiere');
    }

    public function branche()
    {
        return $this->belongsTo('App\Models\Branche');
    }

    public function forme()
    {
        return $this->belongsTo('App\Models\Forme');
    }

    public function programmes()
    {
        return $this->belongsToMany('App\Models\Programme', 'dossiers');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function juridiqueAvisUser()
    {
        return $this->belongsTo(User::class, 'juridique_avis_user_id');
    }

    public function conformiteAvisUser()
    {
        return $this->belongsTo(User::class, 'conformite_avis_user_id');
    }

    public function promuClientUser()
    {
        return $this->belongsTo(User::class, 'promu_client_user_id');
    }

    public function prospectRejectedUser()
    {
        return $this->belongsTo(User::class, 'prospect_rejected_user_id');
    }

    /**
     * Avis juridique / conformité figés après validation client ou refus par le chef d'agence.
     */
    public function isProspectAvisCircuitClosed(): bool
    {
        return $this->promu_client_at !== null || $this->prospect_rejected_at !== null;
    }

    /**
     * Fiches prospect hors brouillon (soumission gestionnaire enregistrée).
     */
    public function scopeSubmittedProspect(Builder $query): Builder
    {
        return $query->where('prospect', true)->whereNotNull('prospect_submitted_at');
    }

    /**
     * Statut métier global de l'entreprise (référence : prompt.txt l.30-36).
     *
     * Gère les états « cycle de vie » au-delà du cas client structuré (prospect en cours / rejeté / etc.).
     * Pour les entreprises promues client, délègue à {@see DossierEntreeRelation::clientStructurationPresentation()}.
     *
     * @return array{code: string, label: string, badge_variant: string, detail: ?string}
     */
    public function clientStatutPresentation(): array
    {
        if ($this->prospect_rejected_at !== null && $this->promu_client_at === null) {
            return [
                'code' => 'prospect_rejete',
                'label' => 'Prospect rejeté',
                'badge_variant' => 'danger',
                'detail' => 'Refus de conversion en client par le chef d’agence',
            ];
        }

        if ($this->promu_client_at === null) {
            if ($this->prospect_submitted_at !== null) {
                return [
                    'code' => 'prospect_en_cours',
                    'label' => 'Prospect — avis en cours',
                    'badge_variant' => 'info',
                    'detail' => 'Avis juridique / conformité / décision chef d’agence en attente',
                ];
            }

            return [
                'code' => 'prospect_brouillon',
                'label' => 'Prospect (brouillon)',
                'badge_variant' => 'secondary',
                'detail' => 'Pas encore soumis pour avis',
            ];
        }

        // Entreprise promue client : on délègue au statut « structuration » prévu par le prompt (l.30-36).
        $this->loadMissing('dossierEntreeRelation');

        return DossierEntreeRelation::clientStructurationPresentation($this->dossierEntreeRelation);
    }

    public function taille()
    {
        return $this->belongsTo('App\Models\Taille');
    }

    public function representation()
    {
        return $this->belongsTo('App\Models\Representation');
    }

    public function agence()
    {
        return $this->belongsTo('App\Models\Agence');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function departement()
    {
        return $this->belongsTo('App\Models\Departement');
    }

    public function arrondissement()
    {
        return $this->belongsTo('App\Models\Arrondissement');
    }

    public function village()
    {
        return $this->belongsTo('App\Models\Village');
    }

    public function quartier()
    {
        return $this->belongsTo('App\Models\Quartier');
    }

    public function sites(): HasMany
    {
        return $this->hasMany(EntrepriseSite::class, 'entreprise_id')->orderBy('libelle');
    }

    public function equipeMembres(): HasMany
    {
        return $this->hasMany(EntrepriseEquipeMembre::class, 'entreprise_id')->orderBy('nom')->orderBy('prenom');
    }

    public function getTpersoAttribute()
    {
        if ($this->personnel_mixte) {
            return 'mixte';
        }
        if ($this->personnel_permanent) {
            return 'permanent';
        }
        if ($this->personnel_saisonier) {
            return 'saisonier';
        }

        return 'xxx';
    }

    /**
     * Filtre les entreprises **promues client** selon le statut métier de structuration (EER).
     *
     * @param  Builder<\App\Models\Entreprise>  $query
     * @return Builder<\App\Models\Entreprise>
     */
    public function scopeWhereClientStructurationStatus(Builder $query, string $status): Builder
    {
        $code = DossierEntreeRelation::normalizeClientStructurationFilter($status);
        if ($code === null) {
            return $query;
        }

        $query->whereNotNull('promu_client_at');

        return match ($code) {
            DossierEntreeRelation::CLIENT_STRUCT_STATUS_STRUCTURE => $query->whereHas('dossierEntreeRelation', function ($q) {
                $q->whereNotNull('qualification_validated_by_agence_at');
            }),
            DossierEntreeRelation::CLIENT_STRUCT_STATUS_REJETEE => $query->whereHas('dossierEntreeRelation', function ($q) {
                $q->whereNotNull('qualification_rejected_by_agence_at')
                    ->whereNull('programmes_submitted_at')
                    ->whereNull('qualification_validated_by_agence_at');
            }),
            DossierEntreeRelation::CLIENT_STRUCT_STATUS_EN_COURS => $query->where(function ($outer) {
                $outer->whereHas('dossierEntreeRelation', function ($q) {
                    $q->whereNotNull('programmes_submitted_at')
                        ->whereNull('qualification_validated_by_agence_at');
                })->orWhereHas('dossierEntreeRelation', function ($q) {
                    $q->whereNotNull('qualification_completed_at')
                        ->whereNull('programmes_submitted_at')
                        ->whereNull('qualification_validated_by_agence_at')
                        ->whereNull('qualification_rejected_by_agence_at');
                });
            }),
            DossierEntreeRelation::CLIENT_STRUCT_STATUS_ATTENTE => $query->where(function ($outer) {
                $outer->whereDoesntHave('dossierEntreeRelation')
                    ->orWhereHas('dossierEntreeRelation', function ($q) {
                        $q->whereNull('qualification_completed_at')
                            ->whereNull('programmes_submitted_at')
                            ->whereNull('qualification_validated_by_agence_at')
                            ->whereNull('qualification_rejected_by_agence_at');
                    });
            }),
            'non_structure' => $query->whereDoesntHave('dossierEntreeRelation', function ($q) {
                $q->whereNotNull('qualification_validated_by_agence_at');
            }),
            default => $query,
        };
    }
}

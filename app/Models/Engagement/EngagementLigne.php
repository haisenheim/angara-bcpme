<?php

namespace App\Models\Engagement;

use App\Models\Banque;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Une ligne de saisie d'engagement : 1 produit, 1 entreprise, 1 partenaire (optionnel).
 *
 * Couvre les colonnes Excel :
 *  - ENCOURS : Initial, Actuel, Remboursement N-1, Retards, Impayés, Statut, Date validité
 *  - SOLLICITÉ : Montant, Date validité
 *  - TOTAL : calculé (encours_actuel + sollicite_montant)
 *  - Métadonnées : commentaire libre, partenaire (banque / EMF / autre).
 *
 * @property int $id
 * @property int $entreprise_id
 * @property int $engagement_categorie_id
 * @property int|null $partenaire_id
 * @property float $encours_initial
 * @property float $encours_actuel
 * @property float $encours_remboursement_n1
 * @property float $encours_retards
 * @property float $encours_impayes
 * @property string|null $encours_statut
 * @property \Carbon\Carbon|null $encours_date_validite
 * @property float $sollicite_montant
 * @property \Carbon\Carbon|null $sollicite_date_validite
 * @property string|null $commentaire
 * @property int|null $created_by_user_id
 * @property int|null $updated_by_user_id
 */
class EngagementLigne extends Model
{
    use HasFactory;

    public const STATUTS = [
        'sain' => 'Sain',
        'performant' => 'Performant',
        'restructure' => 'Restructuré',
        'litige' => 'Litige / contentieux',
        'douteux' => 'Douteux',
        'rembourse' => 'Remboursé',
    ];

    protected $connection = 'central_app_mysql';

    protected $table = 'engagement_lignes';

    protected $guarded = [];

    protected $casts = [
        'encours_initial' => 'float',
        'encours_actuel' => 'float',
        'encours_remboursement_n1' => 'float',
        'encours_retards' => 'float',
        'encours_impayes' => 'float',
        'sollicite_montant' => 'float',
        'encours_date_validite' => 'date',
        'sollicite_date_validite' => 'date',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(EngagementCategorie::class, 'engagement_categorie_id');
    }

    public function partenaire(): BelongsTo
    {
        return $this->belongsTo(Banque::class, 'partenaire_id');
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    public function getTotalMontantAttribute(): float
    {
        return (float) $this->encours_actuel + (float) $this->sollicite_montant;
    }

    public function getVariationAttribute(): float
    {
        return (float) $this->sollicite_montant - (float) $this->encours_actuel;
    }
}

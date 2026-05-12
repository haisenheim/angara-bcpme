<?php

namespace App\Models\Engagement;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Référentiel hiérarchique de la grille « TABLEAU GRILLE TRAITEMENT ENGAGEMENTS ».
 *
 * Quatre niveaux possibles :
 *  - section   : une grande catégorie (Emprunts bancaires, Investissements directs, ...)
 *  - rubrique  : sous-section (Crédits courants en FCFA, Crédits MLT en FCFA, ...)
 *  - nature    : famille intermédiaire (Mobilisation de créances, Facilités de caisse, ...)
 *  - produit   : feuille saisissable (Découvert, Emprunt à moyen terme, ...) ; is_leaf = 1
 *
 * Seuls les nœuds `is_leaf = 1` acceptent des lignes (`engagement_lignes`).
 *
 * @property int $id
 * @property string $code
 * @property string $libelle
 * @property int|null $parent_id
 * @property string $type
 * @property bool $is_leaf
 * @property int $sort_order
 * @property string|null $description
 */
class EngagementCategorie extends Model
{
    use HasFactory;

    public const TYPE_SECTION = 'section';

    public const TYPE_RUBRIQUE = 'rubrique';

    public const TYPE_NATURE = 'nature';

    public const TYPE_PRODUIT = 'produit';

    protected $connection = 'central_app_mysql';

    protected $table = 'engagement_categories';

    protected $guarded = [];

    protected $casts = [
        'is_leaf' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(EngagementLigne::class, 'engagement_categorie_id');
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id')->orderBy('sort_order');
    }

    public function scopeLeaves(Builder $query): Builder
    {
        return $query->where('is_leaf', true);
    }

    /**
     * Renvoie la profondeur (0 = section racine) selon la chaîne `parent_id`.
     */
    public function depth(): int
    {
        $depth = 0;
        $node = $this;
        while ($node->parent_id !== null) {
            $depth++;
            $node = $node->parent;
            if (! $node) {
                break;
            }
        }

        return $depth;
    }
}

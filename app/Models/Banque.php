<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Partenaire financier : banque, établissement de microfinance (EMF) ou autre
 * partenaire (fonds, fonds de garantie, leasing non bancaire, etc.).
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $siege
 * @property string|null $address
 * @property bool $microfinance
 * @property string $kind
 * @property bool $actif
 */
class Banque extends Model
{
    use HasFactory;

    public const KIND_BANQUE = 'banque';

    public const KIND_EMF = 'emf';

    public const KIND_AUTRE = 'autre';

    public const KIND_LABELS = [
        self::KIND_BANQUE => 'Banque',
        self::KIND_EMF => 'Établissement de microfinance',
        self::KIND_AUTRE => 'Autre partenaire financier',
    ];

    protected $connection = 'central_app_mysql';

    protected $table = 'banques';

    protected $guarded = [];

    public $timestamps = true;

    protected $casts = [
        'microfinance' => 'boolean',
        'actif' => 'boolean',
    ];

    /**
     * Libellé localisé du type de partenaire.
     */
    public function getKindLabelAttribute(): string
    {
        return self::KIND_LABELS[$this->kind ?? self::KIND_BANQUE] ?? self::KIND_LABELS[self::KIND_BANQUE];
    }

    public function scopeBanques(Builder $query): Builder
    {
        return $query->where('kind', self::KIND_BANQUE);
    }

    public function scopeEmf(Builder $query): Builder
    {
        return $query->where('kind', self::KIND_EMF);
    }

    public function scopeAutres(Builder $query): Builder
    {
        return $query->where('kind', self::KIND_AUTRE);
    }

    public function scopeActifs(Builder $query): Builder
    {
        return $query->where('actif', true);
    }
}

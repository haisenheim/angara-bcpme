<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_id');
    }

    public function branche(): BelongsTo
    {
        return $this->belongsTo(Branche::class, 'branche_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DossierInstructionProgramme extends Model
{
    protected $connection = 'central_app_mysql';

    protected $table = 'dossier_instruction_programmes';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'budget_appui_financier' => 'decimal:2',
            'budget_appui_non_financier' => 'decimal:2',
        ];
    }

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }
}

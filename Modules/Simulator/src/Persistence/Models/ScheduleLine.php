<?php

namespace Modules\Simulator\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleLine extends Model
{
    protected $table = 'simulator_schedule_lines';

    protected $guarded = [];

    protected $casts = [
        'scenario_id' => 'integer',
        'period_index' => 'integer',
        'period_date' => 'date',
        'capital_due_start' => 'decimal:2',
        'principal_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'insurance_paid' => 'decimal:2',
        'fees_paid' => 'decimal:2',
        'vat_paid' => 'decimal:2',
        'total_payment' => 'decimal:2',
        'capital_due_end' => 'decimal:2',
        'is_deferred' => 'boolean',
    ];

    public function getConnectionName(): string
    {
        return config('simulator.connection', 'central_app_mysql');
    }

    public function scenario(): BelongsTo
    {
        return $this->belongsTo(Scenario::class, 'scenario_id');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $c = 'central_app_mysql';

    public function up(): void
    {
        if (Schema::connection($this->c)->hasTable('simulator_schedule_lines')) {
            return;
        }

        Schema::connection($this->c)->create('simulator_schedule_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scenario_id');
            $table->unsignedInteger('period_index');
            $table->date('period_date')->nullable();
            $table->decimal('capital_due_start', 18, 2);
            $table->decimal('principal_paid', 18, 2);
            $table->decimal('interest_paid', 18, 2);
            $table->decimal('insurance_paid', 18, 2)->default(0);
            $table->decimal('fees_paid', 18, 2)->default(0);
            $table->decimal('vat_paid', 18, 2)->default(0);
            $table->decimal('total_payment', 18, 2);
            $table->decimal('capital_due_end', 18, 2);
            $table->boolean('is_deferred')->default(false);
            $table->timestamps();

            $table->unique(['scenario_id', 'period_index'], 'sim_lines_scenario_period_unique');
            $table->foreign('scenario_id', 'sim_lines_scenario_fk')
                ->references('id')->on('simulator_scenarios')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::connection($this->c)->dropIfExists('simulator_schedule_lines');
    }
};

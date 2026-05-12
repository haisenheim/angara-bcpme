<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $c = 'central_app_mysql';

    public function up(): void
    {
        if (Schema::connection($this->c)->hasTable('simulator_scenarios')) {
            return;
        }

        Schema::connection($this->c)->create('simulator_scenarios', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();
            $table->string('name');

            $table->unsignedInteger('dossier_id')->nullable();
            $table->unsignedBigInteger('dossier_instruction_programme_id')->nullable();

            $table->char('currency', 3)->default('XOF');
            $table->decimal('fx_rate', 18, 8)->nullable();

            $table->decimal('principal', 18, 2);
            $table->decimal('annual_rate', 8, 4);
            $table->unsignedInteger('term_periods');
            $table->string('periodicity', 20);
            $table->string('amortization_type', 20);
            $table->string('deferral_type', 20)->default('none');
            $table->unsignedInteger('deferral_periods')->default(0);
            $table->date('first_period_date')->nullable();

            $table->decimal('dossier_fee_fixed', 18, 2)->default(0);
            $table->decimal('dossier_fee_pct', 8, 4)->default(0);
            $table->decimal('insurance_pct', 8, 4)->default(0);
            $table->string('insurance_basis', 30)->default('outstanding_balance');
            $table->decimal('vat_rate', 8, 4)->default(0);

            $table->decimal('computed_teg', 8, 4)->nullable();
            $table->decimal('total_principal', 18, 2)->default(0);
            $table->decimal('total_interest', 18, 2)->default(0);
            $table->decimal('total_fees', 18, 2)->default(0);
            $table->decimal('total_insurance', 18, 2)->default(0);
            $table->decimal('total_vat', 18, 2)->default(0);
            $table->decimal('total_due', 18, 2)->default(0);
            $table->decimal('first_payment', 18, 2)->default(0);
            $table->decimal('max_payment', 18, 2)->default(0);

            $table->string('status', 20)->default('draft');
            $table->boolean('is_locked')->default(false);
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('submitted_by_user_id')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->unsignedBigInteger('validated_by_user_id')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('rejected_by_user_id')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('dossier_id', 'sim_scenarios_dossier_idx');
            $table->index('dossier_instruction_programme_id', 'sim_scenarios_dip_idx');
            $table->index('status', 'sim_scenarios_status_idx');

            $table->foreign('dossier_id', 'sim_scenarios_dossier_fk')
                ->references('id')->on('dossiers')
                ->nullOnDelete();
            $table->foreign('dossier_instruction_programme_id', 'sim_scenarios_dip_fk')
                ->references('id')->on('dossier_instruction_programmes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::connection($this->c)->dropIfExists('simulator_scenarios');
    }
};

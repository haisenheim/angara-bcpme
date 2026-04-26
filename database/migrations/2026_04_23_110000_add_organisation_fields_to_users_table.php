<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'organisation_type')) {
                // agence | entite
                $table->string('organisation_type', 20)->nullable()->after('agence_id');
            }
            if (! Schema::hasColumn('users', 'organisation_entite_id')) {
                $table->unsignedBigInteger('organisation_entite_id')->nullable()->after('organisation_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = array_values(array_filter([
                Schema::hasColumn('users', 'organisation_type') ? 'organisation_type' : null,
                Schema::hasColumn('users', 'organisation_entite_id') ? 'organisation_entite_id' : null,
            ]));
            if ($cols !== []) {
                $table->dropColumn($cols);
            }
        });
    }
};


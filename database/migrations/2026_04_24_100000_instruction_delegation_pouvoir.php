<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var string */
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        $c = $this->connection;

        Schema::connection($c)->table('dossiers', function (Blueprint $table) use ($c) {
            if (! Schema::connection($c)->hasColumn('dossiers', 'engagements_sollicites_total')) {
                $table->decimal('engagements_sollicites_total', 18, 2)->nullable();
            }
            if (! Schema::connection($c)->hasColumn('dossiers', 'engagements_en_cours_total')) {
                $table->decimal('engagements_en_cours_total', 18, 2)->nullable();
            }
            if (! Schema::connection($c)->hasColumn('dossiers', 'instruction_agence_closing_note')) {
                $table->text('instruction_agence_closing_note')->nullable();
            }
        });

        if (! Schema::connection($c)->hasTable('delegation_pouvoirs')) {
            Schema::connection($c)->create('delegation_pouvoirs', function (Blueprint $table) {
                $table->id();
                $table->decimal('seuil_engagements_max', 18, 2);
                $table->unsignedBigInteger('profil_id');
                $table->timestamps();
                $table->index('seuil_engagements_max');
            });
        }
    }

    public function down(): void
    {
        $c = $this->connection;
        Schema::connection($c)->dropIfExists('delegation_pouvoirs');

        Schema::connection($c)->table('dossiers', function (Blueprint $table) use ($c) {
            foreach (['instruction_agence_closing_note', 'engagements_en_cours_total', 'engagements_sollicites_total'] as $col) {
                if (Schema::connection($c)->hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

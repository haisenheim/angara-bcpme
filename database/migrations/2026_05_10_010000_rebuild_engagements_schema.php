<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Refonte globale de la fonctionnalité « Grille des engagements ».
 *
 * - Supprime l'ancienne implémentation (engagement_entreprises, engagements)
 *   pour repartir d'un schéma propre aligné sur la grille Excel
 *   « TABLEAU GRILLE TRAITEMENT ENGAGEMENTS ».
 * - Étend la table `banques` avec un attribut `kind` (banque / EMF /
 *   autre partenaire financier) afin d'unifier la liste des partenaires.
 * - Crée le référentiel `engagement_categories` (sections, rubriques,
 *   natures, produits) puis la table de saisie `engagement_lignes`
 *   (encours, sollicité, statut, dates, partenaire, commentaire).
 */
return new class extends Migration
{
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        // 1) Détruire l'ancienne implémentation.
        $schema->dropIfExists('engagement_entreprises');
        $schema->dropIfExists('engagements');

        // 2) Étendre les partenaires financiers (banques + EMF + autres).
        if ($schema->hasTable('banques')) {
            $schema->table('banques', function (Blueprint $table) use ($schema) {
                if (! $schema->hasColumn('banques', 'kind')) {
                    $table->enum('kind', ['banque', 'emf', 'autre'])
                        ->default('banque')
                        ->after('microfinance');
                }
                if (! $schema->hasColumn('banques', 'actif')) {
                    $table->boolean('actif')->default(true)->after('kind');
                }
                if (! $schema->hasColumn('banques', 'created_at')) {
                    $table->timestamps();
                }
            });

            DB::connection($this->connection)
                ->table('banques')
                ->where('microfinance', 1)
                ->update(['kind' => 'emf']);
        }

        // 3) Référentiel taxonomique des engagements.
        if (! $schema->hasTable('engagement_categories')) {
            $schema->create('engagement_categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code', 96)->unique();
                $table->string('libelle', 255);
                $table->unsignedBigInteger('parent_id')->nullable()->index();
                $table->enum('type', ['section', 'rubrique', 'nature', 'produit']);
                $table->boolean('is_leaf')->default(false);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('parent_id')
                    ->references('id')->on('engagement_categories')
                    ->onDelete('cascade');
            });
        }

        // 4) Saisies (1 ligne = 1 engagement produit chez 1 partenaire pour 1 entreprise).
        if (! $schema->hasTable('engagement_lignes')) {
            $schema->create('engagement_lignes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('entreprise_id')->index();
                $table->unsignedBigInteger('engagement_categorie_id')->index();
                $table->unsignedInteger('partenaire_id')->nullable()->index();

                // Encours
                $table->decimal('encours_initial', 20, 2)->default(0);
                $table->decimal('encours_actuel', 20, 2)->default(0);
                $table->decimal('encours_remboursement_n1', 20, 2)->default(0);
                $table->decimal('encours_retards', 20, 2)->default(0);
                $table->decimal('encours_impayes', 20, 2)->default(0);
                $table->string('encours_statut', 64)->nullable();
                $table->date('encours_date_validite')->nullable();

                // Sollicité
                $table->decimal('sollicite_montant', 20, 2)->default(0);
                $table->date('sollicite_date_validite')->nullable();

                // Note / commentaire
                $table->text('commentaire')->nullable();

                // Audit
                $table->unsignedBigInteger('created_by_user_id')->nullable()->index();
                $table->unsignedBigInteger('updated_by_user_id')->nullable()->index();

                $table->timestamps();

                $table->foreign('entreprise_id')
                    ->references('id')->on('entreprises')
                    ->onDelete('cascade');
                $table->foreign('engagement_categorie_id')
                    ->references('id')->on('engagement_categories')
                    ->onDelete('cascade');
                $table->foreign('partenaire_id')
                    ->references('id')->on('banques')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        $schema->dropIfExists('engagement_lignes');
        $schema->dropIfExists('engagement_categories');

        if ($schema->hasTable('banques')) {
            $schema->table('banques', function (Blueprint $table) use ($schema) {
                if ($schema->hasColumn('banques', 'kind')) {
                    $table->dropColumn('kind');
                }
                if ($schema->hasColumn('banques', 'actif')) {
                    $table->dropColumn('actif');
                }
                // On ne retire pas les timestamps : peuvent être utilisés
                // par d'autres modules.
            });
        }
    }
};

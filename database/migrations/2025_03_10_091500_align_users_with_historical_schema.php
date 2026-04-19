<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'photo_uri')) {
                $table->string('photo_uri', 100)->nullable()->after('role_id');
            }
            if (! Schema::hasColumn('users', 'agence_id')) {
                $table->integer('agence_id')->default(0)->after('photo_uri');
            }
            if (! Schema::hasColumn('users', 'representation_id')) {
                $table->integer('representation_id')->default(0)->after('agence_id');
            }
            if (! Schema::hasColumn('users', 'programme_id')) {
                $table->integer('programme_id')->default(0)->after('representation_id');
            }
            if (! Schema::hasColumn('users', 'cooperative_id')) {
                $table->integer('cooperative_id')->default(0)->after('programme_id');
            }
            if (! Schema::hasColumn('users', 'secteur_id')) {
                $table->integer('secteur_id')->default(0)->after('cooperative_id');
            }
            if (! Schema::hasColumn('users', 'banque_id')) {
                $table->integer('banque_id')->default(0)->after('secteur_id');
            }
            if (! Schema::hasColumn('users', 'poste_id')) {
                $table->integer('poste_id')->default(0)->after('banque_id');
            }
            if (! Schema::hasColumn('users', 'departement_id')) {
                $table->integer('departement_id')->default(0)->after('poste_id');
            }
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('departement_id');
            }
            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
            if (! Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(true)->after('updated_at');
            }
            if (! Schema::hasColumn('users', 'permissions')) {
                $table->longText('permissions')->nullable()->after('token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = array_values(array_filter([
                Schema::hasColumn('users', 'photo_uri') ? 'photo_uri' : null,
                Schema::hasColumn('users', 'agence_id') ? 'agence_id' : null,
                Schema::hasColumn('users', 'representation_id') ? 'representation_id' : null,
                Schema::hasColumn('users', 'programme_id') ? 'programme_id' : null,
                Schema::hasColumn('users', 'cooperative_id') ? 'cooperative_id' : null,
                Schema::hasColumn('users', 'secteur_id') ? 'secteur_id' : null,
                Schema::hasColumn('users', 'banque_id') ? 'banque_id' : null,
                Schema::hasColumn('users', 'poste_id') ? 'poste_id' : null,
                Schema::hasColumn('users', 'departement_id') ? 'departement_id' : null,
                Schema::hasColumn('users', 'phone') ? 'phone' : null,
                Schema::hasColumn('users', 'email_verified_at') ? 'email_verified_at' : null,
                Schema::hasColumn('users', 'remember_token') ? 'remember_token' : null,
                Schema::hasColumn('users', 'active') ? 'active' : null,
                Schema::hasColumn('users', 'permissions') ? 'permissions' : null,
            ]));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};

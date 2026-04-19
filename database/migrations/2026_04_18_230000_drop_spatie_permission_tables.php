<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['role_id']);
            });
        } catch (\Throwable $e) {
        }

        Schema::disableForeignKeyConstraints();

        foreach ([
            'user_roles',
            'permission_role',
            'role_has_permissions',
            'model_has_roles',
            'model_has_permissions',
            'permissions',
            'roles',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Le module Spatie Permission a ete retire du projet.
    }
};

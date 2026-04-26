<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        Schema::connection($this->connection)->table('fichiers_types', function (Blueprint $table) {
            if (! Schema::connection($this->connection)->hasColumn('fichiers_types', 'active')) {
                $table->boolean('active')->default(true)->after('name');
                $table->index('active');
            }
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('fichiers_types', function (Blueprint $table) {
            if (Schema::connection($this->connection)->hasColumn('fichiers_types', 'active')) {
                $table->dropIndex(['active']);
                $table->dropColumn('active');
            }
        });
    }
};


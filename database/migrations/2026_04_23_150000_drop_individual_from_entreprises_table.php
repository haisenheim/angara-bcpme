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
        if (! Schema::connection($this->connection)->hasTable('entreprises')) {
            return;
        }

        Schema::connection($this->connection)->table('entreprises', function (Blueprint $table) {
            if (Schema::connection($this->connection)->hasColumn('entreprises', 'individual')) {
                $table->dropColumn('individual');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::connection($this->connection)->hasTable('entreprises')) {
            return;
        }

        Schema::connection($this->connection)->table('entreprises', function (Blueprint $table) {
            if (! Schema::connection($this->connection)->hasColumn('entreprises', 'individual')) {
                $table->boolean('individual')->default(false);
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('central_app_mysql')->hasTable('entreprises')) {
            return;
        }

        foreach ($this->nullableStatements() as $statement) {
            DB::connection('central_app_mysql')->statement($statement);
        }
    }

    public function down(): void
    {
        if (! Schema::connection('central_app_mysql')->hasTable('entreprises')) {
            return;
        }

        foreach ($this->rollbackStatements() as $statement) {
            DB::connection('central_app_mysql')->statement($statement);
        }
    }

    /**
     * @return array<int, string>
     */
    private function nullableStatements(): array
    {
        return [
            'ALTER TABLE entreprises MODIFY forme_id INT NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY capital DOUBLE NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY ressources_propres DOUBLE NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY total_actif DOUBLE NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY nb_personnel INT NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY nb_personnel_permanent INT NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY nb_personnel_saisonier INT NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY manager_promoteur TINYINT(1) NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY produit_id INT NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY region_id INT NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY departement_id INT NULL DEFAULT NULL',
            'ALTER TABLE entreprises MODIFY arrondissement_id INT NULL DEFAULT NULL',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function rollbackStatements(): array
    {
        return [
            "ALTER TABLE entreprises MODIFY forme_id INT NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY capital DOUBLE NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY ressources_propres DOUBLE NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY total_actif DOUBLE NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY nb_personnel INT NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY nb_personnel_permanent INT NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY nb_personnel_saisonier INT NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY manager_promoteur TINYINT(1) NOT NULL DEFAULT '1'",
            "ALTER TABLE entreprises MODIFY produit_id INT NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY region_id INT NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY departement_id INT NOT NULL DEFAULT '0'",
            "ALTER TABLE entreprises MODIFY arrondissement_id INT NOT NULL DEFAULT '0'",
        ];
    }
};

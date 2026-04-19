<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Charge les référentiels d'initialisation utiles depuis angara_demo_db.sql.
 *
 * On importe uniquement les tables de paramétrage / référence, et jamais les
 * données transactionnelles (entreprises, dossiers, users, etc.).
 *
 * Idempotent : chaque INSERT du dump est converti en
 * INSERT ... ON DUPLICATE KEY UPDATE.
 */
class BcpmeAngaraDemoReferenceSeeder extends Seeder
{
    protected string $connection = 'central_app_mysql';

    public function run(): void
    {
        $sqlDumpPath = env('BCPME_REFERENCE_DUMP_PATH', base_path('angara_demo_db.sql'));
        if (! is_file($sqlDumpPath)) {
            throw new \RuntimeException("Dump de reference introuvable: {$sqlDumpPath}");
        }

        $sql = file_get_contents($sqlDumpPath);
        if ($sql === false) {
            throw new \RuntimeException("Impossible de lire le dump de reference: {$sqlDumpPath}");
        }

        DB::connection($this->connection)->disableQueryLog();

        $orderedTables = $this->referenceTables();
        $statementsByTable = [];
        foreach ($orderedTables as $table) {
            $statementsByTable[$table] = $this->extractInsertStatements($sql, $table);
        }

        foreach ($orderedTables as $table) {
            $targetTable = $this->resolveExistingTable($table);
            if ($targetTable === null) {
                continue;
            }

            foreach ($statementsByTable[$table] ?? [] as $statement) {
                DB::connection($this->connection)->unprepared(
                    $this->toUpsertStatement($statement, $table, $targetTable)
                );
            }
        }
    }

    /**
     * @return list<string>
     */
    protected function extractInsertStatements(string $sql, string $table): array
    {
        $needle = "INSERT INTO `{$table}` ";
        $offset = 0;
        $statements = [];

        while (($start = strpos($sql, $needle, $offset)) !== false) {
            $end = strpos($sql, ";\n", $start);
            if ($end === false) {
                $end = strpos($sql, ';', $start);
            }
            if ($end === false) {
                throw new \RuntimeException("Fin d'INSERT introuvable pour la table {$table}");
            }

            $statements[] = substr($sql, $start, ($end - $start) + 1);
            $offset = $end + 1;
        }

        return $statements;
    }

    /**
     * Tables de référence uniquement, dans l'ordre des dépendances.
     *
     * @return list<string>
     */
    protected function referenceTables(): array
    {
        return [
            'representations',
            'agences',
            'regions',
            'departements',
            'arrondissements',
            'localites',
            'villages',
            'domaines',
            'gammes',
            'formes_juridiques',
            'entreprise_types',
            'profils',
            'niveaux',
            'tailles',
            'liens',
            'approches',
            'tservices',
            'services',
            'banques',
            'organismes',
            'Torganismes',
            'fichiers_types',
            'elements_constitutifs_types',
            'filieres',
            'produits',
            'criteres',
            'sous_criteres',
            'choices',
            'questions_sous_criteres',
            'questions',
            'questions_choices',
            'sme_notes',
            'indicateurs',
            'programmes',
            'programme_appuis',
            'programme_indicateurs',
            'programme_organismes',
            'programme_produits',
            'composantes',
            'engagements',
        ];
    }

    protected function resolveExistingTable(string $table): ?string
    {
        if (Schema::connection($this->connection)->hasTable($table)) {
            return $table;
        }

        $lower = strtolower($table);
        if ($lower !== $table && Schema::connection($this->connection)->hasTable($lower)) {
            return $lower;
        }

        return null;
    }

    protected function toUpsertStatement(string $statement, string $sourceTable, string $targetTable): string
    {
        preg_match('/INSERT INTO `[^`]+` \((?P<columns>.*?)\) VALUES/s', $statement, $matches);
        $columnBlock = $matches['columns'] ?? null;
        if ($columnBlock === null) {
            throw new \RuntimeException("Impossible d'extraire le bloc colonnes pour la table {$sourceTable}");
        }

        preg_match_all('/`([^`]+)`/', $columnBlock, $columnMatches);
        $columns = $columnMatches[1] ?? [];
        if ($columns === []) {
            throw new \RuntimeException("Impossible d'extraire les colonnes pour la table {$sourceTable}");
        }

        $updateColumns = array_values(array_filter($columns, fn (string $column) => $column !== 'id'));
        if ($updateColumns === []) {
            $updateColumns = ['id'];
        }

        $sql = $statement;
        if ($sourceTable !== $targetTable) {
            $sql = preg_replace(
                '/INSERT INTO `'.preg_quote($sourceTable, '/').'`/',
                'INSERT INTO `'.$targetTable.'`',
                $sql,
                1
            ) ?? $sql;
        }

        $sql = rtrim(trim($sql), ';');
        $updateClause = implode(', ', array_map(
            fn (string $column) => sprintf('`%s` = VALUES(`%s`)', $column, $column),
            $updateColumns
        ));

        return $sql.' ON DUPLICATE KEY UPDATE '.$updateClause.';';
    }
}

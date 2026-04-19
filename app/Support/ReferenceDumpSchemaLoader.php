<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReferenceDumpSchemaLoader
{
    private ?string $sql = null;

    public function __construct(
        private readonly string $connection = 'central_app_mysql',
        private readonly ?string $dumpPath = null,
    ) {
    }

    /**
     * @param  list<string>  $tables
     */
    public function createTables(array $tables): void
    {
        foreach ($tables as $table) {
            if (Schema::connection($this->connection)->hasTable($table)) {
                continue;
            }

            DB::connection($this->connection)->unprepared($this->extractCreateTableStatement($table));

            foreach ($this->extractAlterTableStatements($table) as $statement) {
                DB::connection($this->connection)->unprepared($statement);
            }
        }
    }

    protected function loadSql(): string
    {
        if ($this->sql !== null) {
            return $this->sql;
        }

        $path = $this->dumpPath ?: base_path('angara_demo_db.sql');
        if (! is_file($path)) {
            throw new \RuntimeException("Dump de référence introuvable: {$path}");
        }

        $sql = file_get_contents($path);
        if ($sql === false) {
            throw new \RuntimeException("Impossible de lire le dump de référence: {$path}");
        }

        return $this->sql = $sql;
    }

    protected function extractCreateTableStatement(string $table): string
    {
        $sql = $this->loadSql();
        $start = strpos($sql, "CREATE TABLE `{$table}` (");
        if ($start === false) {
            throw new \RuntimeException("CREATE TABLE introuvable pour {$table}");
        }

        $end = strpos($sql, ";\n", $start);
        if ($end === false) {
            $end = strpos($sql, ';', $start);
        }
        if ($end === false) {
            throw new \RuntimeException("Fin de CREATE TABLE introuvable pour {$table}");
        }

        return $this->sanitizeSql(substr($sql, $start, ($end - $start) + 1));
    }

    /**
     * @return list<string>
     */
    protected function extractAlterTableStatements(string $table): array
    {
        $pattern = '/ALTER TABLE `'.preg_quote($table, '/').'`.*?;/s';
        preg_match_all($pattern, $this->loadSql(), $matches);

        return array_map(fn (string $statement) => $this->sanitizeSql($statement), $matches[0] ?? []);
    }

    protected function sanitizeSql(string $sql): string
    {
        $sql = preg_replace("/timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'/i", "timestamp NULL DEFAULT NULL", $sql) ?? $sql;
        $sql = preg_replace("/datetime NOT NULL DEFAULT '0000-00-00 00:00:00'/i", "datetime NULL DEFAULT NULL", $sql) ?? $sql;
        $sql = preg_replace("/DEFAULT '0000-00-00 00:00:00'/i", 'DEFAULT NULL', $sql) ?? $sql;

        return $sql;
    }
}

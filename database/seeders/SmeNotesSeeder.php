<?php

namespace Database\Seeders;

use App\Models\Instruction\SmeNote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Charge le référentiel notation PME / avis SME depuis sme_notes.sql (racine du projet).
 *
 * Clé métier : colonne note (1–10). Idempotent (updateOrCreate).
 */
class SmeNotesSeeder extends Seeder
{
    public function run(): void
    {
        $path = env('SME_NOTES_SQL_PATH', base_path('sme_notes.sql'));
        if (! is_file($path)) {
            throw new \RuntimeException("Fichier sme_notes.sql introuvable : {$path}");
        }

        $sql = file_get_contents($path);
        if ($sql === false) {
            throw new \RuntimeException("Impossible de lire le fichier : {$path}");
        }

        $rows = $this->parseSmeNotesInsert($sql);

        foreach ($rows as $row) {
            SmeNote::query()->updateOrCreate(
                ['note' => (int) $row['note']],
                [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'mention' => $row['mention'],
                    'created_at' => $row['created_at'] ?? now(),
                    'updated_at' => $row['updated_at'] ?? now(),
                ],
            );
        }
    }

    /**
     * @return list<array{name: string, description: string, mention: string, note: int, created_at?: Carbon, updated_at?: Carbon}>
     */
    protected function parseSmeNotesInsert(string $sql): array
    {
        $statements = $this->extractInsertStatements($sql, 'sme_notes');
        if ($statements === []) {
            throw new \RuntimeException('Aucun INSERT INTO `sme_notes` trouvé dans le fichier SQL.');
        }

        $rows = [];
        foreach ($statements as $statement) {
            if (! preg_match(
                '/INSERT INTO `sme_notes`\s*\((?P<columns>[^)]+)\)\s*VALUES\s*(?P<values>.+)$/is',
                $statement,
                $matches
            )) {
                continue;
            }

            foreach ($this->splitSqlValueTuples($matches['values']) as $tuple) {
                $fields = $this->parseSqlTuple($tuple);
                if (count($fields) < 7) {
                    continue;
                }

                $rows[] = [
                    'name' => $fields[1],
                    'description' => $fields[2],
                    'mention' => $fields[3],
                    'note' => (int) $fields[4],
                    'created_at' => $this->parseSqlTimestamp($fields[5]),
                    'updated_at' => $this->parseSqlTimestamp($fields[6]),
                ];
            }
        }

        if ($rows === []) {
            throw new \RuntimeException('Aucune ligne sme_notes parsée dans le fichier SQL.');
        }

        return $rows;
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
     * @return list<string>
     */
    protected function splitSqlValueTuples(string $valuesBlock): array
    {
        $valuesBlock = trim($valuesBlock);
        $valuesBlock = rtrim($valuesBlock, ';');
        $valuesBlock = trim($valuesBlock);

        $tuples = [];
        $depth = 0;
        $current = '';
        $inString = false;
        $escaped = false;
        $len = strlen($valuesBlock);

        for ($i = 0; $i < $len; $i++) {
            $char = $valuesBlock[$i];

            if ($inString) {
                $current .= $char;
                if ($escaped) {
                    $escaped = false;
                } elseif ($char === '\\') {
                    $escaped = true;
                } elseif ($char === "'") {
                    $inString = false;
                }

                continue;
            }

            if ($char === "'") {
                $inString = true;
                $current .= $char;

                continue;
            }

            if ($char === '(') {
                $depth++;
                $current .= $char;

                continue;
            }

            if ($char === ')') {
                $depth--;
                $current .= $char;
                if ($depth === 0) {
                    $tuples[] = trim($current);
                    $current = '';
                }

                continue;
            }

            if ($depth > 0) {
                $current .= $char;
            }
        }

        return array_values(array_filter($tuples));
    }

    /**
     * @return list<string|int>
     */
    protected function parseSqlTuple(string $tuple): array
    {
        $tuple = trim($tuple);
        if (str_starts_with($tuple, '(')) {
            $tuple = substr($tuple, 1);
        }
        if (str_ends_with($tuple, ')')) {
            $tuple = substr($tuple, 0, -1);
        }

        $fields = [];
        $buffer = '';
        $inString = false;
        $escaped = false;
        $len = strlen($tuple);

        for ($i = 0; $i < $len; $i++) {
            $char = $tuple[$i];

            if ($inString) {
                if ($escaped) {
                    $buffer .= $char;
                    $escaped = false;
                } elseif ($char === '\\') {
                    $escaped = true;
                } elseif ($char === "'") {
                    $inString = false;
                } else {
                    $buffer .= $char;
                }

                continue;
            }

            if ($char === "'") {
                $inString = true;

                continue;
            }

            if ($char === ',' && ! $inString) {
                $fields[] = $this->normalizeSqlField(trim($buffer));
                $buffer = '';

                continue;
            }

            $buffer .= $char;
        }

        if ($buffer !== '' || $fields !== []) {
            $fields[] = $this->normalizeSqlField(trim($buffer));
        }

        return $fields;
    }

    /**
     * @param  string|int  $field
     */
    protected function normalizeSqlField(string|int $field): string|int
    {
        if (! is_string($field)) {
            return $field;
        }

        if ($field === 'NULL') {
            return '';
        }

        if (preg_match('/^-?\d+$/', $field)) {
            return (int) $field;
        }

        return $field;
    }

    protected function parseSqlTimestamp(string|int $value): Carbon
    {
        if (is_int($value)) {
            return Carbon::createFromTimestamp($value);
        }

        return Carbon::parse($value);
    }
}

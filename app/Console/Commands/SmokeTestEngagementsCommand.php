<?php

namespace App\Console\Commands;

use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\Instruction\EngagementEntreprise;
use App\Services\EngagementReportService;
use App\Services\TableDocumentExportService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Throwable;

class SmokeTestEngagementsCommand extends Command
{
    protected $signature = 'smoke:engagements
        {--token= : Token entreprise (sinon auto)}
        {--banque_id= : Banque ID (sinon auto)}
        {--search= : Recherche (sinon auto)}
        {--space=analyste : analyste|analyste-credit (contrôle d’accès seulement)}
        {--user_id= : user id pour analyste-credit (sinon auto)}
    ';

    protected $description = 'Smoke-test engagements: table/stats/export + rendu PDF/Excel (HTML).';

    public function handle(): int
    {
        try {
            $space = (string) $this->option('space');
            if (! in_array($space, ['analyste', 'analyste-credit'], true)) {
                $this->error('Option --space invalide (analyste|analyste-credit).');
                return self::FAILURE;
            }

            /** @var Entreprise|null $entreprise */
            $entreprise = $this->resolveEntreprise((string) $this->option('token'));
            if (! $entreprise) {
                $this->error("Aucune entreprise éligible trouvée (avec des lignes d'engagements).");
                return self::FAILURE;
            }

            if ($space === 'analyste-credit') {
                if (! $this->checkAnalysteCreditAccess($entreprise)) {
                    $this->error("Accès analyste-credit refusé: aucun dossier autorisé pour l'entreprise ".$entreprise->token);
                    return self::FAILURE;
                }
            }

            $banqueId = $this->resolveBanqueId($entreprise->id, $this->option('banque_id'));
            $search = $this->resolveSearch($this->option('search'));

            $this->line('Entreprise: '.$entreprise->name.' (token='.$entreprise->token.', id='.$entreprise->id.')');
            $this->line('Banque filter: '.($banqueId ? (string) $banqueId : 'none'));
            $this->line('Search: '.($search !== '' ? $search : 'none'));
            $this->newLine();

            /** @var EngagementReportService $svc */
            $svc = app(EngagementReportService::class);

            $allRows = $svc->buildUiRowsForEntreprise((int) $entreprise->id, null, '');
            $filteredRows = $svc->buildUiRowsForEntreprise((int) $entreprise->id, $banqueId, $search);
            $stats = $svc->buildUiStatsForEntreprise((int) $entreprise->id, $banqueId, $search);
            $export = $svc->buildExportTableForEntreprise((int) $entreprise->id, $banqueId, $search);

            $this->assertBasicShape($allRows, $filteredRows, $stats, $export);
            $this->assertStatsConsistency($filteredRows, $stats);
            $this->assertFiltersActuallyFilter($entreprise->id, $allRows, $filteredRows, $banqueId, $search);

            $this->assertExportRenders(
                documentTitle: $space === 'analyste'
                    ? 'Analyste — état des engagements'
                    : 'Analyste crédit — état des engagements',
                subtitle: (string) $entreprise->name,
                headers: Arr::get($export, 'headers', []),
                rows: Arr::get($export, 'rows', []),
            );

            $this->info('OK: smoke-test engagements réussi.');
            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('ECHEC: '.$e->getMessage());
            $this->line($e->getTraceAsString());
            return self::FAILURE;
        }
    }

    private function resolveEntreprise(string $token): ?Entreprise
    {
        if ($token !== '') {
            return Entreprise::query()->where('token', $token)->first();
        }

        $entrepriseId = EngagementEntreprise::query()
            ->select('entreprise_id')
            ->whereNotNull('entreprise_id')
            ->orderByDesc('id')
            ->value('entreprise_id');

        if (! $entrepriseId) {
            return null;
        }

        return Entreprise::query()->whereKey($entrepriseId)->first();
    }

    private function resolveBanqueId(int $entrepriseId, mixed $banqueIdOption): ?int
    {
        // If the option is provided explicitly as an empty string, treat it as "no banque filter"
        // and do NOT auto-pick a banque from data.
        if ($banqueIdOption === '') {
            return null;
        }

        if (is_numeric($banqueIdOption) && (int) $banqueIdOption > 0) {
            return (int) $banqueIdOption;
        }

        $banqueId = EngagementEntreprise::query()
            ->where('entreprise_id', $entrepriseId)
            ->orderByDesc('id')
            ->value('banque_id');

        return $banqueId ? (int) $banqueId : null;
    }

    private function resolveSearch(mixed $searchOption): string
    {
        // If the option is provided explicitly as an empty string, treat it as "no search"
        // and do NOT auto-pick a term.
        if ($searchOption === '') {
            return '';
        }

        $raw = is_string($searchOption) ? trim($searchOption) : '';
        if ($raw !== '') {
            return $raw;
        }

        // Try to auto-pick a keyword from an engagement label.
        $name = \App\Models\Instruction\Engagement::query()
            ->whereNotNull('name')
            ->orderByDesc('id')
            ->value('name');

        $name = is_string($name) ? trim($name) : '';
        if ($name === '') {
            return '';
        }

        $parts = preg_split('/\s+/', $name) ?: [];
        $candidate = (string) ($parts[0] ?? '');
        $candidate = Str::limit($candidate, 12, '');

        return trim($candidate);
    }

    private function checkAnalysteCreditAccess(Entreprise $entreprise): bool
    {
        $userId = $this->option('user_id');
        $userId = is_numeric($userId) && (int) $userId > 0
            ? (int) $userId
            : null;

        $q = Dossier::query()
            ->where('entreprise_id', $entreprise->id)
            ->whereNotNull('juridique_submitted_to_engagements_at');

        if ($userId) {
            $q->where('reng_analyste_credit_user_id', $userId);
        }

        return $q->exists();
    }

    /**
     * @param  array<int, array<string, mixed>>  $allRows
     * @param  array<int, array<string, mixed>>  $filteredRows
     * @param  array<string, mixed>  $stats
     * @param  array<string, mixed>  $export
     */
    private function assertBasicShape(array $allRows, array $filteredRows, array $stats, array $export): void
    {
        if (count($allRows) === 0) {
            throw new \RuntimeException("Aucune ligne UI trouvée (buildUiRowsForEntreprise).");
        }
        if (! array_key_exists('encours_montant', $stats) || ! array_key_exists('leaf_count', $stats)) {
            throw new \RuntimeException('Stats UI incomplètes (clés manquantes).');
        }
        if (! is_array(Arr::get($export, 'headers')) || ! is_array(Arr::get($export, 'rows'))) {
            throw new \RuntimeException('Export table invalide (headers/rows manquants).');
        }
        if (count((array) Arr::get($export, 'headers', [])) === 0) {
            throw new \RuntimeException('Export headers vides.');
        }

        $this->info('OK: formes de base (rows/stats/export).');
        $this->line('  - UI rows total: '.count($allRows));
        $this->line('  - UI rows filtered: '.count($filteredRows));
        $this->line('  - Export rows: '.count((array) Arr::get($export, 'rows', [])));
    }

    /**
     * @param  array<int, array<string, mixed>>  $filteredRows
     * @param  array<string, mixed>  $stats
     */
    private function assertStatsConsistency(array $filteredRows, array $stats): void
    {
        $leafRows = array_values(array_filter($filteredRows, fn (array $r) => (bool) ($r['is_leaf'] ?? false)));

        $sumEncours = array_sum(array_map(fn (array $r) => (int) ($r['encours_montant'] ?? 0), $leafRows));
        $sumImpayes = array_sum(array_map(fn (array $r) => (int) ($r['encours_impaye'] ?? 0), $leafRows));
        $sumSollicite = array_sum(array_map(fn (array $r) => (int) ($r['sollicite_montant'] ?? 0), $leafRows));
        $sumVariation = array_sum(array_map(fn (array $r) => (int) ($r['variation'] ?? 0), $leafRows));

        foreach ([
            'encours_montant' => $sumEncours,
            'encours_impaye' => $sumImpayes,
            'sollicite_montant' => $sumSollicite,
            'variation' => $sumVariation,
            'total_montant' => $sumEncours + $sumSollicite,
            'leaf_count' => count($leafRows),
        ] as $k => $expected) {
            $actual = (int) ($stats[$k] ?? -999999);
            if ($actual !== (int) $expected) {
                throw new \RuntimeException("Stats incohérentes: {$k} attendu={$expected} obtenu={$actual}");
            }
        }

        $this->info('OK: cohérence stats (somme feuilles).');
    }

    /**
     * @param  array<int, array<string, mixed>>  $allRows
     * @param  array<int, array<string, mixed>>  $filteredRows
     */
    private function assertFiltersActuallyFilter(int $entrepriseId, array $allRows, array $filteredRows, ?int $banqueId, string $search): void
    {
        if ($banqueId) {
            $hasAnyForBanque = EngagementEntreprise::query()
                ->where('entreprise_id', $entrepriseId)
                ->where('banque_id', $banqueId)
                ->exists();
            if ($hasAnyForBanque && count($filteredRows) > count($allRows)) {
                throw new \RuntimeException('Filtre banque: rows filtered > rows total (impossible).');
            }
        }

        if ($search !== '') {
            // Search is applied on UI rows "name".
            $needle = mb_strtolower($search);
            $violations = array_values(array_filter($filteredRows, function (array $row) use ($needle) {
                $hay = mb_strtolower(trim((string) ($row['name'] ?? '')));
                return $hay === '' || ! str_contains($hay, $needle);
            }));
            if (count($violations) > 0) {
                throw new \RuntimeException('Recherche: certaines lignes filtrées ne matchent pas le terme.');
            }
        }

        $this->info('OK: filtres (banque/recherche) appliqués.');
    }

    /**
     * @param  array<string, string>  $headers
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function assertExportRenders(string $documentTitle, string $subtitle, array $headers, array $rows): void
    {
        $logo = TableDocumentExportService::defaultLogoDataUri();
        if (empty($logo) || ! Str::startsWith((string) $logo, 'data:image/')) {
            throw new \RuntimeException('Logo manquant: defaultLogoDataUri() vide ou invalide.');
        }

        $generatedAt = now()->format('d/m/Y H:i');

        $pdfHtml = view('exports.formatted_table_pdf', [
            'title' => $documentTitle,
            'subtitle' => $subtitle,
            'headers' => $headers,
            'rows' => $rows,
            'logoDataUri' => $logo,
            'generatedAt' => $generatedAt,
        ])->render();

        foreach ([
            $documentTitle,
            $subtitle,
            'Document généré le '.$generatedAt,
            'Page',
            'Angara',
        ] as $needle) {
            if (! Str::contains($pdfHtml, $needle)) {
                throw new \RuntimeException("PDF HTML: marqueur introuvable: {$needle}");
            }
        }

        $xlsxHtml = view('exports.formatted_table_excel', [
            'title' => $documentTitle,
            'subtitle' => $subtitle,
            'headers' => $headers,
            'rows' => $rows,
            'logoDataUri' => $logo,
            'generatedAt' => $generatedAt,
        ])->render();

        foreach ([
            $documentTitle,
            $subtitle,
            'Généré le '.$generatedAt,
        ] as $needle) {
            if (! Str::contains($xlsxHtml, $needle)) {
                throw new \RuntimeException("Excel HTML: marqueur introuvable: {$needle}");
            }
        }

        $this->info('OK: rendu export (PDF/Excel) contient logo/titre/footer/pagination.');
    }
}


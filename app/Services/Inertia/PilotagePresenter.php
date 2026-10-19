<?php

namespace App\Services\Inertia;

use App\Services\TableauDeBordService;
use Illuminate\Support\Carbon;

/**
 * Mise en forme (Inertia) des 5 familles d'indicateurs de TableauDeBordService pour la page Pilotage/Index.
 * Les calculs restent dans le service ; ici uniquement présentation : indicateurs, tableaux, barres.
 */
final class PilotagePresenter
{
    public function __construct(private readonly TableauDeBordService $service) {}

    /**
     * @param  array<string, mixed>  $scope
     * @return array{kpis: array, charts: array, tables: array, notes: array}
     */
    public function famille(string $code, array $scope): array
    {
        return match ($code) {
            'portefeuille' => $this->portefeuille($this->service->portefeuille($scope)),
            'risques' => $this->risques($this->service->risques($scope)),
            'strategique' => $this->strategique($this->service->strategique($scope)),
            'programmes' => $this->programmes($this->service->programmes($scope)),
            default => $this->operationnel($this->service->operationnel($scope)),
        };
    }

    private function operationnel(array $d): array
    {
        $delai = $d['delai_moyen_traitement_jours'];

        return $this->page([
            self::kpi('Dossiers en cours', self::n($d['dossiers_en_cours']), 'info', 'folder_open'),
            self::kpi('En attente validation CA', self::n($d['dossiers_en_attente_validation']), 'warning', 'hourglass_top'),
            self::kpi('Dossiers rejetés', self::n($d['dossiers_rejetes']), 'danger', 'block'),
            self::kpi('Délai moyen (jours)', $delai !== null ? number_format((float) $delai, 1, ',', ' ') : '—', 'neutral', 'schedule'),
        ]);
    }

    private function portefeuille(array $d): array
    {
        $filiere = collect($d['repartition_par_filiere']);
        $secteur = collect($d['repartition_par_secteur']);

        return $this->page(
            [
                self::kpi('Volume crédits sollicités', self::m($d['volume_credits_sollicites']), 'info', 'payments'),
                self::kpi('Encours total', self::m($d['encours_total']), 'success', 'account_balance'),
                self::kpi('Nombre de dossiers', self::n($d['nb_dossiers']), 'neutral', 'folder'),
                self::kpi('Nombre de clients', self::n($d['nb_clients']), 'neutral', 'groups'),
            ],
            [
                self::bars('Volume sollicité par filière', $filiere->map(fn ($r) => [
                    'label' => $r['label'], 'value' => (float) $r['volume'], 'display' => self::m($r['volume']),
                ])->all()),
                self::bars('Clients par secteur', $secteur->map(fn ($r) => [
                    'label' => $r['label'], 'value' => (float) $r['nb_clients'], 'display' => self::n($r['nb_clients']),
                ])->all()),
            ],
            [
                self::table('Répartition par filière', ['Filière', 'Dossiers', 'Volume'], $filiere->map(fn ($r) => [
                    $r['label'], self::n($r['nb_dossiers']), self::m($r['volume']),
                ])->all(), 1),
                self::table('Top clients par encours', ['Client', 'Dossiers', 'Encours'], collect($d['top_clients_par_encours'])->map(fn ($r) => [
                    $r['name'], self::n($r['nb_dossiers']), self::m($r['encours']),
                ])->all(), 1),
            ],
        );
    }

    private function risques(array $d): array
    {
        $alertes = $d['alertes_seuil'] ?? [];

        return $this->page(
            [
                self::kpi("Dossiers à risque (rejets en cours d'instruction)", self::n($d['dossiers_a_risque_en_instruction']), 'warning', 'warning'),
                self::kpi('Rejets de clôture finaux', self::n($d['rejets_clotures_finaux']), 'danger', 'block'),
                self::kpi('Alertes — au-dessus du seuil ('.self::m($alertes['seuil'] ?? null).')', self::n($alertes['dossiers_au_dessus_seuil'] ?? null), 'info', 'notifications'),
            ],
            notes: ["Les indicateurs impayés et créances en souffrance nécessitent un module de gestion des échéances non encore intégré au schéma. Ils s'afficheront dès que les tables de remboursement seront disponibles."],
        );
    }

    private function strategique(array $d): array
    {
        $perf = $d['performance_globale'] ?? [];
        $impact = $d['impact_programmes'] ?? [];
        $croissance = collect($d['croissance_portefeuille'] ?? []);
        $mois = fn (string $ym) => ucfirst(Carbon::createFromFormat('Y-m-d', $ym.'-01')->locale('fr')->translatedFormat('M Y'));

        return $this->page(
            [
                self::kpi('Dossiers ouverts (12 mois)', self::n($perf['dossiers_ouverts_12_mois'] ?? null), 'info', 'trending_up'),
                self::kpi('Dossiers clôturés (12 mois)', self::n($perf['dossiers_clotures_12_mois'] ?? null), 'success', 'task_alt'),
                self::kpi('Taux de clôture', ($perf['taux_cloture'] ?? null) !== null ? number_format((float) $perf['taux_cloture'], 1, ',', ' ').' %' : '—', 'neutral', 'percent'),
            ],
            [
                self::bars('Croissance du portefeuille — dossiers validés par mois', $croissance->map(fn ($r) => [
                    'label' => $mois($r['mois']), 'value' => (float) $r['nb_dossiers'], 'display' => self::n($r['nb_dossiers']),
                ])->all(), 'columns'),
            ],
            [
                self::table('Croissance du portefeuille (12 mois)', ['Mois', 'Dossiers', 'Volume'], $croissance->map(fn ($r) => [
                    $mois($r['mois']), self::n($r['nb_dossiers']), self::m($r['volume']),
                ])->all(), 1),
                self::table('Impact programmes', ['Indicateur', 'Valeur'], [
                    ['Total appui financier', self::m($impact['total_appui_financier'] ?? null)],
                    ['Total appui non financier', self::m($impact['total_appui_non_financier'] ?? null)],
                    ['Dossiers couverts', self::n($impact['nb_dossiers_touches'] ?? 0)],
                ], 1),
            ],
        );
    }

    private function programmes(array $d): array
    {
        $totaux = $d['totaux'] ?? [];
        $rows = collect($d['programmes'] ?? []);

        return $this->page(
            [
                self::kpi('Programmes actifs', self::n($totaux['nb_programmes'] ?? null), 'info', 'apps'),
                self::kpi('Total appui financier', self::m($totaux['total_montant_financier'] ?? null), 'success', 'payments'),
                self::kpi('Total appui non financier', self::m($totaux['total_montant_non_financier'] ?? null), 'neutral', 'handshake'),
            ],
            [
                self::bars('Bénéficiaires par programme', $rows->map(fn ($r) => [
                    'label' => $r['name'], 'value' => (float) $r['nb_beneficiaires'], 'display' => self::n($r['nb_beneficiaires']),
                ])->all()),
            ],
            [
                self::table('Détail par programme', ['Programme', 'Bénéficiaires', 'Appui financier', 'Appui non financier'], $rows->map(fn ($r) => [
                    $r['name'], self::n($r['nb_beneficiaires']), self::m($r['montant_financier']), self::m($r['montant_non_financier']),
                ])->all(), 2),
            ],
        );
    }

    private function page(array $kpis, array $charts = [], array $tables = [], array $notes = []): array
    {
        return compact('kpis', 'charts', 'tables', 'notes');
    }

    private static function kpi(string $label, string $value, string $tone, string $icon): array
    {
        return compact('label', 'value', 'tone', 'icon');
    }

    private static function bars(string $title, array $items, string $layout = 'rows'): array
    {
        return compact('title', 'items', 'layout');
    }

    private static function table(string $title, array $headers, array $rows, int $span): array
    {
        return compact('title', 'headers', 'rows', 'span');
    }

    private static function n(mixed $v): string
    {
        return $v === null || $v === '' ? '—' : number_format((float) $v, 0, ',', ' ');
    }

    private static function m(mixed $v): string
    {
        return $v === null ? '—' : number_format((float) $v, 0, ',', ' ').' FCFA';
    }
}

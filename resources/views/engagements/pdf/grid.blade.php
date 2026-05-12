<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Grille des engagements — {{ $entreprise->name ?? '' }}</title>
    <style>
        @page { margin: 12mm 8mm; }
        * { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1f2937; }
        body { margin: 0; }
        h1 { font-size: 14px; margin: 0 0 4px; }
        h2 { font-size: 11px; margin: 0 0 12px; color: #6b7280; font-weight: normal; }
        .header-table { width: 100%; margin-bottom: 8px; border-collapse: collapse; }
        .header-table td { padding: 0; vertical-align: middle; }
        .logo-cell { width: 90px; }
        .logo-cell img { max-width: 80px; max-height: 50px; }

        table.grid {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        table.grid th, table.grid td {
            border: 1px solid #94a3b8;
            padding: 3px 4px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 8px;
        }
        table.grid thead th {
            background: #1e40af;
            color: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
        }
        table.grid thead th.subhead {
            background: #3b82f6;
            color: #fff;
        }
        td.num { text-align: right; }
        td.center { text-align: center; }

        tr.lvl-section td {
            background: #1e40af;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr.lvl-rubrique td {
            background: #dbeafe;
            color: #1e3a8a;
            font-weight: bold;
        }
        tr.lvl-nature td {
            background: #f1f5f9;
            color: #1f2937;
            font-weight: bold;
        }
        tr.lvl-produit td {
            background: #fff;
        }
        tr.lvl-ligne td {
            background: #f9fafb;
        }

        .indent-1 { padding-left: 12px !important; }
        .indent-2 { padding-left: 22px !important; }
        .indent-3 { padding-left: 32px !important; }
        .indent-4 { padding-left: 42px !important; }

        tfoot td {
            background: #f1f5f9;
            font-weight: bold;
        }
        .footer-meta {
            margin-top: 10px;
            font-size: 8px;
            color: #6b7280;
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
            background: #e5e7eb;
            color: #1f2937;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            @if (! empty($logoDataUri))
                <td class="logo-cell"><img src="{{ $logoDataUri }}" alt=""></td>
            @endif
            <td>
                <h1>Grille des engagements</h1>
                <h2>{{ $entreprise->name ?? '' }} — généré le {{ $generatedAt }}</h2>
            </td>
        </tr>
    </table>

    <table class="grid">
        <thead>
            <tr>
                <th rowspan="2" style="width:24%">Engagement</th>
                <th rowspan="2" style="width:14%">Partenaire</th>
                <th colspan="7">ENCOURS</th>
                <th colspan="2">SOLLICITÉ</th>
                <th rowspan="2" style="width:7%">Total</th>
                <th rowspan="2" style="width:8%">Commentaire</th>
            </tr>
            <tr>
                <th class="subhead">Initial</th>
                <th class="subhead">Actuel</th>
                <th class="subhead">Remb. N-1</th>
                <th class="subhead">Retards</th>
                <th class="subhead">Impayés</th>
                <th class="subhead">Statut</th>
                <th class="subhead">Date</th>
                <th class="subhead">Montant</th>
                <th class="subhead">Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $renderNode = null;
                $renderNode = function (array $node, int $depth) use (&$renderNode) {
                    $type = $node['type'] ?? 'produit';
                    $indentClass = 'indent-'.min(4, max(1, $depth + 1));
                    $rowClass = match ($type) {
                        'section' => 'lvl-section',
                        'rubrique' => 'lvl-rubrique',
                        'nature' => 'lvl-nature',
                        default => 'lvl-produit',
                    };
                    $fmt = fn ($v) => number_format((float) ($v ?? 0), 0, ',', ' ');
                    echo '<tr class="'.$rowClass.'">';
                    echo '<td class="'.$indentClass.'">'.e($node['libelle']).'</td>';
                    echo '<td>—</td>';
                    echo '<td class="num">'.$fmt($node['encours_initial']).'</td>';
                    echo '<td class="num">'.$fmt($node['encours_actuel']).'</td>';
                    echo '<td class="num">'.$fmt($node['encours_remboursement_n1']).'</td>';
                    echo '<td class="num">'.$fmt($node['encours_retards']).'</td>';
                    echo '<td class="num">'.$fmt($node['encours_impayes']).'</td>';
                    echo '<td class="center">—</td>';
                    echo '<td class="center">—</td>';
                    echo '<td class="num">'.$fmt($node['sollicite_montant']).'</td>';
                    echo '<td class="center">—</td>';
                    echo '<td class="num">'.$fmt($node['total_montant']).'</td>';
                    echo '<td></td>';
                    echo '</tr>';

                    if (($node['is_leaf'] ?? false) === true) {
                        foreach ($node['lignes'] ?? [] as $ligne) {
                            $statut = $ligne['encours_statut_label'] ?? '—';
                            echo '<tr class="lvl-ligne">';
                            echo '<td class="indent-4">↳ Saisie #'.$ligne['id'].'</td>';
                            echo '<td>'.e($ligne['partenaire_nom'] ?? '—');
                            if (! empty($ligne['partenaire_kind_label'])) {
                                echo ' <span class="badge">'.e($ligne['partenaire_kind_label']).'</span>';
                            }
                            echo '</td>';
                            echo '<td class="num">'.$fmt($ligne['encours_initial']).'</td>';
                            echo '<td class="num">'.$fmt($ligne['encours_actuel']).'</td>';
                            echo '<td class="num">'.$fmt($ligne['encours_remboursement_n1']).'</td>';
                            echo '<td class="num">'.$fmt($ligne['encours_retards']).'</td>';
                            echo '<td class="num">'.$fmt($ligne['encours_impayes']).'</td>';
                            echo '<td class="center">'.e($statut).'</td>';
                            echo '<td class="center">'.e($ligne['encours_date_validite'] ?? '—').'</td>';
                            echo '<td class="num">'.$fmt($ligne['sollicite_montant']).'</td>';
                            echo '<td class="center">'.e($ligne['sollicite_date_validite'] ?? '—').'</td>';
                            echo '<td class="num">'.$fmt($ligne['total_montant']).'</td>';
                            echo '<td>'.e(mb_strimwidth((string) ($ligne['commentaire'] ?? ''), 0, 80, '…')).'</td>';
                            echo '</tr>';
                        }
                    }

                    foreach ($node['children'] ?? [] as $child) {
                        $renderNode($child, $depth + 1);
                    }
                };
            @endphp

            @forelse ($tree as $root)
                @php $renderNode($root, 0); @endphp
            @empty
                <tr><td colspan="13" class="center">Aucune saisie pour cette entreprise.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL ENTREPRISE</td>
                <td class="num">{{ number_format((float) ($stats['encours_initial'] ?? 0), 0, ',', ' ') }}</td>
                <td class="num">{{ number_format((float) ($stats['encours_actuel'] ?? 0), 0, ',', ' ') }}</td>
                <td class="num">{{ number_format((float) ($stats['encours_remboursement_n1'] ?? 0), 0, ',', ' ') }}</td>
                <td class="num">{{ number_format((float) ($stats['encours_retards'] ?? 0), 0, ',', ' ') }}</td>
                <td class="num">{{ number_format((float) ($stats['encours_impayes'] ?? 0), 0, ',', ' ') }}</td>
                <td colspan="2" class="center">—</td>
                <td class="num">{{ number_format((float) ($stats['sollicite_montant'] ?? 0), 0, ',', ' ') }}</td>
                <td class="center">—</td>
                <td class="num">{{ number_format((float) ($stats['total_montant'] ?? 0), 0, ',', ' ') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <p class="footer-meta">BC-PME — Angara · {{ $stats['nb_lignes'] ?? 0 }} ligne(s) saisie(s)</p>
</body>
</html>

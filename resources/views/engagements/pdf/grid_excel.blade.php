<table>
    <thead>
        <tr>
            <th colspan="13" style="background:#1e40af;color:#fff;font-weight:bold;font-size:14px;text-align:left;">
                Grille des engagements — {{ $entreprise->name ?? '' }}
            </th>
        </tr>
        <tr>
            <th colspan="13" style="font-size:9px;color:#6b7280;text-align:left;">
                Généré le {{ $generatedAt }} · {{ $stats['nb_lignes'] ?? 0 }} ligne(s)
            </th>
        </tr>
        <tr>
            <th rowspan="2" style="background:#1e40af;color:#fff;font-weight:bold;">Engagement</th>
            <th rowspan="2" style="background:#1e40af;color:#fff;font-weight:bold;">Partenaire</th>
            <th colspan="7" style="background:#1e40af;color:#fff;font-weight:bold;text-align:center;">ENCOURS</th>
            <th colspan="2" style="background:#1e40af;color:#fff;font-weight:bold;text-align:center;">SOLLICITÉ</th>
            <th rowspan="2" style="background:#1e40af;color:#fff;font-weight:bold;">Total</th>
            <th rowspan="2" style="background:#1e40af;color:#fff;font-weight:bold;">Commentaire</th>
        </tr>
        <tr>
            <th style="background:#3b82f6;color:#fff;">Initial</th>
            <th style="background:#3b82f6;color:#fff;">Actuel</th>
            <th style="background:#3b82f6;color:#fff;">Remb. N-1</th>
            <th style="background:#3b82f6;color:#fff;">Retards</th>
            <th style="background:#3b82f6;color:#fff;">Impayés</th>
            <th style="background:#3b82f6;color:#fff;">Statut</th>
            <th style="background:#3b82f6;color:#fff;">Date</th>
            <th style="background:#3b82f6;color:#fff;">Montant</th>
            <th style="background:#3b82f6;color:#fff;">Date</th>
        </tr>
    </thead>
    <tbody>
        @php
            $bg = [
                'section' => '#1e40af',
                'rubrique' => '#dbeafe',
                'nature' => '#f1f5f9',
                'produit' => '#ffffff',
            ];
            $fg = [
                'section' => '#ffffff',
                'rubrique' => '#1e3a8a',
                'nature' => '#1f2937',
                'produit' => '#1f2937',
            ];

            $renderNode = null;
            $renderNode = function (array $node, int $depth) use (&$renderNode, $bg, $fg) {
                $type = $node['type'] ?? 'produit';
                $background = $bg[$type] ?? '#fff';
                $color = $fg[$type] ?? '#1f2937';
                $weight = $type === 'produit' ? 'normal' : 'bold';
                $indent = str_repeat('  ', max(0, $depth));

                echo '<tr style="background:'.$background.';color:'.$color.';font-weight:'.$weight.';">';
                echo '<td>'.e($indent.$node['libelle']).'</td>';
                echo '<td>—</td>';
                echo '<td>'.(float) ($node['encours_initial'] ?? 0).'</td>';
                echo '<td>'.(float) ($node['encours_actuel'] ?? 0).'</td>';
                echo '<td>'.(float) ($node['encours_remboursement_n1'] ?? 0).'</td>';
                echo '<td>'.(float) ($node['encours_retards'] ?? 0).'</td>';
                echo '<td>'.(float) ($node['encours_impayes'] ?? 0).'</td>';
                echo '<td>—</td>';
                echo '<td>—</td>';
                echo '<td>'.(float) ($node['sollicite_montant'] ?? 0).'</td>';
                echo '<td>—</td>';
                echo '<td>'.(float) ($node['total_montant'] ?? 0).'</td>';
                echo '<td></td>';
                echo '</tr>';

                if (($node['is_leaf'] ?? false) === true) {
                    foreach ($node['lignes'] ?? [] as $ligne) {
                        $partenaire = $ligne['partenaire_nom'] ?? '—';
                        if (! empty($ligne['partenaire_kind_label'])) {
                            $partenaire .= ' ('.$ligne['partenaire_kind_label'].')';
                        }
                        echo '<tr style="background:#f9fafb;">';
                        echo '<td>'.e('    ↳ saisie #'.$ligne['id']).'</td>';
                        echo '<td>'.e($partenaire).'</td>';
                        echo '<td>'.(float) ($ligne['encours_initial'] ?? 0).'</td>';
                        echo '<td>'.(float) ($ligne['encours_actuel'] ?? 0).'</td>';
                        echo '<td>'.(float) ($ligne['encours_remboursement_n1'] ?? 0).'</td>';
                        echo '<td>'.(float) ($ligne['encours_retards'] ?? 0).'</td>';
                        echo '<td>'.(float) ($ligne['encours_impayes'] ?? 0).'</td>';
                        echo '<td>'.e($ligne['encours_statut_label'] ?? '—').'</td>';
                        echo '<td>'.e($ligne['encours_date_validite'] ?? '—').'</td>';
                        echo '<td>'.(float) ($ligne['sollicite_montant'] ?? 0).'</td>';
                        echo '<td>'.e($ligne['sollicite_date_validite'] ?? '—').'</td>';
                        echo '<td>'.(float) ($ligne['total_montant'] ?? 0).'</td>';
                        echo '<td>'.e((string) ($ligne['commentaire'] ?? '')).'</td>';
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
            <tr><td colspan="13" style="text-align:center;">Aucune saisie pour cette entreprise.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="background:#f1f5f9;font-weight:bold;">
            <td colspan="2">TOTAL ENTREPRISE</td>
            <td>{{ (float) ($stats['encours_initial'] ?? 0) }}</td>
            <td>{{ (float) ($stats['encours_actuel'] ?? 0) }}</td>
            <td>{{ (float) ($stats['encours_remboursement_n1'] ?? 0) }}</td>
            <td>{{ (float) ($stats['encours_retards'] ?? 0) }}</td>
            <td>{{ (float) ($stats['encours_impayes'] ?? 0) }}</td>
            <td colspan="2">—</td>
            <td>{{ (float) ($stats['sollicite_montant'] ?? 0) }}</td>
            <td>—</td>
            <td>{{ (float) ($stats['total_montant'] ?? 0) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

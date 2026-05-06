<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dossier d’analyse critique — {{ $item->programmesLabel() ?: 'Dossier' }}</title>
    <style>
        @page {
            margin: 28mm 18mm 22mm 18mm;
        }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            color: #1a1a1a;
            line-height: 1.45;
            margin: 0;
        }
        .pdf-header {
            border-bottom: 2px solid #0d47a1;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .pdf-header-table { width: 100%; border-collapse: collapse; }
        .pdf-header-table td { vertical-align: middle; }
        .pdf-logo { max-height: 42px; }
        .pdf-title {
            font-size: 14pt;
            font-weight: bold;
            color: #0d47a1;
            margin: 0 0 4px 0;
        }
        .pdf-sub {
            font-size: 9pt;
            color: #555;
            margin: 0;
        }
        .pdf-meta {
            background: #f5f7fa;
            border: 1px solid #dde3ea;
            padding: 10px 12px;
            margin-bottom: 16px;
            font-size: 9pt;
        }
        .pdf-meta strong { color: #333; }
        .pdf-zone {
            page-break-inside: avoid;
            margin-bottom: 12px;
            border: 1px solid #cfd8dc;
            border-radius: 4px;
            overflow: hidden;
        }
        .pdf-zone-head {
            background: #eceff1;
            padding: 8px 10px;
            border-bottom: 1px solid #cfd8dc;
            font-size: 9pt;
        }
        .pdf-zone-title { font-weight: bold; color: #1a237e; }
        .pdf-zone-body {
            font-size: 9.5pt;
            padding: 10px;
            background: #fafbfc;
        }
        .pdf-zone-body p { margin: 0 0 6px 0; }
        .pdf-zone-body p:last-child { margin-bottom: 0; }
        .pdf-empty { color: #888; font-style: italic; font-size: 9pt; }
        .pdf-footer-note {
            font-size: 8pt;
            color: #666;
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }
        .pdf-section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #0d47a1;
            margin: 16px 0 10px 0;
            border-bottom: 1px solid #0d47a1;
            padding-bottom: 4px;
        }
        .pdf-legacy {
            border: 1px solid #cfd8dc;
            padding: 10px;
            background: #fffef7;
            font-size: 9pt;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="pdf-header">
        <table class="pdf-header-table">
            <tr>
                <td style="width: 38%;">
                    @if($logoData !== '')
                        <img class="pdf-logo" src="data:image/png;base64,{{ $logoData }}" alt="BC-PME">
                    @else
                        <strong style="color:#0d47a1;">BC-PME</strong>
                    @endif
                </td>
                <td style="text-align: right;">
                    <p class="pdf-title">Dossier d’analyse critique</p>
                    <p class="pdf-sub">Avis du chargé d’instruction — rubriques séparées</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="pdf-meta">
        <strong>Entreprise :</strong> {{ $item->entreprise?->name ?? '—' }}<br>
        <strong>Programme(s) :</strong> {{ $item->programmesLabel() }}<br>
        <strong>Réf. dossier :</strong> {{ $item->token }}<br>
        <strong>Document généré le :</strong> {{ $generatedAt->format('d/m/Y à H:i') }}
    </div>

    @php
        $item->loadMissing('analyste');
        $titreAnalyseCritiquePdf = 'Analyse critique faite par '.($item->analyste?->name ?? 'l’analyste financier (non renseigné)');
        $zones = $item->exploitationAfInstructionZonesForDisplay();
        $anyZone = collect($zones)->contains(fn ($z) => $z['filled']);
        $legacy = (string) ($item->exploitation_analyste_instruction_avis ?? '');
        $legacyFilled = strlen(trim(strip_tags($legacy))) > 0;
    @endphp
    <p class="pdf-section-title">{{ $titreAnalyseCritiquePdf }}</p>
    @foreach($zones as $zone)
        <div class="pdf-zone">
            <div class="pdf-zone-head">
                <span class="pdf-zone-title">{{ $zone['label'] }}</span>
            </div>
            <div class="pdf-zone-body rich-text-rendered">
                @if($zone['filled'])
                    {!! $zone['html'] !!}
                @else
                    <p class="pdf-empty">Non renseigné.</p>
                @endif
            </div>
        </div>
    @endforeach

    @if(! $anyZone && $legacyFilled)
        <div class="pdf-legacy rich-text-rendered">
            <strong>Contenu consolidé (historique ou synchronisation)</strong><br><br>
            {!! $legacy !!}
        </div>
    @elseif(! $anyZone && ! $legacyFilled)
        <p class="pdf-empty">Aucune rubrique renseignée.</p>
    @endif

    <div class="pdf-footer-note">
        Document confidentiel — BC-PME. Reproduction interdite sans autorisation.
    </div>
</body>
</html>

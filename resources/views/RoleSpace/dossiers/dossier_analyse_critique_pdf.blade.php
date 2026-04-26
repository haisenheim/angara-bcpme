<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dossier d’analyse critique — {{ $item->programme?->name ?? 'Dossier' }}</title>
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
        .entry {
            page-break-inside: avoid;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .entry:last-child { border-bottom: none; }
        .entry-date {
            font-size: 8.5pt;
            color: #fff;
            background: #37474f;
            padding: 2px 8px;
            display: inline-block;
            margin-bottom: 6px;
        }
        .entry-label { font-weight: bold; font-size: 10.5pt; margin-bottom: 4px; color: #0d47a1; }
        .entry-author {
            font-size: 9pt;
            color: #444;
            margin-bottom: 8px;
        }
        .entry-body {
            font-size: 9.5pt;
            border: 1px solid #e3e8ef;
            padding: 10px;
            background: #fafbfc;
        }
        .entry-body p { margin: 0 0 6px 0; }
        .entry-body p:last-child { margin-bottom: 0; }
        .pdf-footer-note {
            font-size: 8pt;
            color: #666;
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }
        .pdf-pieces {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }
        .pdf-pieces-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0d47a1;
            margin: 0 0 8px 0;
        }
        .pdf-pieces-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        .pdf-pieces-table th,
        .pdf-pieces-table td {
            border: 1px solid #cfd8dc;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }
        .pdf-pieces-table th {
            background: #eceff1;
            font-weight: bold;
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
                    <p class="pdf-sub">Banque Camerounaise des PME — Angara</p>
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

    <div class="pdf-pieces">
        <p class="pdf-pieces-title">Pièces jointes au dossier</p>
        @if($item->fichiersDossier->isEmpty())
            <p style="color:#666; font-size:9pt; margin:0;">Aucune pièce n’a encore été déposée sur ce dossier.</p>
        @else
            <table class="pdf-pieces-table">
                <thead>
                    <tr>
                        <th style="width:18%;">Type</th>
                        <th style="width:32%;">Fichier</th>
                        <th style="width:20%;">Date et heure</th>
                        <th style="width:30%;">Déposé par</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->fichiersDossier as $f)
                        <tr>
                            <td>{{ $f->type?->name ?? '—' }}</td>
                            <td>{{ $f->original_name ?: ($f->name ? basename($f->name) : '—') }}</td>
                            <td>{{ $f->uploaded_at ? $f->uploaded_at->format('d/m/Y H:i') : '—' }}</td>
                            <td>{{ $f->uploadedBy?->name ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @forelse($entries as $entry)
        <div class="entry">
            <div class="entry-date">{{ $entry['at']->format('d/m/Y H:i') }}</div>
            <div class="entry-label">{{ $entry['label'] }}</div>
            <div class="entry-author">
                <strong>{{ $entry['author_name'] }}</strong> — {{ $entry['author_profile'] }}
                @if(($entry['origin'] ?? '') === 'analyse_critique')
                    (Analyse critique entreprise)
                @endif
            </div>
            <div class="entry-body rich-text-rendered">
                {!! $entry['body_html'] !!}
            </div>
        </div>
    @empty
        <p style="color:#666;">Aucun avis enregistré dans la chronologie pour ce dossier.</p>
    @endforelse

    <div class="pdf-footer-note">
        Document confidentiel — BC-PME. Reproduction interdite sans autorisation.
    </div>
</body>
</html>

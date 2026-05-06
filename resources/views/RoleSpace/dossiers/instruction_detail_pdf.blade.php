<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Dossier d’instruction — {{ $item->entreprise?->name ?? '—' }}</title>
    <style>
        @page { margin: 18mm 14mm 18mm 14mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; color: #111; }
        .muted { color: #666; }
        .small { font-size: 8.5pt; }
        .title { font-size: 16pt; font-weight: 700; margin: 0; }
        .subtitle { font-size: 10pt; margin: 2px 0 0 0; color: #444; }
        .hr { height: 1px; background: #e5e5e5; margin: 10px 0 12px 0; }
        .hdr-table { width: 100%; border-collapse: collapse; }
        .hdr-table td { vertical-align: top; }
        .logo { max-height: 44px; }
        .zone { border: 1px solid #e6e6e6; border-radius: 6px; padding: 10px; margin: 0 0 10px 0; }
        .zone-title { font-weight: 700; color: #1a237e; margin: 0 0 6px 0; }
        .meta { width: 100%; border-collapse: collapse; }
        .meta td { padding: 2px 0; }
        .meta strong { color: #333; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #e6e6e6; padding: 6px 7px; vertical-align: top; }
        .table th { background: #f7f7f9; text-align: left; font-size: 9pt; }
        .table .zebra:nth-child(even) td { background: #fcfcfd; }
        .rich-text-rendered p { margin: 0 0 6px 0; }
        .rich-text-rendered p:last-child { margin-bottom: 0; }
        .rich-text-rendered ul, .rich-text-rendered ol { margin: 0 0 6px 18px; padding: 0; }
        .empty { color: #888; font-style: italic; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
@php
    $fmtMoney = static fn ($v) => number_format((float) $v, 0, ',', ' ') . ' XAF';
    $fmtDateTime = static fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d/m/Y H:i') : '—';
@endphp

<table class="hdr-table">
    <tr>
        <td style="width: 35%;">
            @if(!empty($logoData))
                <img class="logo" src="data:image/png;base64,{{ $logoData }}" alt="BCPME">
            @else
                <div class="muted small">BCPME</div>
            @endif
        </td>
        <td style="width: 65%; text-align: right;">
            <p class="title">Dossier d’instruction — complet</p>
            <p class="subtitle">{{ $item->entreprise?->name ?? '—' }} — {{ $item->programmesLabel() ?: '—' }}</p>
            <div class="muted small" style="margin-top: 6px;">
                Édité le {{ $generatedAt->format('d/m/Y H:i') }} — Réf: {{ $item->token }}
            </div>
        </td>
    </tr>
</table>

<div class="hr"></div>

<div class="zone">
    <p class="zone-title">Références & contexte</p>
    <table class="meta">
        <tr>
            <td><strong>Client</strong> : {{ $item->entreprise?->name ?? '—' }}</td>
            <td><strong>Agence</strong> : {{ $item->agence?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Gestionnaire</strong> : {{ $item->gestionnaire?->name ?? '—' }}</td>
            <td><strong>Analyste</strong> : {{ $item->analyste?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Programmes</strong> : {{ $item->programmesLabel() ?: '—' }}</td>
            <td><strong>Créé le</strong> : {{ $fmtDateTime($item->created_at) }}</td>
        </tr>
    </table>
</div>

<div class="zone">
    <p class="zone-title">Budgets par programme</p>
    @if(($instructionConsultation['has_budget_rows'] ?? false) && !empty($instructionConsultation['lignes']))
        <table class="table">
            <thead>
            <tr>
                <th>Programme</th>
                <th style="width: 20%;">Appui financier</th>
                <th style="width: 20%;">Appui non financier</th>
                <th style="width: 20%;">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach(($instructionConsultation['lignes'] ?? []) as $row)
                <tr class="zebra">
                    <td>{{ $row['programme_name'] ?? '—' }}</td>
                    <td>{{ $fmtMoney($row['financier'] ?? 0) }}</td>
                    <td>{{ $fmtMoney($row['non_financier'] ?? 0) }}</td>
                    <td><strong>{{ $fmtMoney($row['total_ligne'] ?? 0) }}</strong></td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{ $fmtMoney($instructionConsultation['totaux']['financier'] ?? 0) }}</strong></td>
                <td><strong>{{ $fmtMoney($instructionConsultation['totaux']['non_financier'] ?? 0) }}</strong></td>
                <td><strong>{{ $fmtMoney($instructionConsultation['totaux']['general'] ?? 0) }}</strong></td>
            </tr>
            </tbody>
        </table>
    @else
        <div class="empty">Aucune ligne budget renseignée.</div>
    @endif
</div>

<div class="zone">
    <p class="zone-title">Pièces jointes du dossier</p>
    @php $files = $item->fichiersDossier ?? collect(); @endphp
    @if($files->count())
        <table class="table">
            <thead>
            <tr>
                <th>Type</th>
                <th>Nom</th>
                <th style="width: 18%;">Déposé le</th>
                <th style="width: 20%;">Déposé par</th>
            </tr>
            </thead>
            <tbody>
            @foreach($files as $f)
                <tr class="zebra">
                    <td>{{ $f->type?->name ?? '—' }}</td>
                    <td>{{ $f->name ?? $f->original_name ?? '—' }}</td>
                    <td>{{ $fmtDateTime($f->created_at ?? null) }}</td>
                    <td>{{ $f->uploadedBy?->name ?? '—' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">Aucune pièce jointe.</div>
    @endif
</div>

<div class="zone">
    <p class="zone-title">Chronologie (workflow + avis)</p>
    @php $timeline = $instructionConsultation['timeline'] ?? collect(); @endphp
    @if($timeline instanceof \Illuminate\Support\Collection && $timeline->count())
        <table class="table">
            <thead>
            <tr>
                <th style="width: 18%;">Date</th>
                <th style="width: 22%;">Acteur</th>
                <th style="width: 18%;">Rôle</th>
                <th>Évènement / avis</th>
            </tr>
            </thead>
            <tbody>
            @foreach($timeline as $t)
                @php $actor = $t['actor'] ?? null; @endphp
                <tr class="zebra">
                    <td>{{ $fmtDateTime($t['at'] ?? null) }}</td>
                    <td>{{ $actor?->name ?? '—' }}</td>
                    <td>{{ $t['actor_role'] ?? '—' }}</td>
                    <td class="rich-text-rendered">
                        <strong>{{ $t['label'] ?? '—' }}</strong>
                        @if(!empty($t['body_html']))
                            <div style="margin-top: 4px;">{!! $t['body_html'] !!}</div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">Aucun évènement disponible.</div>
    @endif
</div>

<div class="page-break"></div>

<div class="zone">
    <p class="zone-title">Grille de notation (détail)</p>
    @if(!empty($criteres) && is_array($criteres))
        @foreach($criteres as $crit)
            <div style="margin: 0 0 10px 0; padding: 8px; border: 1px solid #eee; border-radius: 6px;">
                <div style="display:flex; justify-content: space-between;">
                    <div><strong>{{ $crit['name'] ?? '—' }}</strong></div>
                    <div class="muted small">Note: {{ $crit['note'] ?? 0 }}</div>
                </div>
                @php $sous = $crit['souscriteres'] ?? []; @endphp
                @if(is_array($sous) && count($sous))
                    <table class="table" style="margin-top: 6px;">
                        <thead>
                        <tr>
                            <th>Sous-critère</th>
                            <th style="width: 20%;">Choix</th>
                            <th style="width: 16%;">Note</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sous as $sc)
                            @php
                                $rep = $sc['reponse'] ?? [];
                                $choice = $rep['choice']['valeur'] ?? null;
                                $note = $rep['note'] ?? ($sc['note'] ?? 0);
                            @endphp
                            <tr class="zebra">
                                <td>{{ $sc['name'] ?? '—' }}</td>
                                <td>{{ $choice ?? '—' }}</td>
                                <td>{{ $note }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty">Aucun sous-critère.</div>
                @endif
            </div>
        @endforeach
    @else
        <div class="empty">Grille indisponible.</div>
    @endif
</div>

@if($item->isInstructionSubmittedToExploitation() && $item->hasExploitationAnalysteInstructionAvisSubstance())
    <div class="zone">
        <p class="zone-title">Analyse critique (rubriques) — analyste financier</p>
        @include('partials.exploitation-analyste-instruction-zones', [
            'dossier' => $item,
            'showSectionTitle' => false,
            'showEmptyZones' => true,
        ])
    </div>
@endif

@if($item->isInstructionCaTransmittedToExploitation() && strlen(trim(strip_tags((string) ($item->instruction_agence_ca_avis ?? '')))) > 0)
    <div class="zone">
        <p class="zone-title">Avis du chef d’agence</p>
        <div class="rich-text-rendered">{!! $item->instruction_agence_ca_avis !!}</div>
    </div>
@endif

</body>
</html>


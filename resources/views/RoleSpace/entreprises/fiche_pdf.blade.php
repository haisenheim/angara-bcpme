<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Fiche {{ $item->prospect ? 'Prospect' : 'Client' }} — {{ $item->name }}</title>
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
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8.5pt; font-weight: 700; }
        .badge-prospect { background: #fff3cd; color: #7a5b00; border: 1px solid #ffe69c; }
        .badge-client { background: #d1e7dd; color: #0f5132; border: 1px solid #a3cfbb; }
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
    </style>
</head>
<body>
@php
    $fmtDateTime = static fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d/m/Y H:i') : '—';
    $fmtDate = static fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d/m/Y') : '—';
    $checkFournies = $checklist->filter(fn ($x) => (bool) ($x['fourni'] ?? false))->values();
    $checkNonFournies = $checklist->filter(fn ($x) => ! (bool) ($x['fourni'] ?? false))->values();
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
            <p class="title">Fiche {{ $item->prospect ? 'Prospect' : 'Client' }}</p>
            <p class="subtitle">{{ $item->name }}</p>
            <div style="margin-top: 6px;">
                @if($item->prospect)
                    <span class="badge badge-prospect">Prospect</span>
                @else
                    <span class="badge badge-client">Client</span>
                @endif
            </div>
        </td>
    </tr>
</table>

<div class="hr"></div>

<div class="zone">
    <p class="zone-title">Références & contexte</p>
    <table class="meta">
        <tr>
            <td><strong>Token</strong> : {{ $item->token }}</td>
            <td><strong>Édité le</strong> : {{ $generatedAt->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Agence</strong> : {{ $item->agence?->name ?? '—' }}</td>
            <td><strong>Gestionnaire</strong> : {{ $item->gestionnaire?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>RCCM</strong> : {{ $item->rccm ?? '—' }}</td>
            <td><strong>NIU</strong> : {{ $item->niu ?? '—' }}</td>
        </tr>
    </table>
</div>

<div class="zone">
    <p class="zone-title">Identification entreprise</p>
    <table class="meta">
        <tr>
            <td><strong>Forme juridique</strong> : {{ $item->forme?->name ?? '—' }}</td>
            <td><strong>Activité</strong> : {{ $item->produit?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Date création</strong> : {{ $fmtDate($item->dt_creation) }}</td>
            <td><strong>Date début activités</strong> : {{ $fmtDate($item->dt_start) }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Localisation</strong> : {{ $item->region?->name ?? '—' }} / {{ $item->departement?->name ?? '—' }} / {{ $item->arrondissement?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Quartier / Village</strong> : {{ $item->village_ou_quartier ?? (trim(($item->village?->name ?? '').' '.($item->quartier?->name ?? '')) ?: '—') }}</td>
        </tr>
    </table>
</div>

<div class="zone">
    <p class="zone-title">Coordonnées</p>
    <table class="meta">
        <tr>
            <td><strong>Téléphone</strong> : {{ $item->phone ?? '—' }}</td>
            <td><strong>Email</strong> : {{ $item->email ?? '—' }}</td>
        </tr>
        <tr>
            <td><strong>Mobile Money</strong> : {{ $item->mm_phone ?? '—' }}</td>
            <td><strong>Dirigeant</strong> : {{ $item->manager ?? '—' }}</td>
        </tr>
    </table>
</div>

<div class="zone">
    <p class="zone-title">Avis responsables (juridique & conformité)</p>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 16%;">Type</th>
                <th style="width: 14%;">Date</th>
                <th style="width: 20%;">Auteur</th>
                <th>Contenu</th>
            </tr>
        </thead>
        <tbody>
            <tr class="zebra">
                <td><strong>Juridique</strong></td>
                <td>{{ $fmtDateTime($item->juridique_avis_at) }}</td>
                <td>{{ $item->juridiqueAvisUser?->name ?? '—' }}</td>
                <td class="rich-text-rendered">{!! $item->juridique_avis ?: '<span class="empty">Non renseigné.</span>' !!}</td>
            </tr>
            <tr class="zebra">
                <td><strong>Conformité</strong></td>
                <td>{{ $fmtDateTime($item->conformite_avis_at) }}</td>
                <td>{{ $item->conformiteAvisUser?->name ?? '—' }}</td>
                <td class="rich-text-rendered">{!! $item->conformite_avis ?: '<span class="empty">Non renseigné.</span>' !!}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="zone">
    <p class="zone-title">Sites</p>
    @if($item->sites && $item->sites->count())
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 22%;">Libellé</th>
                    <th style="width: 18%;">Région</th>
                    <th style="width: 20%;">Département</th>
                    <th style="width: 20%;">Arrondissement</th>
                    <th>Adresse</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->sites as $s)
                    <tr class="zebra">
                        <td>{{ $s->libelle ?? '—' }}</td>
                        <td>{{ $s->region?->name ?? '—' }}</td>
                        <td>{{ $s->departement?->name ?? '—' }}</td>
                        <td>{{ $s->arrondissement?->name ?? '—' }}</td>
                        <td>{{ $s->adresse ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">Aucun site renseigné.</div>
    @endif
</div>

<div class="zone">
    <p class="zone-title">Équipe</p>
    @if($item->equipeMembres && $item->equipeMembres->count())
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 28%;">Nom & prénom</th>
                    <th style="width: 22%;">Fonction</th>
                    <th style="width: 18%;">Téléphone</th>
                    <th style="width: 22%;">Email</th>
                    <th style="width: 10%;">Site</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->equipeMembres as $m)
                    <tr class="zebra">
                        <td>{{ trim(($m->nom ?? '').' '.($m->prenom ?? '')) ?: '—' }}</td>
                        <td>{{ $m->fonction ?? '—' }}</td>
                        <td>{{ $m->telephone ?? '—' }}</td>
                        <td>{{ $m->email ?? '—' }}</td>
                        <td>{{ $m->site?->libelle ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">Aucun membre d’équipe renseigné.</div>
    @endif
</div>

<div class="zone">
    <p class="zone-title">Avis du gestionnaire par critère (questionnaire d’entrée en relation)</p>
    @if($mr && $mr->count())
        @foreach($mr as $critereId => $block)
            @php
                $critere = $block['critere'] ?? null;
                $critereLabel = $critere?->name ?? ('Critère '.$critereId);
                $avisRow = $item->critereAvis?->firstWhere('critere_id', (int) $critereId);
            @endphp
            <div style="margin: 0 0 10px 0; padding: 8px; border: 1px solid #eee; border-radius: 6px;">
                <div style="display:flex; justify-content: space-between;">
                    <div><strong>{{ $critereLabel }}</strong></div>
                    <div class="muted small">Dernière saisie : {{ $fmtDateTime($avisRow?->saved_at) }}</div>
                </div>
                <div class="rich-text-rendered" style="margin-top: 6px;">
                    {!! $avisRow?->avis ?: '<span class="empty">Non renseigné.</span>' !!}
                </div>
            </div>
        @endforeach
    @else
        <div class="empty">Aucun critère détecté pour ce questionnaire.</div>
    @endif
</div>

<div class="zone">
    <p class="zone-title">Pièces exigibles</p>
    <table class="table">
        <thead>
        <tr>
            <th style="width: 50%;">Pièces fournies ({{ $checkFournies->count() }})</th>
            <th>Pièces non fournies ({{ $checkNonFournies->count() }})</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                @if($checkFournies->count())
                    <ul style="margin: 0 0 0 18px; padding: 0;">
                        @foreach($checkFournies as $row)
                            <li>{{ $row['definition']?->name ?? '—' }}</li>
                        @endforeach
                    </ul>
                @else
                    <span class="empty">Aucune pièce fournie.</span>
                @endif
            </td>
            <td>
                @if($checkNonFournies->count())
                    <ul style="margin: 0 0 0 18px; padding: 0;">
                        @foreach($checkNonFournies as $row)
                            <li>{{ $row['definition']?->name ?? '—' }}</li>
                        @endforeach
                    </ul>
                @else
                    <span class="empty">Aucune pièce manquante.</span>
                @endif
            </td>
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>


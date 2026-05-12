<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $scenario->name ?? 'Simulation de credit' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        h1 { font-size: 18px; margin: 0 0 4px 0; }
        h2 { font-size: 13px; margin: 14px 0 6px; border-bottom: 1px solid #999; padding-bottom: 2px; }
        .subtitle { color: #666; font-size: 11px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        th, td { border: 1px solid #ccc; padding: 3px 4px; }
        th { background: #f3f3f3; font-weight: 700; }
        .amount { text-align: right; }
        .params td { padding: 4px 6px; }
        .params td.label { color: #666; width: 35%; }
        .totals { margin-top: 8px; }
        .totals td { padding: 3px 6px; }
        .totals td.label { color: #666; }
        .deferred { background: #fff7d6; }
    </style>
</head>
<body>
    <h1>{{ $scenario->name ?? 'Simulation de credit' }}</h1>
    <div class="subtitle">
        Genere le {{ now()->format('Y-m-d H:i') }}
        @if ($scenario)
            &middot; Token : {{ $scenario->token }}
        @endif
    </div>

    <h2>Parametres</h2>
    @php $i = $result->input; @endphp
    <table class="params">
        <tr>
            <td class="label">Capital</td>
            <td>{{ number_format($i->principal, 2, ',', ' ') }} {{ $i->currency }}</td>
            <td class="label">Taux annuel</td>
            <td>{{ number_format($i->annualRate, 4, ',', ' ') }} %</td>
        </tr>
        <tr>
            <td class="label">Duree</td>
            <td>{{ $i->termPeriods }} periodes ({{ $i->periodicity->label() }})</td>
            <td class="label">Type d'amortissement</td>
            <td>{{ $i->amortizationType->label() }}</td>
        </tr>
        <tr>
            <td class="label">Differe</td>
            <td>{{ $i->deferralType->label() }} ({{ $i->deferralPeriods }})</td>
            <td class="label">Premiere echeance</td>
            <td>{{ $i->firstPeriodDate?->format('Y-m-d') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Frais de dossier</td>
            <td>{{ number_format($i->dossierFeeFixed, 2, ',', ' ') }} + {{ number_format($i->dossierFeePct, 4, ',', ' ') }} %</td>
            <td class="label">Assurance</td>
            <td>{{ number_format($i->insurancePct, 4, ',', ' ') }} % {{ $i->insuranceBasis->label() }}</td>
        </tr>
        <tr>
            <td class="label">TVA / TAF</td>
            <td>{{ number_format($i->vatRate, 4, ',', ' ') }} %</td>
            <td class="label">TEG calcule</td>
            <td>{{ $result->computedTeg !== null ? number_format($result->computedTeg, 4, ',', ' ').' %' : '-' }}</td>
        </tr>
    </table>

    <h2>Echeancier</h2>
    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Date</th>
                <th class="amount">Capital debut</th>
                <th class="amount">Capital rembourse</th>
                <th class="amount">Interets</th>
                <th class="amount">Assurance</th>
                <th class="amount">Frais</th>
                <th class="amount">TVA</th>
                <th class="amount">Echeance</th>
                <th class="amount">Capital fin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result->lines as $line)
                <tr class="{{ $line->isDeferred ? 'deferred' : '' }}">
                    <td>{{ $line->periodIndex }}</td>
                    <td>{{ $line->periodDate?->format('Y-m-d') ?? '-' }}</td>
                    <td class="amount">{{ number_format($line->capitalDueStart, 2, ',', ' ') }}</td>
                    <td class="amount">{{ number_format($line->principalPaid, 2, ',', ' ') }}</td>
                    <td class="amount">{{ number_format($line->interestPaid, 2, ',', ' ') }}</td>
                    <td class="amount">{{ number_format($line->insurancePaid, 2, ',', ' ') }}</td>
                    <td class="amount">{{ number_format($line->feesPaid, 2, ',', ' ') }}</td>
                    <td class="amount">{{ number_format($line->vatPaid, 2, ',', ' ') }}</td>
                    <td class="amount"><strong>{{ number_format($line->totalPayment, 2, ',', ' ') }}</strong></td>
                    <td class="amount">{{ number_format($line->capitalDueEnd, 2, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Totaux</h2>
    <table class="totals">
        <tr>
            <td class="label">Capital rembourse</td>
            <td class="amount">{{ number_format($result->totalPrincipal, 2, ',', ' ') }} {{ $i->currency }}</td>
            <td class="label">Total interets</td>
            <td class="amount">{{ number_format($result->totalInterest, 2, ',', ' ') }} {{ $i->currency }}</td>
        </tr>
        <tr>
            <td class="label">Frais de dossier</td>
            <td class="amount">{{ number_format($result->totalFees, 2, ',', ' ') }} {{ $i->currency }}</td>
            <td class="label">Assurance</td>
            <td class="amount">{{ number_format($result->totalInsurance, 2, ',', ' ') }} {{ $i->currency }}</td>
        </tr>
        <tr>
            <td class="label">TVA</td>
            <td class="amount">{{ number_format($result->totalVat, 2, ',', ' ') }} {{ $i->currency }}</td>
            <td class="label"><strong>Total a rembourser</strong></td>
            <td class="amount"><strong>{{ number_format($result->totalDue, 2, ',', ' ') }} {{ $i->currency }}</strong></td>
        </tr>
    </table>
</body>
</html>

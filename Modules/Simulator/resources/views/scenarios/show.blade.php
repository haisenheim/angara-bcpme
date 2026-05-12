@extends($simulatorLayout ?? 'Layouts.app')

@section('title', $scenario->name)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('simulator::simulator.breadcrumb.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('simulator.index') }}">{{ __('simulator::simulator.breadcrumb.simulator') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('simulator.scenarios.index') }}">{{ __('simulator::simulator.breadcrumb.scenarios') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $scenario->name }}</li>
        </ol>
    </nav>
@endsection

@section('actions')
    <a href="{{ route('simulator.scenarios.pdf', $scenario) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-file-pdf me-1"></i> {{ __('simulator::simulator.actions.export_pdf') }}
    </a>
    <a href="{{ route('simulator.scenarios.xlsx', $scenario) }}" class="btn btn-outline-success btn-sm">
        <i class="bi bi-file-earmark-excel me-1"></i> {{ __('simulator::simulator.actions.export_xlsx') }}
    </a>
@endsection

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>{{ $scenario->name }}</strong>
                    @php
                        $statusClass = match ($scenario->status) {
                            'draft' => 'text-bg-secondary-subtle',
                            'submitted' => 'text-bg-info',
                            'validated' => 'text-bg-success',
                            'rejected' => 'text-bg-danger',
                            default => 'text-bg-secondary-subtle',
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ __('simulator::simulator.workflow.status.'.$scenario->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="text-muted small">{{ __('simulator::simulator.fields.principal') }}</div>
                            <div class="fw-semibold">{{ number_format((float) $scenario->principal, 2, ',', ' ') }} {{ $scenario->currency }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted small">{{ __('simulator::simulator.fields.annual_rate') }}</div>
                            <div class="fw-semibold">{{ number_format((float) $scenario->annual_rate, 4, ',', ' ') }} %</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted small">{{ __('simulator::simulator.fields.term_periods') }}</div>
                            <div class="fw-semibold">{{ $scenario->term_periods }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted small">{{ __('simulator::simulator.fields.periodicity') }}</div>
                            <div class="fw-semibold">{{ $scenario->periodicityEnum()->label() }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted small">{{ __('simulator::simulator.fields.amortization_type') }}</div>
                            <div class="fw-semibold">{{ $scenario->amortizationTypeEnum()->label() }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted small">{{ __('simulator::simulator.fields.deferral_type') }}</div>
                            <div class="fw-semibold">{{ $scenario->deferralTypeEnum()->label() }} ({{ $scenario->deferral_periods }})</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted small">{{ __('simulator::simulator.kpis.computed_teg') }}</div>
                            <div class="fw-semibold">{{ $scenario->computed_teg !== null ? number_format((float) $scenario->computed_teg, 4, ',', ' ').' %' : '-' }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-muted small">{{ __('simulator::simulator.kpis.total_due') }}</div>
                            <div class="fw-semibold">{{ number_format((float) $scenario->total_due, 2, ',', ' ') }} {{ $scenario->currency }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><strong>Workflow</strong></div>
                <div class="card-body">
                    <ul class="list-unstyled mb-3">
                        <li><span class="text-muted">Cree le :</span> {{ $scenario->created_at?->format('Y-m-d H:i') }}</li>
                        @if ($scenario->submitted_at)
                            <li><span class="text-muted">Soumis le :</span> {{ $scenario->submitted_at->format('Y-m-d H:i') }}</li>
                        @endif
                        @if ($scenario->validated_at)
                            <li><span class="text-muted">Valide le :</span> {{ $scenario->validated_at->format('Y-m-d H:i') }}</li>
                        @endif
                        @if ($scenario->rejected_at)
                            <li><span class="text-muted">Rejete le :</span> {{ $scenario->rejected_at->format('Y-m-d H:i') }}</li>
                            @if ($scenario->rejection_reason)
                                <li class="text-danger">Motif : {{ $scenario->rejection_reason }}</li>
                            @endif
                        @endif
                    </ul>

                    <div class="d-grid gap-2">
                        @if ($scenario->status === 'draft')
                            <form action="{{ route('simulator.scenarios.submit', $scenario) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info w-100">{{ __('simulator::simulator.actions.submit') }}</button>
                            </form>
                        @elseif ($scenario->status === 'submitted')
                            <form action="{{ route('simulator.scenarios.validate', $scenario) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">{{ __('simulator::simulator.actions.validate') }}</button>
                            </form>
                            <form action="{{ route('simulator.scenarios.reject', $scenario) }}" method="POST" class="mt-2">
                                @csrf
                                <textarea name="reason" class="form-control form-control-sm mb-2" placeholder="Motif du rejet" required></textarea>
                                <button type="submit" class="btn btn-outline-danger w-100">{{ __('simulator::simulator.actions.reject') }}</button>
                            </form>
                        @endif

                        @unless ($scenario->is_locked)
                            <form action="{{ route('simulator.scenarios.detach', $scenario) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100">{{ __('simulator::simulator.actions.detach') }}</button>
                            </form>
                            <form action="{{ route('simulator.scenarios.destroy', $scenario) }}" method="POST" onsubmit="return confirm('Supprimer ce scenario ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">{{ __('simulator::simulator.actions.delete') }}</button>
                            </form>
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Echeancier</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 70vh;">
                <table class="table table-sm table-hover sim-schedule mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>{{ __('simulator::simulator.schedule.period') }}</th>
                            <th>{{ __('simulator::simulator.schedule.date') }}</th>
                            <th class="amount">{{ __('simulator::simulator.schedule.capital_due_start') }}</th>
                            <th class="amount">{{ __('simulator::simulator.schedule.principal_paid') }}</th>
                            <th class="amount">{{ __('simulator::simulator.schedule.interest_paid') }}</th>
                            <th class="amount">{{ __('simulator::simulator.schedule.insurance_paid') }}</th>
                            <th class="amount">{{ __('simulator::simulator.schedule.fees_paid') }}</th>
                            <th class="amount">{{ __('simulator::simulator.schedule.vat_paid') }}</th>
                            <th class="amount">{{ __('simulator::simulator.schedule.total_payment') }}</th>
                            <th class="amount">{{ __('simulator::simulator.schedule.capital_due_end') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($scenario->lines as $l)
                            <tr class="{{ $l->is_deferred ? 'deferred' : '' }}">
                                <td>{{ $l->period_index }}</td>
                                <td>{{ $l->period_date?->format('Y-m-d') ?? '-' }}</td>
                                <td class="amount">{{ number_format((float) $l->capital_due_start, 2, ',', ' ') }}</td>
                                <td class="amount">{{ number_format((float) $l->principal_paid, 2, ',', ' ') }}</td>
                                <td class="amount">{{ number_format((float) $l->interest_paid, 2, ',', ' ') }}</td>
                                <td class="amount">{{ number_format((float) $l->insurance_paid, 2, ',', ' ') }}</td>
                                <td class="amount">{{ number_format((float) $l->fees_paid, 2, ',', ' ') }}</td>
                                <td class="amount">{{ number_format((float) $l->vat_paid, 2, ',', ' ') }}</td>
                                <td class="amount fw-semibold">{{ number_format((float) $l->total_payment, 2, ',', ' ') }}</td>
                                <td class="amount">{{ number_format((float) $l->capital_due_end, 2, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .sim-schedule { font-size: .85rem; }
            .sim-schedule td.amount, .sim-schedule th.amount { text-align: right; font-variant-numeric: tabular-nums; }
            .sim-schedule tbody tr.deferred { background: var(--bs-warning-bg-subtle); }
        </style>
    @endpush
@endsection

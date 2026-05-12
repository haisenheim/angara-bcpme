@extends($simulatorLayout ?? 'Layouts.app')

@section('title', __('simulator::simulator.title'))

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('simulator::simulator.breadcrumb.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('simulator::simulator.breadcrumb.simulator') }}</li>
        </ol>
    </nav>
@endsection

@section('actions')
    <a href="{{ route('simulator.scenarios.index') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-list-ul me-1"></i> {{ __('simulator::simulator.breadcrumb.scenarios') }}
    </a>
@endsection

@section('page-header')
    <div class="mt-3">
        <h1 class="h3 mb-1">{{ __('simulator::simulator.title') }}</h1>
        <p class="text-muted mb-0">{{ __('simulator::simulator.subtitle') }}</p>
    </div>
@endsection

@section('content')
    @php
        $preselected = $preselected ?? [];
    @endphp
    <div id="simulator-app"
         data-simulate-url="{{ route('simulator.api.simulate') }}"
         data-persist-url="{{ route('simulator.api.persist') }}"
         data-pdf-url="{{ route('simulator.api.export.pdf') }}"
         data-xlsx-url="{{ route('simulator.api.export.xlsx') }}"
         data-csrf="{{ csrf_token() }}"
         data-usury="{{ $usury_rate_warning }}">

        <div class="row g-3">
            <div class="col-12 col-xl-5">
                <div class="card">
                    <div class="card-header">
                        <strong>{{ __('simulator::simulator.actions.simulate') }}</strong>
                    </div>
                    <div class="card-body">
                        <form id="simulator-form" autocomplete="off">
                            @csrf
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label">{{ __('simulator::simulator.fields.principal') }}</label>
                                    <input type="number" step="any" min="1" class="form-control" name="principal"
                                           value="{{ $preselected['principal'] ?? 10000000 }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.annual_rate') }}</label>
                                    <input type="number" step="any" min="0" class="form-control" name="annual_rate" value="8" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.term_periods') }}</label>
                                    <input type="number" min="1" class="form-control" name="term_periods" value="60" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.periodicity') }}</label>
                                    <select class="form-select" name="periodicity">
                                        @foreach ($periodicities as $p)
                                            <option value="{{ $p->value }}" @selected($p->value === 'monthly')>{{ $p->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.amortization_type') }}</label>
                                    <select class="form-select" name="amortization_type">
                                        @foreach ($amortization_types as $a)
                                            <option value="{{ $a->value }}">{{ $a->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.deferral_type') }}</label>
                                    <select class="form-select" name="deferral_type">
                                        @foreach ($deferral_types as $d)
                                            <option value="{{ $d->value }}">{{ $d->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.deferral_periods') }}</label>
                                    <input type="number" min="0" class="form-control" name="deferral_periods" value="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.currency') }}</label>
                                    <select class="form-select" name="currency">
                                        @foreach ($currencies as $code => $info)
                                            <option value="{{ $code }}" @selected($code === $default_currency)>{{ $info['label'] ?? $code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.first_period_date') }}</label>
                                    <input type="date" class="form-control" name="first_period_date" value="{{ now()->addMonth()->format('Y-m-d') }}">
                                </div>

                                <div class="col-12"><hr class="my-2"></div>

                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.dossier_fee_fixed') }}</label>
                                    <input type="number" step="any" min="0" class="form-control" name="dossier_fee_fixed" value="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.dossier_fee_pct') }}</label>
                                    <input type="number" step="any" min="0" max="100" class="form-control" name="dossier_fee_pct" value="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.insurance_pct') }}</label>
                                    <input type="number" step="any" min="0" max="100" class="form-control" name="insurance_pct" value="0">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.insurance_basis') }}</label>
                                    <select class="form-select" name="insurance_basis">
                                        @foreach ($insurance_bases as $ib)
                                            <option value="{{ $ib->value }}">{{ $ib->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">{{ __('simulator::simulator.fields.vat_rate') }}</label>
                                    <input type="number" step="any" min="0" max="100" class="form-control" name="vat_rate" value="0">
                                </div>

                                @if (! empty($preselected['dossier_id']))
                                    <input type="hidden" name="dossier_id" value="{{ $preselected['dossier_id'] }}">
                                @endif
                                @if (! empty($preselected['dossier_instruction_programme_id']))
                                    <input type="hidden" name="dossier_instruction_programme_id" value="{{ $preselected['dossier_instruction_programme_id'] }}">
                                @endif
                            </div>

                            <div class="alert alert-warning d-none mt-3" id="simulator-usury-warning"></div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-calculator me-1"></i> {{ __('simulator::simulator.actions.simulate') }}
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    {{ __('simulator::simulator.actions.reset') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-7">
                <div class="card mb-3 d-none" id="simulator-result-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>{{ __('simulator::simulator.kpis.total_due') }}</strong>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" id="simulator-export-pdf">
                                <i class="bi bi-file-pdf me-1"></i> {{ __('simulator::simulator.actions.export_pdf') }}
                            </button>
                            <button type="button" class="btn btn-outline-success" id="simulator-export-xlsx">
                                <i class="bi bi-file-earmark-excel me-1"></i> {{ __('simulator::simulator.actions.export_xlsx') }}
                            </button>
                            <button type="button" class="btn btn-primary" id="simulator-persist" data-bs-toggle="modal" data-bs-target="#simulator-persist-modal">
                                <i class="bi bi-save me-1"></i> {{ __('simulator::simulator.actions.persist') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('simulator::partials.kpi-cards')
                        @include('simulator::partials.schedule-table')
                    </div>
                </div>

                <div class="card d-none" id="simulator-empty-state">
                    <div class="card-body text-center text-muted py-5">
                        <i class="bi bi-calculator-fill display-4 d-block mb-2"></i>
                        <p class="mb-0">Saisissez les parametres et lancez la simulation pour generer l'echeancier.</p>
                    </div>
                </div>
            </div>
        </div>

        @include('simulator::partials.persist-modal')
    </div>

    @push('styles')
        <style>
            .sim-kpi { padding: .75rem 1rem; border: 1px solid var(--bs-border-color); border-radius: .5rem; background: var(--bs-tertiary-bg); }
            .sim-kpi .label { font-size: .8rem; color: var(--bs-secondary-color); }
            .sim-kpi .value { font-size: 1.1rem; font-weight: 600; }
            .sim-schedule { font-size: .85rem; }
            .sim-schedule td.amount, .sim-schedule th.amount { text-align: right; font-variant-numeric: tabular-nums; }
            .sim-schedule tbody tr.deferred { background: var(--bs-warning-bg-subtle); }
        </style>
    @endpush

    @push('scripts')
        <script>
            (function () {
                const root = document.getElementById('simulator-app');
                if (!root) return;
                const form = document.getElementById('simulator-form');
                const resultCard = document.getElementById('simulator-result-card');
                const emptyState = document.getElementById('simulator-empty-state');
                emptyState.classList.remove('d-none');

                const csrf = root.dataset.csrf;
                const simulateUrl = root.dataset.simulateUrl;
                const persistUrl = root.dataset.persistUrl;
                const pdfUrl = root.dataset.pdfUrl;
                const xlsxUrl = root.dataset.xlsxUrl;
                const usury = parseFloat(root.dataset.usury || '0');

                let lastInput = null;
                let lastResult = null;

                function readForm() {
                    const fd = new FormData(form);
                    const data = {};
                    fd.forEach((v, k) => {
                        if (v === '' || v === null) return;
                        data[k] = v;
                    });
                    return data;
                }

                function fmt(n) {
                    if (n === null || n === undefined) return '-';
                    const num = Number(n);
                    if (Number.isNaN(num)) return '-';
                    return num.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }

                function showWarning(rate) {
                    const w = document.getElementById('simulator-usury-warning');
                    if (usury > 0 && rate > usury) {
                        w.textContent = `Le taux saisi (${rate}%) depasse le seuil d'usure indicatif (${usury}%).`;
                        w.classList.remove('d-none');
                    } else {
                        w.classList.add('d-none');
                    }
                }

                function renderKpis(t) {
                    document.querySelector('[data-kpi="first_payment"]').textContent = fmt(t.first_payment);
                    document.querySelector('[data-kpi="max_payment"]').textContent = fmt(t.max_payment);
                    document.querySelector('[data-kpi="total_interest"]').textContent = fmt(t.interest);
                    document.querySelector('[data-kpi="total_fees"]').textContent = fmt(t.fees);
                    document.querySelector('[data-kpi="total_insurance"]').textContent = fmt(t.insurance);
                    document.querySelector('[data-kpi="total_vat"]').textContent = fmt(t.vat);
                    document.querySelector('[data-kpi="total_due"]').textContent = fmt(t.total_due);
                    document.querySelector('[data-kpi="computed_teg"]').textContent = t.computed_teg !== null && t.computed_teg !== undefined
                        ? Number(t.computed_teg).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 4 })
                        : '-';
                }

                function renderSchedule(lines) {
                    const tbody = document.querySelector('#simulator-schedule tbody');
                    tbody.innerHTML = '';
                    lines.forEach(l => {
                        const tr = document.createElement('tr');
                        if (l.is_deferred) tr.classList.add('deferred');
                        tr.innerHTML = `
                            <td>${l.period_index}</td>
                            <td>${l.period_date ?? '-'}</td>
                            <td class="amount">${fmt(l.capital_due_start)}</td>
                            <td class="amount">${fmt(l.principal_paid)}</td>
                            <td class="amount">${fmt(l.interest_paid)}</td>
                            <td class="amount">${fmt(l.insurance_paid)}</td>
                            <td class="amount">${fmt(l.fees_paid)}</td>
                            <td class="amount">${fmt(l.vat_paid)}</td>
                            <td class="amount fw-semibold">${fmt(l.total_payment)}</td>
                            <td class="amount">${fmt(l.capital_due_end)}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }

                async function postJson(url, payload, expectFile = false) {
                    const fd = new FormData();
                    Object.entries(payload).forEach(([k, v]) => fd.append(k, v));
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': expectFile ? '*/*' : 'application/json' },
                        body: fd,
                    });
                    if (!res.ok) {
                        let msg = 'Erreur lors de la requete';
                        try { const j = await res.json(); msg = j.message || JSON.stringify(j.errors || j); } catch (e) {}
                        throw new Error(msg);
                    }
                    return expectFile ? res.blob() : res.json();
                }

                form.addEventListener('submit', async (ev) => {
                    ev.preventDefault();
                    const payload = readForm();
                    showWarning(parseFloat(payload.annual_rate || 0));
                    try {
                        const result = await postJson(simulateUrl, payload);
                        lastInput = payload;
                        lastResult = result;
                        emptyState.classList.add('d-none');
                        resultCard.classList.remove('d-none');
                        renderKpis(result.totals);
                        renderSchedule(result.lines);
                    } catch (e) {
                        alert(e.message);
                    }
                });

                async function exportFile(url, filename) {
                    if (!lastInput) return;
                    try {
                        const blob = await postJson(url, lastInput, true);
                        const a = document.createElement('a');
                        a.href = URL.createObjectURL(blob);
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    } catch (e) { alert(e.message); }
                }

                document.getElementById('simulator-export-pdf').addEventListener('click', () => exportFile(pdfUrl, 'simulation-credit.pdf'));
                document.getElementById('simulator-export-xlsx').addEventListener('click', () => exportFile(xlsxUrl, 'simulation-credit.xlsx'));

                document.getElementById('simulator-persist-form').addEventListener('submit', async (ev) => {
                    ev.preventDefault();
                    if (!lastInput) return;
                    const modalForm = ev.target;
                    const payload = Object.assign({}, lastInput);
                    payload.name = modalForm.querySelector('[name=name]').value;
                    const target = modalForm.querySelector('[name=attach_target]:checked')?.value;
                    if (target === 'dossier') {
                        payload.dossier_id = modalForm.querySelector('[name=dossier_id]').value;
                        delete payload.dossier_instruction_programme_id;
                    } else if (target === 'programme') {
                        payload.dossier_instruction_programme_id = modalForm.querySelector('[name=dossier_instruction_programme_id]').value;
                        delete payload.dossier_id;
                    } else {
                        delete payload.dossier_id;
                        delete payload.dossier_instruction_programme_id;
                    }
                    try {
                        const r = await postJson(persistUrl, payload);
                        window.location.href = `{{ url('simulator/scenarios') }}/${r.token}`;
                    } catch (e) { alert(e.message); }
                });
            })();
        </script>
    @endpush
@endsection

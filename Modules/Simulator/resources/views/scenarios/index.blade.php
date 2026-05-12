@extends($simulatorLayout ?? 'Layouts.app')

@section('title', __('simulator::simulator.breadcrumb.scenarios'))

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('simulator::simulator.breadcrumb.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('simulator.index') }}">{{ __('simulator::simulator.breadcrumb.simulator') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('simulator::simulator.breadcrumb.scenarios') }}</li>
        </ol>
    </nav>
@endsection

@section('actions')
    <a href="{{ route('simulator.index') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle simulation
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Rattachement</th>
                            <th class="text-end">Capital</th>
                            <th class="text-end">Taux</th>
                            <th>Periodicite</th>
                            <th class="text-end">TEG</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($scenarios as $s)
                            <tr>
                                <td>
                                    <a href="{{ route('simulator.scenarios.show', $s) }}" class="fw-semibold">{{ $s->name }}</a>
                                </td>
                                <td>
                                    @if ($s->dossier_id)
                                        <span class="badge text-bg-primary-subtle">Dossier #{{ $s->dossier_id }}</span>
                                    @elseif ($s->dossier_instruction_programme_id)
                                        <span class="badge text-bg-info-subtle">Ligne #{{ $s->dossier_instruction_programme_id }}</span>
                                    @else
                                        <span class="badge text-bg-secondary-subtle">{{ __('simulator::simulator.attachment.none') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format((float) $s->principal, 0, ',', ' ') }} {{ $s->currency }}</td>
                                <td class="text-end">{{ number_format((float) $s->annual_rate, 2, ',', ' ') }} %</td>
                                <td>{{ $s->periodicity }}</td>
                                <td class="text-end">{{ $s->computed_teg !== null ? number_format((float) $s->computed_teg, 2, ',', ' ').' %' : '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = match ($s->status) {
                                            'draft' => 'text-bg-secondary-subtle',
                                            'submitted' => 'text-bg-info',
                                            'validated' => 'text-bg-success',
                                            'rejected' => 'text-bg-danger',
                                            default => 'text-bg-secondary-subtle',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ __('simulator::simulator.workflow.status.'.$s->status) }}</span>
                                </td>
                                <td>{{ $s->created_at?->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('simulator.scenarios.show', $s) }}" class="btn btn-sm btn-outline-primary">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Aucun scenario enregistre.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $scenarios->links() }}
            </div>
        </div>
    </div>
@endsection

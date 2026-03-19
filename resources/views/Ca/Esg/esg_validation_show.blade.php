@extends('Layouts.ca')

@section('title', 'Évaluation ESG')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('ca.esg-evaluations.index') }}">Validations ESG</a></li>
       <li class="breadcrumb-item active" aria-current="page">Détail</li>
    </ol>
</nav>
@endsection

@section('actions')
    @if($evaluation->isSubmitted())
        <form action="{{ route('ca.esg-evaluations.validate', $evaluation) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Valider</button>
        </form>
        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">Rejeter</button>
        @include('Ca.Esg.partials.reject_modal', ['evaluation' => $evaluation])
    @endif
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Évaluation ESG - {{ $evaluation->dossier?->entreprise?->name }}</h5>
        <p class="lead">{{ $evaluation->dossier?->programme?->name }} - {{ $evaluation->analyste?->name }}</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <p><strong>Statut :</strong> <span class="badge bg-{{ $evaluation->status === 'validated' ? 'success' : ($evaluation->status === 'submitted' ? 'warning' : ($evaluation->status === 'rejected' ? 'danger' : 'info')) }}">{{ ucfirst($evaluation->status) }}</span></p>
            <p><strong>Score global :</strong> {{ $evaluation->score_global }}/100</p>
            <p><strong>Score E :</strong> {{ $evaluation->score_environmental }} | <strong>S :</strong> {{ $evaluation->score_social }} | <strong>G :</strong> {{ $evaluation->score_governance }}</p>
            <p><strong>Niveau de risque :</strong> {{ $evaluation->risk_level ?? '-' }}</p>
            <p><strong>Bancabilité :</strong> {{ $evaluation->bankability_level ?? '-' }}</p>
            @if($evaluation->analyst_conclusion)
                <p><strong>Conclusion analyste :</strong> {{ $evaluation->analyst_conclusion }}</p>
            @endif
            @if($evaluation->rejection_reason)
                <p><strong>Raison du rejet :</strong> {{ $evaluation->rejection_reason }}</p>
            @endif
        </div>
    </div>
    <a href="{{ route('ca.esg-evaluations.index') }}" class="btn btn-secondary mt-2">Retour</a>
@endsection

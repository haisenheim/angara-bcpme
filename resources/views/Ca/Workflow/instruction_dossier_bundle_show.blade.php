@extends('Layouts.ca')

@section('title', 'Validation structuration (chef d’agence)')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">{{ $dossier->entreprise?->name ?? 'Client' }}</h1>
        <p class="text-muted mb-0">Structuration soumise par le chef de filière — à valider ou rejeter par le chef d’agence.</p>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            @include('partials.instruction-dossier-consultation', ['dossier' => $dossier, 'instructionConsultation' => $instructionConsultation ?? null])
            @include('partials.dossier-pieces-jointes', [
                'dossier' => $dossier,
                'showUpload' => false,
            ])
        </div>
        <div class="col-lg-4">
            @include('partials.ca-instruction-agence-closure-stack', [
                'dossier' => $dossier,
                'canApproveRejectInstructionTransmission' => $canApproveRejectInstructionTransmission ?? false,
                'closureStatutLabel' => $closureStatutLabel ?? null,
                'workflowBundleView' => true,
            ])
        </div>
    </div>
</div>
@endsection

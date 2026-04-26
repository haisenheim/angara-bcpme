@extends('Layouts.gestionnaire')

@section('title', 'État des engagements')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.show', $entreprise->token) }}">{{ Str::limit($entreprise->name, 35) }}</a></li>
       <li class="breadcrumb-item active" aria-current="page">État des engagements</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">État des engagements</h5>
        <p class="text-body-secondary mb-0 mt-1">{{ $entreprise->name }}</p>
    </div>
@endsection

@section('content')
    @include('partials.engagement_report_inner', [
        'engagements' => $engagements,
        'entreprise' => $entreprise,
        'banques' => $banques,
        'canEdit' => $canEdit ?? false,
        'setEngagementUrl' => $setEngagementUrl ?? null,
    ])
@endsection

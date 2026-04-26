@extends(match ($space['route'] ?? '') {
    'respexp' => 'Layouts.respexp',
    'juridique' => 'Layouts.juridique',
    'analyste-juridique' => 'Layouts.analyste-juridique',
    'reng' => 'Layouts.reng',
    'analyste-credit' => 'Layouts.analyste-credit',
    'analyste-risques' => 'Layouts.analyste-risques',
    'rerx' => 'Layouts.rerx',
    'pca' => 'Layouts.app',
    'administrateur' => 'Layouts.app',
    'dg' => 'Layouts.dg',
    'dga' => 'Layouts.dga',
    'respaud' => 'Layouts.app',
    'respci' => 'Layouts.app',
    default => 'Layouts.app',
})

@php
    $entityListLabel = in_array($space['route'] ?? '', ['dg', 'dga'], true) ? 'Clients' : 'Entreprises';
@endphp

@section('title', 'État des engagements — '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.entreprises.index') }}">{{ $entityListLabel }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.entreprises.show', $entreprise->token) }}">{{ Str::limit($entreprise->name, 40) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">État des engagements</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">État des engagements</h5>
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

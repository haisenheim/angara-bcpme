@extends('Layouts.gestionnaire')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/programme-fiche.css') }}">
@endpush

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.programmes.index') }}">Programmes</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="text-body-secondary mb-0 mt-1 small">Périmètre opérationnel, budgets et dossiers d'instruction.</p>
    </div>
@endsection

@section('content')
    @include('partials.programme-fiche-lecture', ['item' => $item, 'space' => 'gestionnaire'])
@endsection

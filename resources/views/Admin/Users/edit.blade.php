@extends('Layouts.admin')

@section('title', 'Modifier un utilisateur')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $item->token) }}">{{ $item->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Modification</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Modifier {{ $item->name }}</h5>
        <p class="lead mb-0">Mise a jour des informations de compte, du role et des affectations.</p>
    </div>
@endsection

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.show', $item->token) }}" class="btn btn-outline-secondary btn-sm">Voir la fiche</a>
        @if($item->active)
            <a href="{{ route('admin.user.disable', $item->token) }}" class="btn btn-outline-danger btn-sm">Verrouiller</a>
        @else
            <a href="{{ route('admin.user.enable', $item->token) }}" class="btn btn-outline-success btn-sm">Activer</a>
        @endif
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.users.update', $item->token) }}" method="POST">
        @include('Admin.Users._form')
    </form>
@endsection

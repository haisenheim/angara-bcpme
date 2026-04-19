@extends('Layouts.admin')

@section('title', 'Nouveau compte utilisateur')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nouveau compte</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouveau compte utilisateur</h5>
        <p class="lead mb-0">Creation d'un profil utilisateur avec ses affectations metier.</p>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
@endsection

@section('content')
    <form action="{{ route('admin.users.store') }}" method="POST">
        @include('Admin.Users._form')
    </form>
@endsection

@extends('Layouts.app')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Mon profil</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $user->name }}</h5>
        <p class="lead">Edition du profil</p>
    </div>
@endsection

@section('content')
    <div style="margin: 1px auto; width:500px" class="card">
        <div class="card-body">
            <div class="d-flex justify-content-center mb-3">
                <img style="width: 120px; border-radius: 50%;" src="{{ $user->photo }}" alt="">
            </div>
            <form enctype="multipart/form-data" action="{{ route('profile.store') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $user->token }}">
                <div class="mb-3">
                        <div class="">
                            <label for="">NOM DE L'UTILISATEUR</label>
                            <input required type="text" name="name" value="{{ $user->name }}" placeholder="Saisir le nom de l'utilisateur" class="form-control">
                        </div>
                        <div class="mt-2">
                            <label for="">PHOTO DE PROFIL</label>
                            <input type="file" name="photo"  placeholder="Saisir le nom de l'utilisateur" class="form-control">
                        </div>
                    </div>
                    <div class="">
                        <div class="">
                            <label for="">EMAIL DE CONNEXION</label>
                            <input required type="email" name="email" value="{{ $user->email }}" placeholder="Saisir l'adresse email de connexion de l'utilisateur" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">MOT DE PASSE</label>
                            <input required type="password" name="password" placeholder="Saisir le mot de passe de connexion de l'utilisateur" class="form-control">
                        </div>
                    </div>
                <div class="mt-5">
                    <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                </div>
            </form>
        </div>
    </div>


    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endsection

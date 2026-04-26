@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Libelles</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des libelles</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="dropdown-item"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Libellés d'écriture</h5>
        <p class="lead">Liste de tous les libellés d'écriture</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Libelle</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouveau libellé</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.libelles.store') }}" method="post">
                        @csrf
                        <div class="mt-2">
                            <label for="">Libellé</label>
                            <input type="text" name="name" required placeholder="Numero du compte" class="form-control">
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        div.form-group{
            margin-top: 1rem;
        }
    </style>
@endsection

@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Agences</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Details sur l'agence</p>
    </div>
@endsection

@section('actions')
    <x-page-actions-dropdown menu-class="dropdown-menu dropdown-menu-end border shadow-sm py-2">
        <li><a data-bs-toggle="modal" data-bs-target="#addModal" class="dropdown-item" href="#">Associer un libellé</a></li>
    </x-page-actions-dropdown>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <h5>AGENCE : {{ $item->name }}</h5>
                <h6>VILLE : {{ $item->ville?$item->ville->name:'-' }}</h6>
                <h6>DEPARTEMENT : {{ $item->departement?$item->departement->name:'-' }}</h6>
            </div>
        </div>
        <div class="card w-75">
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>LIBELLE</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->libelles as $lib)
                            <tr>
                                <td>{{ $lib->name }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Associer un libellé</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.agence.set.libelle') }}" method="post">
                        @csrf
                        <input type="hidden" name="agence_id" value="{{ $item->id }}">
                        <div class="mt-3">
                            <label for="">Libellé</label>
                            <select name="libelle_id" required id="" class="form-control">
                                <option value="">Choix du libellé ...</option>
                                @foreach($libelles as $cpt)
                                    <option value="{{ $cpt->id }}">{{ $cpt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

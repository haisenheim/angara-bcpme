@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Parametres</a></li>
       <li class="breadcrumb-item"><a href="#">Opérateurs mobiles</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des Opérateurs mobiles</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-primary btn-sm"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Opérateurs</h5>
        <p class="lead">Liste de tous les opérateurs mobiles </p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Operateur</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>
                                <div>
                                    <img src="{{ $item->photo }}" width="25" alt="">
                                </div>
                            </td>
                            <td><a href="{{ route('admin.operateurs.show',$item->id) }}">{{ $item->name }}</a></td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvel opérateur mobile</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.operateurs.store') }}" method="post">
                        @csrf
                            <div class="d-flex gap-2 flex-grow">
                                <div class=" w-50">
                                    <label for="">NOM</label>
                                    <input required type="text" name="name" placeholder="Saisir le nom de l'operateur" class="form-control">
                                </div>
                                <div class="">
                                    <label for="">Image</label>
                                    <input required type="file" name="photo" class="form-control">
                                </div>
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

@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Parametres</a></li>
       <li class="breadcrumb-item"><a href="#">Pays</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des pays</li>
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
        <h5 class="page-title mb-0 mt-2">Pays</h5>
        <p class="lead">Liste de tous les pays </p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pay</th>
                        <th>Continent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pays as $pay)
                        <tr>
                            <td>{{ $pay->abb }}</td>
                            <td><a href="{{ route('admin.pays.show',$pay->id) }}">{{ $pay->name }}</a></td>
                            <td>{{ $pay->continent?$pay->continent->name:'-' }}</td>
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
                    <h5 class="modal-title">Nouveu pays</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.pays.store') }}" method="post">
                        @csrf
                            <div class="d-flex gap-2 flex-grow">
                                <div class=" w-50">
                                    <label for="">NOM</label>
                                    <input required type="text" name="name" placeholder="Saisir le nom de la cooperative" class="form-control">
                                </div>
                                <div class="">
                                    <label for="">Abbreviation</label>
                                    <input required type="text" name="abb" class="form-control">
                                </div>
                                <div>
                                    <label for="">Continent</label>
                                    <select required name="continent_id" id="region_id" class="form-control">
                                        <option value="">Selectionner un continent ...</option>
                                        @foreach($continents as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
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

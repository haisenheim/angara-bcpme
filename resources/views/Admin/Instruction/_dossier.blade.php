@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="#">INSTRUCTION</a></li>
       <li class="breadcrumb-item active" aria-current="page">DOSSIER</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">INSTRCTION DU DOSSIER</h5>
        <p class="lead">DEBUT D'INSTRUCTION D'UN DOSSIER</p>
    </div>
@endsection

@section('actions')
    <div class="btn-group">
        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
        <span class="vr"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a data-bs-toggle="modal" data-bs-target="#addModal" class="dropdown-item" href="#">Associer un libellé</a></li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="d-flex gap-1">
        <div class="card w-25">
            <div class="card-body">
                <form enctype="multipart/form-data" action="{{ route('admin.instruction.dsf') }}" method="post">
                    <div class="form-group">
                        <label for="">FICHIER DSF</label>
                        <input type="file" name="upload" class="form-control">
                    </div>
                </form>
            </div>
        </div>
        <div class="card w-75">
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Critere Principaux</th>
                            <td>&numero; sous-critere</td>
                            <th>Sous - critere</th>
                            <th>Ponderation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td rowspan="{{ count($item['souscriteres'])+1 }}">{{ $item['name'] }}</td>
                            </tr>
                                @foreach($item['souscriteres'] as $sc)
                                <tr>
                                    <td>{{ $sc['sequence'] }}</td>
                                    <td>{{ $sc['name'] }}</td>
                                    <td data-critere_id="{{ $sc['id'] }}" class="td-edit border-1 border-muted" contenteditable="true">{{ $sc['default'] }}</td>
                                </tr>
                                @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@extends('Layouts.ca')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Coopératives</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des wallets de coopérative</li>
    </ol>
 </nav>
@endsection
@section('actions')

  <!--  <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-primary btn-sm"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a> -->
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Wallets de coopératives</h5>
        <p class="lead">Liste de tous les wallets de coopératives</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Coopérative</th>
                        <th>Opérateur</th>
                        <th>Solde</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td> <span><img src="{{ $item->cooperative?->photo }}" width="20" alt=""></span>{{ $item->cooperative?->name }}</td>
                            <td> <img src="{{ $item->operateur?->photo }}" width="20" alt=""> {{ $item->operateur?->name }}</td>
                            <td>{{ number_format($item->montant,0,',','.') }}</td>
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
                    <h5 class="modal-title">Nouveau wallet</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('ca.wallets.store') }}" method="post">
                        @csrf
                        <div class="d-flex gap-2 flex-grow mt-3">
                            <div class="flex-fill">
                                <label for="">Cooperative</label>
                                <select required class="form-control" name="cooperative_id" id="">
                                    <option value="">Selectionner un cooperative ...</option>
                                    @foreach ($cooperatives as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-fill">
                                <label for="">Operateur</label>
                                <select required class="form-control" name="operateur_id" id="">
                                    <option value="">Selectionner un operateur ...</option>
                                    @foreach ($operateurs as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class=" w-30">
                                <label for="">Montant initial</label>
                                <input required type="text" name="montant" placeholder="Adresse physque de la cooperative" class="form-control">
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

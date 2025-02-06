@extends('Layouts.cooperative')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Entrées de stocks</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection
@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
    Actions
    <span class="vr"></span>
    </button>
    <ul class="dropdown-menu analyse">
        <li><a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#addModal"  href="#">Enregistrer un paiement</a></li>
    </ul>
</div>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2 text-center">Entrée en stock &numero; {{ $item->name }}</h5>

    </div>
@endsection

@section('content')
    <div style="margin: 0 auto; width:900px" class="card">
        <div class="card-body">
            <div class="d-flex gap-2 d-flex gap-2 justify-content-between">
                <fieldset>
                    <legend>Producteur</legend>
                    <img src="{{ $item->exploitant->photo }}" class="rounded-circle" width="100" alt="">
                    <p><span class="fs-4">{{ $item->exploitant->name }}</span></p>
                    <p><span class="fs-5">{{ $item->exploitant->age }} ans</span></p>
                    <p><span class="fs-5">Village : {{ $item->exploitant->village?->name }}</span></p>
                </fieldset>
                <div class="">
                    <fieldset>
                        <legend>Agent</legend>
                        <img src="{{ $item->agent->photo }}" class="rounded-circle" width="100" height="100" alt="">
                        <p><span class="fs-4">{{ $item->agent->name }}</span></p>
                        <p><span class="fs-5">{{ $item->exploitant->phone }} ans</span></p>
                    </fieldset>
                </div>
                <div>
                    <p><span>Date de collecte : {{ $item->created_at->format('d/m/Y') }}</span></p>
                    <p><span>Quantite : {{ $item->quantity }} Kg</span></p>
                    <p><span>Prix unitaire : {{ $item->pu }}</span></p>
                    <p><span>Total : {{ number_format($item->montant,0,',','.') }}</span></p>
                    <p><span>Total versement : {{ number_format($item->versements,0,',','.') }} </span></p>
                    <p><span>Total reste : {{ number_format($item->reste,0,',','.') }} </span></p>
                    <p><span>Entrepot : {{ $item->entrepot?->name }} </span></p>
                </div>
            </div>

            <h5>Historique des paiements</h5>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>MONTANT</th>
                        <th>WALLET</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->paiements as $p)
                        <tr>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($p->montant,0,',','.') }}</td>
                            <td>{{ $p->wallet->phone }}</td>
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
                    <h5 class="modal-title">Nouveau paiement</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('cooperative.entree.paiement') }}" method="post">
                        @csrf
                        <input type="hidden" id="id" name="entree_id" value="{{ $item->id }}">
                        <div>
                            <div>
                                <label for="">WALLET</label>
                                <select required name="wallet_id" id="" class="form-control">
                                    <option value="">Selectionner un wallet ...</option>
                                    @foreach($item->agent->wallets as $mbr)
                                        <option value="{{ $mbr->id }}">{{ $mbr->phone }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="">MONTANT</label>
                                <input type="number" name="montant" class="form-control">
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        p{
            margin-bottom: 10px;
        }
    </style>
@endsection

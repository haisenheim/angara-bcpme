@extends('../Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Prévisions de stocks</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2 text-center">Prévision de stock &numero; {{ $item->name }}</h5>
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
                        <p><span class="fs-5">{{ $item->exploitant->age }} ans</span></p>
                        <p>TEL:<span class="fs-5">{{ $item->exploitant->phone }}</span></p>
                    </fieldset>
                </div>
                <div>
                    <fieldset>
                        <legend>Details</legend>
                        <div class="text-dark">
                            <p><span>Date de collecte :</span><span class="fw-bold"> {{ $item->created_at->format('d/m/Y') }}</span></p>
                            <p><span>Quantite :</span><span class="fw-bold"> {{ $item->quantity }} Kg</span></p>
                            <p><span>Prix unitaire :</span><span class="fw-bold"> {{ $item->pu }}</span></p>
                            <p><span>Total :</span><span class="fw-bold"> {{ number_format($item->montant,0,',','.') }}</span></p>

                            <p><span>Entrepot :</span><span class="fw-bold"> {{ $item->entrepot?->name }} </span></p>
                        </div>
                    </fieldset>
                </div>
            </div>



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
                    <form enctype="multipart/form-data" action="" method="post">
                        @csrf
                        <input type="hidden" id="id" name="entree_id" value="{{ $item->id }}">
                        <div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

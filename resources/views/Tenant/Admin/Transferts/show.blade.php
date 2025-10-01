@extends('../Layouts.tenant.admin')

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
                    <p><span class="fs-5">Village : {{ $item->exploitant->village?->name }}</span></p>
                </fieldset>
                <fieldset>
                        <legend>Producteur relai</legend>
                        <img src="{{ $item->agent->photo }}" class="rounded-circle" width="100" height="100" alt="">
                        <p><span class="fs-4">{{ $item->agent->name }}</span></p>
                        <p>TEL:<span class="fs-5">{{ $item->agent->phone }}</span></p>
                </fieldset>
                <div>
                    <fieldset>
                        <legend>Details</legend>
                        <div class="text-dark">
                            <p><span>Date de collecte :</span><span class="fw-bold"> {{ $item->created_at->format('d/m/Y') }}</span></p>
                            <p><span>Quantite :</span><span class="fw-bold"> {{ $item->quantity }} Kg</span></p>
                            <p><span>Prix unitaire :</span><span class="fw-bold"> {{ $item->pu }}</span></p>
                            <p><span>Total :</span><span class="fw-bold"> {{ number_format($item->montant,0,',','.') }}</span></p>
                            <p><span>Total versement :</span><span class="fw-bold"> {{ number_format($item->versements,0,',','.') }} </span></p>
                            <p><span>Total reste :</span><span class="fw-bold"> {{ number_format($item->reste,0,',','.') }} </span></p>
                            <p><span>Entrepot :</span><span class="fw-bold"> {{ $item->entrepot?->name }} </span></p>
                        </div>
                    </fieldset>
                </div>
            </div>

            <h5>Historique des paiements</h5>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>MODE DE PAIEMENT</th>
                        <th>MONTANT</th>
                        <th>CAISSE</th>
                        <th>WALLET</th>
                        <th>COMPTE CIBLE</th>
                        <th>STATUT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->paiements as $p)
                        <tr>
                            <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $p->mode?->name}}</td>
                            <td>{{ number_format($p->montant,0,',','.') }}</td>
                            <td>{{ $p->caisse?->name }}</td>
                            <td>{{ $p->wallet?->name }}</td>
                            <td>{{$p->compte}}</td>
                            <td>
                                <span class="badge {{ $p->status['class']}}">{{ $p->status['name'] }}</span>
                            </td>
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
                    <form enctype="multipart/form-data" action="{{ route('admin.entree.paiement') }}" method="post">
                        @csrf
                        <input type="hidden" id="id" name="entree_id" value="{{ $item->id }}">
                        <div>
                            <div>
                                <select required id="mode" class="form-control"  name="mode_paiement_id">
                                    <option value="">Mode de paiement ...</option>
                                    <option value="1">CAISSE</option>
                                    <option value="2">WALLET</option>
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="">CAISSE</label>
                                <select disabled required name="caisse_id" id="caisse_id" class="form-control">
                                    <option value="">Selectionner une caisse ...</option>
                                    @foreach($caisses as $mbr)
                                        <option value="{{ $mbr->id }}">{{ $mbr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <label for="">WALLET</label>
                                <select disabled required name="wallet_id" id="wallet_id" class="form-control">
                                    <option value="">Selectionner un wallet ...</option>
                                    @foreach($wallets as $mbr)
                                        <option value="{{ $mbr->id }}">{{ $mbr->name }}-{{ $mbr->operateur?->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="">NUMERO TELEPHONE CIBLE</label>
                                <input disabled required type="text" id="phone" name="phone" class="form-control">
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

    <script>
        $('#mode').change(function(){
            var id = $('#mode').val();
            if(id == 1){
                $('#wallet_id').prop('disabled',true);
                $('#caisse_id').prop('disabled',false);
                $('#phone').prop('disabled',true);
            }

            if(id == 2){
                $('#wallet_id').prop('disabled',false);
                $('#phone').prop('disabled',false);
                $('#caisse_id').prop('disabled',true);
            }
        })
    </script>

    <style>
        p{
            margin-bottom: 10px;
        }
    </style>
@endsection

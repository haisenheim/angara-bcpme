@extends('../Layouts.tenant.payeur')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Agents de terrain</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des wallets des agents</li>
    </ol>
 </nav>
@endsection
@section('actions')
    <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-primary btn-sm"><i class="demo-pli-add me-2 fs-5"></i> Ajouter</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Wallets des agents de terrain</h5>
        <p class="lead">Liste de tous les wallets des agents de terrains</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Opérateur</th>
                        <th>Télephone</th>
                        <th>Solde</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td> <span><img class="rounded-circle" src="{{ $item->agent?->photo }}" width="20" alt=""></span>{{ $item->agent?->name }}</td>
                            <td> <img src="{{ $item->operateur?->photo }}" width="20" alt=""> <span style="vertical-align: middle" class="mb-2">{{ $item->operateur?->name }}</span></td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ number_format($item->montant,0,',','.') }}</td>
                            <td><span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <span class="vr"></span>
                                    </button>
                                    <ul class="dropdown-menu analyse">
                                        @if($item->active)
                                        <li><a class="dropdown-item r-btn" data-token="{{ $item->token }}" data-bs-target="#rechargeModal" data-bs-toggle="modal"  href="#">Recharger</a></li>
                                            <li><a class="dropdown-item"  href="{{ route('payeur.wallet.disable',$item->token) }}">Verrouiller</a></li>
                                        @else
                                            <li><a class="dropdown-item"  href="{{ route('payeur.wallet.enable',$item->token) }}">Deverrouiller</a></li>
                                        @endif

                                    </ul>
                                </div>
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
                    <h5 class="modal-title">Nouveau wallet</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('payeur.wallets.store') }}" method="post">
                        @csrf
                        <div class="">
                            <div class="flex-fill">
                                <label for="">Agent</label>
                                <select required class="form-control" name="agent_id" id="">
                                    <option value="">Selectionner un agent ...</option>
                                    @foreach ($agents as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-fill mt-4">
                                <label for="">Operateur</label>
                                <select required class="form-control" name="operateur_id" id="">
                                    <option value="">Selectionner un operateur ...</option>
                                    @foreach ($operateurs as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-fill mt-4">
                                <label for="">Telephone</label>
                                <input required type="text" placeholder="Numero de telephone du compte" name="phone" class="form-control">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn-primary btn">ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rechargeModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Recharge du wallet</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('payeur.wallet.recharge') }}" method="post">
                        @csrf
                        <input type="hidden" id="token" name="token" value="">
                        <div class="mt-0">
                            <h6 class="text-danger">Avertissement !</h6>
                            <p>Attention! Cette opérateur est irreversible</p>
                            <div class="w-30 mt-2">
                                <label for="">Montant</label>
                                <input required type="text" name="montant" placeholder="Montant de la recharge" class="form-control">
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
        $('.r-btn').click(function(){
            var token = $(this).data('token');
            $('#token').val(token);
        })
    </script>

@endsection

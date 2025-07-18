@extends('Layouts.structuration_gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Tableau de bord</a></li>
       <li class="breadcrumb-item active" aria-current="page">comptes</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>

    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informations du compte</h5>
                        <p class="card-text">Cooperative: {{ $item->tenant?->name }}</p>
                        <p class="card-text">Numero: {{ $item->name }}</p>
                        <p class="card-text">Solde: {{ number_format($item->solde, 0,',','.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Demandes de fonds</h5>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Montant</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ number_format($request->montant, 0,',','.') }}</td>
                                        <td><span class="badge bg-{{ $request->status['color'] }}">{{ $request->status['name'] }}</span></td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if(!$request->cancelled_at && $request->validated_at && !$request->treated_at)
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                                <span class="vr"></span>
                                                </button>
                                                <ul class="dropdown-menu analyse">
                                                    <li><a class="dropdown-item v-btn"  data-token="{{ $request->token }}" data-bs-toggle="modal" data-bs-target="#validateModal"  href="#">traiter</a></li>
                                                </ul>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>

        <div class="modal fade" id="validateModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Confirmation !</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('structuration_gestionnaire.request.validate') }}" method="post">
                        @csrf
                        <input type="hidden" name="compte_id" value="{{ $item->token }}">
                        <input type="hidden" name="token" id="v-token" value="">
                        <p>Attention cette operation est irreversible</p>
                        <div class="mt-5">
                            <button type="submit" class="btn-success btn-sm btn">Approuver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.v-btn').click(function(){
            var token = $(this).data('token');
            $('#v-token').val(token);
            console.log(token);
        })
    </script>
@endsection

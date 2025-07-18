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
        <h5 class="page-title mb-0 mt-2">Appels de fonds</h5>

    </div>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Cooperative</th>
                            <th>Numero de compte</th>
                            <th scope="col">Montant</th>
                            <th>Caisse</th>
                            <th>Wallet</th>
                            <th>Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach($requests as $request)
                             <tr>
                                <td>{{ $request->created_at->format('d/m/Y H:i')}}</td>
                                <td>{{ $request->compte?->tenant?->name }}</td>
                                <td class="p-2"><a class="btn-link" href="{{ route('structuration_gestionnaire.comptes.show', $request->compte?->token) }}">{{ $request->compte?->name }}</a></td>
                                 <td>{{ number_format($request->montant, 0,',','.') }}</td>
                                 <td>{{ $request->caisse?->name }}</td>
                                 <td>{{ $request->wallet?->name}} - {{ $request->wallet?->operateur?->name }}</td>
                                 <td><span class="badge bg-{{ $request->status['color'] }}">{{ $request->status['name'] }}</span></td>
                                 <td>
                                     @if(!$request->cancelled_at && $request->validated_at && !$request->treated_at)
                                     <div class="btn-group">
                                         <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                         Actions
                                         <span class="vr"></span>
                                         </button>
                                         <ul class="dropdown-menu analyse">
                                             <li><a class="dropdown-item v-btn" data-compte="{{ $request->compte?->token }}" data-token="{{ $request->token }}" data-bs-toggle="modal" data-bs-target="#validateModal"  href="#">traiter</a></li>
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
                        <input type="hidden" id="compte_id" name="compte_id" value="">
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
            var compte = $(this).data('compte');
            $('#compte_id').val(compte);
            console.log(token);
        })
    </script>

@endsection

@extends('Layouts.sectoriel')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Appels de fonds</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste de tous les appels de fonds</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Appels de fonds</h5>
        <p class="lead">Liste de tous les appels de fonds</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>EXPEDITEUR</th>
                        <th>CAISSE</th>
                        <th>WALLET</th>
                        <th>MONTANT</th>
                        <th>STATUS</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                       <tr>
                            <td>{{ $item->created_at->format('d/m/Y à H:i') }}</td>
                            <td>{{ $item->cooperative?->name }}</td>
                            <td>{{ $item->caisse?->name }}</td>
                            <td><img src="{{ $item->wallet?->operateur?->photo }}" width="20" alt=""> {{ $item->wallet?->name }}</td>
                            <td>{{ number_format($item->montant,0,',','.') }}</td>
                            <td><span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></td>
                            <td>
                                @if(!$item->cancelled_at && !$item->validated_at)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <span class="vr"></span>
                                    </button>
                                    <ul class="dropdown-menu analyse">
                                        <li><a class="dropdown-item v-btn"  data-token="{{ $item->token }}" data-bs-toggle="modal" data-bs-target="#validateModal"  href="#">Approuver</a></li>
                                        <li><a class="dropdown-item c-btn"  data-token="{{ $item->token }}" data-bs-toggle="modal" data-bs-target="#cancelModal"  href="#">Rejeter</a></li>
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
                    <form enctype="multipart/form-data" action="{{ route('sectoriel.request.validate') }}" method="post">
                        @csrf
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

    <div class="modal fade" id="cancelModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Confirmation !</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('sectoriel.request.cancel') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" id="c-token" value="">
                        <p>Attention cette operation est irreversible</p>
                        <div class="mt-5">
                            <button type="submit" class="btn-danger btn-sm btn">Rejeter</button>
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
        $('.c-btn').click(function(){
            var token = $(this).data('token');
            $('#c-token').val(token);
        })
    </script>
@endsection

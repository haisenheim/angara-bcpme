@extends('../Layouts.tenant.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Livraisons</a></li>
       <li class="breadcrumb-item active" aria-current="page">Suivi des livraisons</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Suivi des livraisons</h5>
        <p class="lead">Centrale de suivi de toutes les livraisons </p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Acheteur</th>
                        <th>Quantité</th>
                        <th>Prix/kg</th>
                        <th>Total</th>
                        <th>Lieu de ramassage</th>
                        <th>Jour de ramassage</th>
                        <th>Saison</th>
                        <th>Status</th>
                        <th>Validé le</th>
                        <th>Livré le</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($livraisons as $item)

                        <tr>
                            <td><a href="#">{{ $item->client?$item->client->name:'-' }}</a></td>
                            <td>{{ number_format($item->quantity,0,',','.') }} tonne(s)</td>
                            <td>{{ number_format($item->price,0,',','.') }} fcfa</td>
                            <td>{{ number_format($item->total,0,',','.') }} fcfa</td>
                            <td>{{ $item->village?$item->village->name:$item->arrondissement->name }}</td>
                            <td>{{ $item->jour }}</td>
                            <td><a href="#">{{ $item->saison?$item->saison->name:'-' }}</a></td>
                            <td><span class="badge bg-{{ $item->status['color']}}">{{ $item->status['name'] }}</span></td>
                            <td>{{ $item->accepted_at?$item->accepted_dt->format('d/m/Y'):'-' }}</td>
                            <td>{{ $item->delivered_at?$item->delivered_dt->format('d/m/Y'):'-' }}</td>
                            <td>
                                @if($item->status['code']==-1)
                                    <a class="btn btn-sm btn-warning btn-validate" data-bs-toggle="modal" data-bs-target="#confirmModal" data-day="{{ $item->jour }}" data-client="{{ $item->client }}" data-livraison="{{ $item }}"  href="#">valider</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="confirmModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Confirmation</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.livraison.validate') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" id="token">
                            <div class="">
                                <h6 class="text-danger">Attention cette operation est irreversible!</h6>
                                <p>Commande de <span class="text-danger" id="qty"></span> tonnes au client  <span class="text-danger" id="client_name"></span> a la date du <span class="text-danger" id="day"></span></p>
                                <p>Voulez-vous vraiment confirmer la validation de cette commande!</p>
                            </div>
                        <div class="mt-5">
                            <a data-bs-dismiss="modal" class="btn-danger btn btn-sm">Annuler</a>
                            <button type="submit" class="btn-success btn btn-sm">Je valide</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.btn-validate').click(function(){
                console.log($(this).data('client'))
                console.log($(this).data('livraison'))
                 var client = $(this).data('client');
                 var day = $(this).data('day');
                 var livraison = $(this).data('livraison');
                 $('#day').text(day);
                 $('#client_name').text(client.name);
                 $('#qty').text(livraison.quantity);
                 $('#token').val(livraison.token);
                 console.log(client)
                 console.log(livraison)
            })
        })
    </script>
@endsection

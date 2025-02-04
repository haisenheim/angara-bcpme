@extends('Layouts.cooperative')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Tableau de bord</a></li>
       <li class="breadcrumb-item active" aria-current="page">Accueil</li>
    </ol>
 </nav>
@endsection

@section('content')
<?php
    $cooperative = $user->cooperative;

?>
    <div>
        <div class="d-flex gap-3">
            <div class="w-300px">
                <div class="card mt-2">
                    <div class="card-body text-center">
                        <h4><i class="pli-wallet-2 fs-3 me-2"></i> MES WALLETS</h4>
                        <ul class="list-group">
                            @foreach ($user->cooperative?->wallets as $wallet)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <img src="{{ $wallet->operateur->photo }}" width="20" alt="">
                                            <span>{{ $wallet->operateur->name }}</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-dark">{{ number_format($wallet->montant,0,',','.') }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-body text-center">
                        <h4> <i class="pli-windmill fs-2 me-2"></i> MON STOCK</h4>
                        <ul class="list-group">
                            @foreach ($user->cooperative?->entrepots as $item)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span>{{ $item->name }}</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-danger">{{ number_format($item->stock/1000,1,',','.') }} tonnes</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer">
                        <span style="float: right" class="badge bg-danger">{{ number_format($cooperative->stock/1000,1,',','.') }} tonnes</span>
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="pli-coins fs-1"></i>
                        </div>
                        <div class="fs-5">
                            <span id="text-total">410.000.000</span> <span>FCFA</span>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <p class="fs-5 fw-semibold">Total des paiements</p>
                    </div>
                </div>
            </div>
            <div class="flex-fill">
                <div class="d-flex gap-1">
                    <div class="card bg-light flex-fill text-primary">
                        <div class="card-body text-center flex-fill">
                            <div class="mb-3">
                                <i class="pli-conference fs-2"></i>
                            </div>
                            <div>
                                <span>{{ number_format($user->cooperative?->exploitants->count(),0,',','.') }}</span> <span>Membres</span>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-teal text-white flex-fill">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="pli-conference fs-1 text-white"></i>
                            </div>
                            <div class="fs-5">
                                <span id="text-agents">{{ $cooperative->agents->count() }}</span> <span>Agents</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div style="">
                                <span>Solde des agents</span>
                                <canvas id="agChart" width="300" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="{{ asset('js/chart.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $.ajax({
            url:'/cooperative/kpi/agents/solde',
            type:'get',
            dataType:'json',
            success:function(data){
                console.log(data);


                const agctx = document.getElementById('agChart');
                //const aglabels = Object.keys(data);
                /* const agdata = {
                labels: aglabels,
                datasets: [
                    {
                    data: Object.values(data), //data.map(elt=>elt.data),
                    //backgroundColor:['#FFEE66','#FFAA22','#FF7744','#EE4444'],
                    borderWidth: 1,
                    borderRadius: 0,
                    borderSkipped:true,
                    },
                ]
                }; */
                const agChart = new Chart(agctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        barValueSpacing: 20,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                }
                            }]
                        },
                        responsive: false,
                        plugins: {
                        legend: {
                            position: 'top',
                            display:false,
                        },
                        title: {
                            display: false,
                            text: 'Solde des agents de terrain'
                        }
                        }
                    },
                });
            },
            error:function(err){
               // alert('Erreur de Connexion au Serveur. Impossible de charger les debiteurs!');
            }
        });

        //$('#ba-section .card').height($('#creances-section .card').height());

/*         $.ajax({
            url:'/api/v1/kpi/fvp',
            type:'get',
            dataType:'json',
            success:function(rps){
                //console.log(rps);
                const fectx = document.getElementById('feChart');
                var felabels = [];
                var data1 = [];
                var data2 = [];
                console.log(Object.entries(rps));
                Object.entries(rps).forEach(function(element){
                    var elt = element[1];
                    felabels.push(elt.name);
                    data1.push(elt.f);
                    data1.push(elt.p);
                });
                const fedata = {
                labels: felabels,
                datasets: [
                    {
                    label: 'Facturation',
                    data: data1,
                // borderColor: Utils.CHART_COLORS.red,
                    backgroundColor: '#1199EE',
                    },
                    {
                    label: 'Encaissement',
                    data: data2,
                    //borderColor: Utils.CHART_COLORS.blue,
                    backgroundColor: '#22CCDD',
                    }
                ]
            };
            const feChart = new Chart(fectx, {
                type: 'bar',
                data: fedata,
                //barThickness:50,
                options: {
                    responsive: false,
                    plugins: {
                    legend: {
                        position: 'top',
                        display:false,
                    },
                    title: {
                        display: false,
                        text: ''
                    }
                    }
                },
            });

            },
            error:function(err){
               // alert('Erreur de Connexion au Serveur. Impossible de charger les debiteurs!');
            }
        }); */




    });
</script>
@endsection

@extends('Layouts.admin')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Tableau de bord</a></li>
       <li class="breadcrumb-item active" aria-current="page">Accueil</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Tableau de bord du coordonnateur</h5>
        <p class="lead">Hello {{ auth()->user()->name }}, bienvenu sur <span class="text-muted">Cogelo</span> votre plateforme de trading de la feve!</p>
    </div>
@endsection

@section('content')
    <div class="">
        <div class="">
            <div class="row">
                <div class="col-md-6 mb-3">
                     <div class="card">
                        <div class="card-body">
                           <canvas id="_liv_client_barChart"></canvas>
                           <!-- END : Bar Chart -->
                        </div>
                     </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                       <div class="card-body">
                          <canvas id="_liv_producteur_barChart"></canvas>
                          <!-- END : Bar Chart -->
                       </div>
                    </div>
               </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th></th>
                                        @foreach($gc as $item)
                                        <th>{{ $item['name'] }}</th>
                                        @endforeach
                                    </tr>
                                    @foreach($gp as $p)
                                    <tr>
                                        <th>{{ $p['name'] }}</th>
                                        @foreach($gc as $item)
                                        <td data-client="{{ $item['id'] }}" data-producteur="{{ $p['id'] }}" class="td-editable">0</td>
                                        @endforeach
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
               <div class="col-md-4 mb-3">
                    <div class="card">
                    <div class="card-body">
                        <canvas id="_tonnage-pieChart"></canvas>
                        <!-- END : Pie Chart -->
                    </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="_tgp-pieChart"></canvas>
                            <!-- END : Pie Chart -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="_tgc-pieChart"></canvas>
                            <!-- END : Pie Chart -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.min.js') }}">

    </script>
    <script>
        $(document).ready(function(){
            const _body           = getComputedStyle( document.body );
            const primaryColor    = _body.getPropertyValue("--bs-primary");
            const successColor    = _body.getPropertyValue("--bs-success");
            const infoColor       = _body.getPropertyValue("--bs-info");
            const warningColor    = _body.getPropertyValue("--bs-warning");
            const dangerColor    = _body.getPropertyValue("--bs-danger");
            const mutedColorRGB   = _body.getPropertyValue("--bs-secondary-color-rgb");
            const grayColor       = "rgba( 180,180,180, .5 )";
            $.ajax({
                url: "{{ route('admin.dashboard.data') }}",
                type:'get',
                dataType:'json',
                success:function(data){
                    console.log(data);
                    var barData = data;
                    var clientDatasets = [
                        {
                            label: "Tonnage contractuel",
                            data: barData.client,
                            borderColor: primaryColor,
                            backgroundColor: primaryColor,
                            parsing: {
                                xAxisKey: "client",
                                yAxisKey: "qty"
                            }
                        },
                        {
                            label: "Tonnage non validé",
                            data: barData.client,
                            borderColor: dangerColor,
                            backgroundColor: dangerColor,
                            parsing: {
                                xAxisKey: "client",
                                yAxisKey: "nv"
                            }
                        },
                        {
                            label: "Tonnage non livré",
                            data: barData.client,
                            borderColor: warningColor,
                            backgroundColor: warningColor,
                            parsing: {
                                xAxisKey: "client",
                                yAxisKey: "nl"
                            }
                        },
                        {
                            label: "Tonnage livré",
                            data: barData.client,
                            borderColor: successColor,
                            backgroundColor: successColor,
                            parsing: {
                                xAxisKey: "client",
                                yAxisKey: "l"
                            }
                        },
                    ];
                    var producteurDatasets = [
                        {
                            label: "Tonnage contractuel",
                            data: barData.producteur,
                            borderColor: primaryColor,
                            backgroundColor: primaryColor,
                            parsing: {
                                xAxisKey: "producteur",
                                yAxisKey: "qty"
                            }
                        },
                        {
                            label: "Tonnage non validé",
                            data: barData.producteur,
                            borderColor: dangerColor,
                            backgroundColor: dangerColor,
                            parsing: {
                                xAxisKey: "producteur",
                                yAxisKey: "nv"
                            }
                        },
                        {
                            label: "Tonnage non livré",
                            data: barData.producteur,
                            borderColor: warningColor,
                            backgroundColor: warningColor,
                            parsing: {
                                xAxisKey: "producteur",
                                yAxisKey: "nl"
                            }
                        },
                        {
                            label: "Tonnage livré",
                            data: barData.producteur,
                            borderColor: successColor,
                            backgroundColor: successColor,
                            parsing: {
                                xAxisKey: "producteur",
                                yAxisKey: "l"
                            }
                        },
                    ];
                    var eltc = document.getElementById("_liv_client_barChart");
                    var eltp = document.getElementById("_liv_producteur_barChart");
                    var elttg = document.getElementById("_tonnage-pieChart");
                    var elttgp = document.getElementById("_tgp-pieChart");
                    var elttgc = document.getElementById("_tgc-pieChart");
                    drawBarChart(eltc,clientDatasets,mutedColorRGB,"Suivi des livraisons client");
                    drawBarChart(eltp,producteurDatasets,mutedColorRGB,"Suivi des livraisons producteur");
                    drawPieChart(elttg,[data.qtyc,data.qtyp],"Tonnage Equilibre offre/demande",['demande','offre'],[dangerColor,primaryColor]);
                    drawPieChart(elttgp,data.tngp.map(elt=>elt.quantity),"Repartition de l'offre",data.tngp.map(elt=>elt.producteur),[warningColor,infoColor,primaryColor,successColor,mutedColorRGB,grayColor,dangerColor]);
                    drawPieChart(elttgc,data.tngc.map(elt=>elt.quantity),"Repartition de la demande",data.tngc.map(elt=>elt.client),[grayColor,infoColor,primaryColor,successColor,mutedColorRGB,grayColor,dangerColor]);

                },
                error:function(err){
                    console.log(err)
                }
            });

            $('.td-editable').each(function(){
                var elt = $(this);
                var client_id = $(this).data('client')
                var cooperative_id = $(this).data('producteur')
                $.ajax({
                url: "{{ route('admin.dashboard.livraisons') }}",
                type:'get',
                dataType:'json',
                data:{'client_id':client_id,'cooperative_id':cooperative_id},
                success:function(data){
                    console.log(data);
                    var html = `<div>
                                    <p class="lh-base">Tonnage : <span class="text-bold">${data.qty}</span></p>
                                    <p class="lh-base">Montant : <span class="text-bold">${data.price} FCFA</span></p>
                                </div>`
                            elt.html(html);
                }
            })
            })



        });

        function drawBarChart(_elt,_datasets,_color,_title){
            var barChart = new Chart(
                    _elt, {
                        type: "bar",
                        data: {
                            datasets: _datasets
                        },

                        options : {
                            plugins :{
                                legend: {
                                    display: true,
                                    align: "end",
                                    labels: {
                                        color: `rgb( ${ _color })`,
                                        boxWidth: 10,
                                    }
                                },
                                tooltip : {
                                    position : "nearest"
                                },
                                title:{
                                    display:true,
                                    text:_title
                                }
                            },

                            interaction: {
                            mode : "index",
                            intersect: false,
                            },

                            scales: {
                            y: {
                                grid: {
                                    borderWidth: 0,
                                    color: `rgba( ${ _color }, .07 )`
                                },
                                suggestedMax: 150,
                                ticks: {
                                    font : { size: 11  },
                                    color : `rgb( ${ _color })`,
                                    beginAtZero: false,
                                    stepSize: 50
                                }
                            },
                            x: {
                                grid: {
                                    borderWidth: 0,
                                    drawOnChartArea: false
                                },
                                ticks: {
                                    font : { size: 11  },
                                    color : `rgb( ${ _color })`,
                                    autoSkip: true,
                                    maxRotation: 0,
                                    minRotation: 0,
                                    maxTicksLimit: 7
                                }
                            }
                            },

                            elements: {
                            // Dot width
                            point : {
                                radius: 3,
                                hoverRadius: 5
                            },
                            // Smooth lines
                            line: {
                                tension: 0.2
                            }
                            }
                        }
                    }
                );
        }

        function drawPieChart(_elt,_data,_title,_labels,_colors){
            var pieChart = new Chart(
                _elt, {
                    type: "pie",
                    data: {
                        labels: _labels,
                        datasets: [{
                        data: _data,
                        borderColor: "transparent",
                        backgroundColor: _colors,
                        }]
                    },
                    options: {
                        plugins :{
                            legend: {
                                display: true,
                                labels: {
                                    color: '#aaa',
                                    boxWidth: 10,
                                }
                            },
                            tooltip : {
                                position : "nearest"
                            },
                            title:{
                                display:true,
                                text:_title
                            }
                        }
                    }
                }
            );
        }
    </script>
    <style>
        .text-bold{
            font-weight: 800;
        }
    </style>
@endsection

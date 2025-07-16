@extends('Layouts.gestionnaire')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">MEMBRES</a></li>
       <li class="breadcrumb-item active" aria-current="page">Gestion du verger</li>
    </ol>
 </nav>
@endsection

@section('content')
    <div class="row">
       <div class="col-md-4 col-sm-12">
        <div class="card h-100 mb-3">
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>LIBELLE</td>
                            <th class="fw-bold border-start border-4 border-black">{{$item->name}}</th>
                        </tr>
                        <tr>
                            <td>ANNEE DE CULTURE</td>
                            <th class="fw-bold border-start border-4 border-black">{{$item->annee}} / {{$item->age}}ans</th>
                        </tr>
                        <tr>
                            <td>SUPERFICIE</td>
                            <th class="fw-bold border-start border-4 border-black">{{$item->size}}ha</th>
                        </tr>
                        <tr>
                            <td>NB. DE PIEDS/HA</td>
                            <th class="fw-bold border-start border-4 border-black">{{$item->nbph}}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
         </div>
       </div>
       <div class="col-md-8 col-sm-12 ps-2">
            <div class="">
                <div class="tab-base">
                    <ul class="nav nav-underline nav-component border-bottom justify-content-end" role="tablist">
                       @foreach($campagnes as $cmp)
                            @if($loop->index == 0)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab_{{$loop->index}}" type="button" role="tab" aria-controls="tab_{{$loop->index}}" aria-selected="true">{{ $cmp['saison']}}</button>
                            </li>
                            @else
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_{{$loop->index}}" type="button" role="tab" aria-controls="tab_{{$loop->index}}" aria-selected="false" tabindex="-1">{{ $cmp['saison']}}</button>
                            </li>
                            @endif
                        @endforeach
                    </ul>


                    <!-- Tabs content -->
                    <div class="tab-content">
                        @foreach($campagnes as $cmp)
                        <div id="_tab_{{$loop->index}}" class="tab-pane fade {{ $loop->index==0?'show active':''}}" role="tabpanel" aria-labelledby="home-tab">

                            <p>NOMBRE DE PLANTS DE MORTS : <strong class="fw-bold">{{ $cmp['morts']}}</strong></p>
                            <p>NOMBRE DE PLANTS DE REPLANTATIONS : <strong class="fw-bold">{{ $cmp['replantations']}}</strong></p>
                            <p>RENDEMENT : <strong class="fw-bold">{{ $cmp['rendement']}} tonne(s)</strong></p>
                            <div class="hr mt-2"></div>
                            <div class="pt-2">
                                <ul class="nav nav-underline nav-component border-bottom justify-content-start" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab_etat" type="button" role="tab" aria-controls="tab_etat" aria-selected="true">ETAT DU VERGER</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_obs" type="button" role="tab" aria-controls="tab_obs" aria-selected="false" tabindex="-1">OBSERVERVATIONS</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_travaux" type="button" role="tab" aria-controls="tab_travaux" aria-selected="false" tabindex="-1">TRAVAUX</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_traitements" type="button" role="tab" aria-controls="tab_traitements" aria-selected="false" tabindex="-1">TRAITEMENTS</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab_visites" type="button" role="tab" aria-controls="tab_visites" aria-selected="false" tabindex="-1">VISITES</button>
                                        </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="_tab_etat" class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab">
                                        <p>
                                            <div class="rounded-2 bg-light p-4">
                                                <h6 class="fs-5">Etat global du verger</h6>
                                                {{ $cmp['etat'] }}
                                            </div>
                                        </p>
                                    </div>
                                    <div id="_tab_obs" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                        <p>
                                            <div class="rounded-2 bg-light p-4">
                                                <h6 class="fs-5">Observations</h6>
                                                {{ $cmp['observations'] }}
                                            </div>
                                        </p>
                                    </div>
                                    <div id="_tab_travaux" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>DATE</th>
                                                            <th>TYPE</th>
                                                            <th>METHODE</th>
                                                            <th>NOTES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($cmp['travaux'] as $trv)
                                                            <tr>
                                                                <td>{{$trv['date']}}</td>
                                                                <td>{{$trv['type']}}</td>
                                                                <td>{{$trv['methode']}}</td>
                                                                <td class="fs-6">{{$trv['description']}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                    <div id="_tab_traitements" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="p-4 table-responsive">
                                                <h6 class="fs-5">Traitements</h6>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr class="border-bottom border-3 border-black">
                                                            <th>DATE</th>
                                                            <th>PRODUIT</th>
                                                            <th>DOSAGE</th>
                                                            <th>NOTES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($cmp['traitements'] as $trv)
                                                            <tr>
                                                                <td>{{$trv['date']}}</td>
                                                                <td>{{$trv['produit']}}</td>
                                                                <td>{{$trv['dosage']}}</td>
                                                                <td class="fs-6">{{$trv['description']}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                    <div id="_tab_visites" class="tab-pane fade" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>DATE</th>
                                                        <th>LIBELLE</th>
                                                        <th>TECHNICIEN</th>
                                                        <th>PV</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($cmp['visites'] as $trv)
                                                        <tr>
                                                            <td>{{$trv['date']}}</td>
                                                            <td>{{$trv['name']}}</td>
                                                            <td>{{$trv['technicien']}}</td>
                                                            <td class="fs-6">{{$trv['description']}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                       @endforeach
                    </div>
                </div>
            </div>
       </div>
    </div>

    <hr>
    <div>
        <div style="width: 90%;" id="map"></div>
    </div>


    <style>
        label{
            font-weight: 500;
            font-size: .75rem;
        }

        p{
            margin-top: 1rem;
        }
    </style>

    <script>
        $('.btn-cmp').click(function(){
            var id = $(this).data('id');
            $('.campagne_id').val(id);
        })
    </script>

        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        /* $(document).ready(function(){
                    const latitude = "{{ $item->latitude }}";
                    const longitude = "{{ $item->longitude }}";
                    console.log(latitude)
                     const map = L.map('map').setView([4.5, 12], 7); // Vue centrée sur le Cameroun
                    //const map = L.map('map').setView([latitude, longitude], 8);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
                        maxZoom: 18
                    }).addTo(map);

                    const markerGroup = L.featureGroup().addTo(map);
                    const marker = L.marker([verger.latitude, verger.longitude]).addTo(markerGroup);
                    marker.bindPopup(`
                        <strong>{{$item->name}}</strong><br>
                        Latitude : {{$item->latitude}}<br>
                        Longitude : {{$item->longitude}}<br>
                    `);

        }) */
    </script>

@endsection

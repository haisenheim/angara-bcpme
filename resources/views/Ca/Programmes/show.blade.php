@extends('Layouts.ca')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Programmes</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
 </nav>
@endsection


@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Fiche signalitique du programme</p>
    </div>
@endsection

@section('content')

    <div class="d-flex gap-2">
        <div style="height: 80vh; overflow: scroll;" class="card w-400px">
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>Designation</td>
                            <th>{{ $item->name }}</th>
                        </tr>
                        <tr>
                            <td>&numero; Référence de la Convention cadre du programme </td>
                            <th>{{ $item->convention}}</th>
                        </tr>
                        <tr>
                            <td>Date de signature de la convention cadre </td>
                            <th>{{ \Carbon\Carbon::parse($item->dt_sig_conv)->format('d/m/Y')}}</th>
                        </tr>
                        <tr>
                            <td>Institution signataire </td>
                            <th>{{ $item->signataire }}</th>
                        </tr>
                        <tr>
                            <td>Budget des appuis financiers</td>
                            <th>{{ number_format($item->budget_af,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Budget des appuis non financiers</td>
                            <th>{{ number_format($item->budget_anf,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Budget de la coordination</td>
                            <th>{{ number_format($item->budget_coord,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Budget total</td>
                            <th>{{ number_format($item->budget,0,',','.') }} XAF</th>
                        </tr>
                        <tr>
                            <td>Bénéficiaires cibles personnes morales</td>
                            <th>{{ $item->type_pm }}</th>
                        </tr>
                        <tr>
                            <td>Bénéficiaires cibles personnes physiques</td>
                            <th>{{ $item->type_pp }}</th>
                        </tr>
                        <tr>
                            <td>Date début des activités </td>
                            <th>{{ \Carbon\Carbon::parse($item->dt_start)->format('d/m/Y') }}</th>
                        </tr>
                        <tr>
                            <td>Personnes ressources et contact du programme</td>
                            <th>{{ $item->contact }}</th>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="card flex-fill">
            <div class="card-body">
                <div class="tab-base">
                    <!-- Nav tabs -->
                    <ul class="nav nav-underline nav-component border-bottom" role="tablist">
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3 active" data-bs-toggle="tab" data-bs-target="#_tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Secteurs cibles</button>
                       </li>
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false" tabindex="-1">Appuis proposés</button>
                       </li>
                       <li class="nav-item" role="presentation">
                          <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false" tabindex="-1">Les composantes</button>
                       </li>
                       <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab4" type="button" role="tab" aria-controls="tab4" aria-selected="false" tabindex="-1">Resultats attendus</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3" data-bs-toggle="tab" data-bs-target="#_tab5" type="button" role="tab" aria-controls="tab5" aria-selected="false" tabindex="-1">Entreprises</button>
                         </li>
                    </ul>


                    <!-- Tabs content -->
                    <div class="tab-content">
                       <div id="_tab1" class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab">

                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Filiere</th>
                                        <th>Branche</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->produits as $p)
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->filiere?->name }}</td>
                                            <td>{{ $p->branche?->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                       </div>
                       <div id="_tab2" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab">

                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Nature</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item->appuis as $service)
                                        <tr>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->type?->name }}</td>
                                            <td>{{ $service->financier?'Financier':'Non financier' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                       </div>
                       <div id="_tab3" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Entité</th>
                                    <th>Nature</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->composantes as $cmp)
                                    <tr>
                                        <td>{{ $cmp->name }}</td>
                                        <td>{{ $cmp->type }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                       </div>
                       <div id="_tab4" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">

                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Indicateur</th>
                                    <th>Attentes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->resultats as $cmp)
                                    <tr>
                                        <td>{{ $cmp->indicateur?->name }}</td>
                                        <td>{{ $cmp->attente }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                       </div>
                       <div id="_tab5" class="tab-pane fade" role="tabpanel" aria-labelledby="contact-tab">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Localité</th>
                                    <th>Taille</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->entreprises as $ent)
                                    <tr>
                                        <td>{{ $ent->name }}</td>
                                        <td>{{ $ent->localite }}</td>
                                        <td>{{ $ent->taille }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                       </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
@endsection



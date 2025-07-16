@extends('../Layouts.tenant.admin')

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
    <div>
        <div class="d-flex gap-3">
            <div class="w-25">
                <div class="card">
                    <div class="card-body text-center hv-outline-parent">
                        <img src="{{ $user->photo }}" width="100" class="rounded-circle hv-oc mb-3" alt="">
                        <p class="lead">Hello <span class="fw-bold text-primary">{{ $user->name }}</span>, bienvenu sur <span class="text-muted">Angara</span> votre plateforme de trading de la feve!</p>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <img class="card-img" height="100"  src="{{ $user->cooperative->photo }}" alt="">
                        </div>
                        <h5>{{ $user->cooperative->name }}</h5>
                    </div>
                </div>
            </div>
            <div class="flex-fill">
                <div class="d-flex gap-1">
                    <div class="card bg-light flex-fill text-primary">
                        <div class="card-body text-center">
                            <div>
                                <i class="pli-conference fs-2"></i>
                            </div>
                            <div>
                                <span>459</span> <span>ADHERENTS</span>
                            </div>
                        </div>
                        <div class="card-footer text-primary text-center">
                            <p>EFFECTIFS</p>
                        </div>
                    </div>
                    <div class="card bg-cyan text-white flex-fill">
                        <div class="card-body text-center">
                            <div>
                                <i class="psi-bucket fs-1 text-white"></i>
                            </div>
                            <div>
                                <span id="text-quantity">43</span> <span>tonnes</span>
                            </div>
                        </div>
                        <div class="card-footer text-center text-white">
                            <p class="fs-5 fw-semibold">Total des ventes</p>
                        </div>
                    </div>

                    <div class="card bg-teal text-white flex-fill">
                        <div class="card-body text-center">
                            <div>
                                <i class="pli-coins fs-1 text-white"></i>
                            </div>
                            <div class="fs-5">
                                <span id="text-total">560.000.000</span> <span>FCFA</span>
                            </div>
                        </div>
                        <div class="card-footer text-center text-white">
                            <p class="fs-5 fw-semibold">Valeur des ventes</p>
                        </div>
                    </div>

                    <div class="card bg-danger text-white flex-fill">
                        <div class="card-body text-center">
                            <div>
                                <i class="pli-money-2 fs-1 text-white"></i>
                            </div>
                            <div class="fs-5">
                                <span id="text-total">410.000.000</span> <span>FCFA</span>
                            </div>
                        </div>
                        <div class="card-footer text-center text-white">
                            <p class="fs-5 fw-semibold">Total des paiements</p>
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex gap-2">
                        <div class="card text-center flex-fill">
                            <div class="card-body">
                                <h6>Calendrier des marches</h6>
                                @if($calendrier)
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Lieu</th>
                                    <th>Villages Concernés</th>
                                    <th>Stock attendu</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach($calendrier->items as $item)
                                        <tr>
                                            <td>{{ $item->day? \Carbon\Carbon::parse($item->day)->format('d/m/Y'):'-' }}</td>
                                            <td>{{ $item->lieu }}</td>
                                            <td>
                                                @foreach($item->villages as $village)
                                                    <span class="badge bg-primary">{{ $village->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $item->prevision?$item->prevision->quantity." tonnes":'non defini' }}</td>

                                        </tr>
                                    @endforeach


                            </tbody>
                        </table>
                        @else
                            <div class="">
                                <div class="text-center">
                                    <p>AUCUN CALENDRIER LIE A LA SAISON EN COURS</p>
                                </div>
                            </div>
                        @endif
                            </div>
                        </div>
                        <div class="card text-center flex-fill">
                            <div class="card-body">
                                <h6>Previsions</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

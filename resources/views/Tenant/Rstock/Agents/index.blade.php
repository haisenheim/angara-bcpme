@extends('../Layouts.tenant.rstock')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Angara</a></li>
       <li class="breadcrumb-item"><a href="#">Agents de terrain</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des agents de terrains</li>
    </ol>
 </nav>
@endsection

@section('actions')
    <a href="#" data-bs-target="#addModal" data-bs-toggle="modal" class="btn btn-primary btn-sm"><i class="demo-pli-add me-2 fs-5"></i> Créer un agent</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Agents de terrain </h5>
        <p class="lead">Liste des agents de terrain  </p>
    </div>
@endsection

@section('content')
    <div class="container d-flex gap-4">
        @foreach ($items as $agent)
        <div class="card mb-3 w-250px">
            <div class="card-body">
               <!-- Profile picture and short information -->
               <div class="text-center position-relative hv-outline-parent hv-grow-parent">
                  <div class="pt-2 pb-3">
                     <img class="img-lg hv-oc hv-gc rounded-circle" src="{{ $agent->photo }}" alt="Profile Picture" loading="lazy">
                  </div>
                  <a href="#" class="h5 stretched-link btn-link">{{ $agent->name }}</a>
                  <p class="text-body-secondary">{{ $agent->phone }}</p>
               </div>
               <!-- END : Profile picture and short information -->


               <!-- Social media buttons -->
               <div class="mt-4 pt-3 d-flex justify-content-around border-top">
                  <div class="text-center">
                     <h5 class="mb-0">1.345 XAF</h5>
                     <small class="text-body-secondary">SOLDE</small>
                  </div>
                  <div class="text-center">
                     <h5 class="mb-0">23k</h5>
                     <small class="text-body-secondary">Collecte</small>
                  </div>
                  <div class="text-center">
                     <h5 class="mb-0">34.000 XAF</h5>
                     <small class="text-body-secondary">Paiements</small>
                  </div>
               </div>
               <!-- END : Social media buttons -->


            </div>
         </div>
        @endforeach
    </div>

    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title">Nouvel agent</h5>
                    <div style="float: right">
                        <button data-bs-dismiss="modal" class="btn btn-sm" >x</button>
                    </div>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('rstock.agents.store') }}" method="post">
                        @csrf
                            <div class="d-flex gap-2 flex-grow">
                                <div class="w-50">
                                    <label for="">NOM</label>
                                    <input required type="text" name="name" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">TELEPHONE</label>
                                    <input required type="text" name="phone" class="form-control">
                                </div>
                                <div class="w-25">
                                    <label for="">PHOTO</label>
                                    <input type="file" name="photo" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3 flex-grow">
                                <div class="w-50">
                                    <label for="">EMAIL DE CONNEXION</label>
                                    <input required type="text" name="email" class="form-control">
                                </div>
                                <div class="w-50">
                                    <label for="">MOT DE PASSE</label>
                                    <input required type="password" name="password" class="form-control">
                                </div>
                            </div>

                        <div class="mt-5">
                            <button type="submit" class="btn-success btn btn-sm"><i class="pli-add"></i> Inserer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

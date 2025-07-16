@extends('Layouts.ca')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">ANGARA</a></li>
       <li class="breadcrumb-item"><a href="{{ route('analyste.entreprises.show',$entreprise->token) }}">{{ $entreprise->name }}</a></li>
       <li class="breadcrumb-item active" aria-current="page">Etat des engagements</li>
    </ol>
 </nav>
@endsection


@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div style="max-width:1000px" class="card">
                <div class="card-header p-4">
                    <h4 class="text-center mb-0">ETAT DES ENGAGEMENTS</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table sm table-bordered">
                        <thead>
                            <tr>
                                <th colspan="1"></th>
                                <th colspan="3">ENCOURS</th>
                                <th colspan="3">SOLLICITES</th>
                                <th colspan="2">TOTAL</th>
                            </tr>
                            <tr>
                                <th>ENGAGEMENT</th>
                                <th>MONTANT</th>
                                <th>IMPAYES</th>
                                <th>DATE DE VALIDITE</th>

                                <th>MONTANT</th>
                                <th>DATE DE VALIDITE</th>
                                <th>VARIATION</th>

                                <th>MONTANT</th>
                                <th>DATE DE VALIDITE</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($engagements as $eng)
                                <x-engagement :eng="json_encode($eng)"></x-engagement>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <script>
        $('.btn-edit').click(function(){
            var id = $(this).data('engagement_id')
            $('#engagement_id').val(id)
        })
    </script>

    <style>
        .table th{
            border: var(--bs-border-width) var(--bs-border-style) #555 !important;
            font-weight: 900;
        }

        .table td{
            border: var(--bs-border-width) var(--bs-border-style) #888 !important;
            font-weight: normal;
        }

        th.vertical-align{
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
    </style>

@endsection



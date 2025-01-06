@extends('Layouts.regional')

@section('title', 'Accueil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="#">Cogelo</a></li>
       <li class="breadcrumb-item"><a href="#">Organisation du territoire</a></li>
       <li class="breadcrumb-item active" aria-current="page">Liste des localites</li>
    </ol>
 </nav>
 <link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/default/easyui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('jquery-easyui/themes/icon.css') }}">
<script type="text/javascript" src="{{ asset('jquery-easyui/jquery.easyui.min.js') }}"></script>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Organisation du territoire</h5>
        <p class="lead">Liste de toutes les communes</p>
    </div>
@endsection



@section('content')
    <div class="card">
        <div class="card-body">

            <div style="margin:20px 0;"></div>
            <table class="easyui-treegrid" style="width:80%;height:400px;"
                    data-options="
                        rownumbers: true,
                        animate: true,
                        collapsible: true,
                        fitColumns: true,
                        url: '{{ route('util.localites') }}',
                        method: 'get',
                        lines: true,
                        rownumbers: true,
                        idField: 'id',
                        treeField: 'name',
                    ">
                <thead>
                    <tr>
                        <th data-options="field:'name'" width="220">Name</th>
                        <th data-options="field:'nb'" width="220">Circonscriptions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


@endsection

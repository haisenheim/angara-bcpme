@extends('../Layouts.tenant.admin')

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
@section('actions')
<div class="btn-group">
    <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
    Actions
    <span class="vr"></span>
    </button>
    <ul class="dropdown-menu analyse">
        <li><a class="dropdown-item"  href="{{ route('admin.requests.create') }}">Faire un appel de fonds</a></li>
    </ul>
</div>
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
                    <tr class="fs-6 fw-bold border border-2">
                        <th>DATE</th>
                        <th>WALLET</th>
                        <th>CAISSE</th>
                        <th>MONTANT</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                       <tr class="fs-6 border">
                            <td>{{ $item->created_at->format('d/m/Y à H:i') }}</td>
                            <td>{{ $item->wallet?->name }}</td>
                            <td>{{ $item->caisse?->name }}</td>
                            <td>{{ number_format($item->montant,0,',','.') }}</td>
                            <td><span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

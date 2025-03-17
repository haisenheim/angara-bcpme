<?php $data = json_decode($eng,true) ?>
<tr class="fw-{{ $data['niveau'] }} fs-{{ $data['isLeaf']?'leaf':'title' }}">
    <td>{{ $data['name'] }}</td>
    <td>{{ $data['encoursMontant'] }}</td>
    <td>{{ $data['encoursImpaye'] }}</td>
    <td>{{ \Carbon\Carbon::parse($data['encoursDtValidite'])->format('d/m/Y') }}</td>
    <td>{{ $data['solliciteMontant'] }}</td>
    <td>{{ \Carbon\Carbon::parse($data['solliciteDtValidite'])->format('d/m/Y') }}</td>
    <td>{{ $data['variation'] }}</td>
    <td>{{ $data['totalMontant'] }}</td>
    <td></td>
    <td>
        @if($data['isLeaf'])
            <div class="btn-group">
                <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle hstack gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                Actions
                <span class="vr"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Afficher les details</a></li>
                    <li><a class="dropdown-item btn-edit" data-engagement_id="{{ $data['id'] }}" data-bs-target="#editModal" data-bs-toggle="modal" href="#">Editer</a></li>
                </ul>
            </div>
         @endif
    </td>
</tr>
@if(isset($data['children']))
    @foreach ($data['children'] as $child)
        <x-engagement :eng="json_encode($child)"></x-engagement>
    @endforeach
@endif

<style>
    .fw-0{
        font-size: 14px;
        font-weight: 700;
    }
    .fw-1{
        font-size: 13px;
        font-weight: 600;
        background: #cccccc
    }
    .fw-2{
        font-size: 12px;
        font-weight: 500;
    }

    .fw-3{
        font-size: 10px;
        font-weight: 400;
    }

    .fs-leaf{
        font-size: 11px;
        font-weight: 400;
    }

</style>

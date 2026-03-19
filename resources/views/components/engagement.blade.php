<?php $data = json_decode($eng, true); ?>
<tr class="engagement-row fw-{{ $data['niveau'] ?? 0 }} fs-{{ ($data['is_leaf'] ?? false) ? 'leaf' : 'title' }}">
    <td>{{ $data['name'] ?? '' }}</td>
    <td class="text-end">{{ number_format($data['encours_montant'] ?? 0, 0, ',', ' ') }}</td>
    <td class="text-end">{{ number_format($data['encours_impaye'] ?? 0, 0, ',', ' ') }}</td>
    <td>—</td>
    <td class="text-end">{{ number_format($data['sollicite_montant'] ?? 0, 0, ',', ' ') }}</td>
    <td>—</td>
    <td class="text-end fw-medium">{{ number_format($data['variation'] ?? 0, 0, ',', ' ') }}</td>
    <td class="text-end">{{ number_format(($data['encours_montant'] ?? 0) + ($data['sollicite_montant'] ?? 0), 0, ',', ' ') }}</td>
    <td>—</td>
    <td class="text-nowrap">
        @if(($data['is_leaf'] ?? false) || isset($data['children']))
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="demo-psi-two-column-layout me-1"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item btn-engagement-details" href="#" data-engagement='@json($data)'>
                            <i class="demo-psi-eye me-2"></i> Afficher les détails
                        </a>
                    </li>
                    @if(($data['is_leaf'] ?? false) && $canEdit)
                        <li><a class="dropdown-item btn-edit" data-engagement_id="{{ $data['id'] }}" data-bs-target="#editModal" data-bs-toggle="modal" href="#"><i class="demo-psi-pen-5 me-2"></i> Éditer</a></li>
                    @endif
                </ul>
            </div>
        @endif
    </td>
</tr>
@if(isset($data['children']))
    @foreach ($data['children'] as $child)
        <x-engagement :eng="json_encode($child)" :can-edit="$canEdit" />
    @endforeach
@endif

<style>
    .engagement-row.fw-0 { font-size: 14px; font-weight: 700; }
    .engagement-row.fw-1 { font-size: 13px; font-weight: 600; background: #f8f9fa; }
    .engagement-row.fw-2 { font-size: 12px; font-weight: 500; }
    .engagement-row.fw-3 { font-size: 11px; font-weight: 400; }
    .engagement-row.fs-leaf { font-size: 11px; font-weight: 400; }
</style>

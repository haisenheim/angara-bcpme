@php
    $reports = config('engagements_exports.reports', []);
@endphp

<div class="dropdown">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
            id="engagementExportPdfDropdown"
            data-bs-toggle="dropdown"
            data-bs-auto-close="true"
            aria-expanded="false">
        <i class="demo-psi-file me-1"></i>Exporter PDF
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="engagementExportPdfDropdown" style="min-width: 17rem;">
        @foreach ($reports as $report)
            @continue(empty($report['pdf']))
            @if (! empty($report['enabled']))
                <li>
                    <button type="button"
                            class="dropdown-item text-start engagement-export-link py-2"
                            data-format="pdf"
                            data-report="{{ $report['id'] }}">
                        <span class="fw-medium">{{ $report['label'] }}</span>
                        @if (! empty($report['description']))
                            <small class="text-muted d-block mt-1">{{ $report['description'] }}</small>
                        @endif
                    </button>
                </li>
            @else
                <li>
                    <span class="dropdown-item disabled text-muted pe-none py-2 mb-0">
                        <span class="fw-medium">{{ $report['label'] }}</span>
                        @if (! empty($report['description']))
                            <small class="text-muted d-block mt-1">{{ $report['description'] }}</small>
                        @endif
                        <small class="d-block mt-1 fst-italic">À venir</small>
                    </span>
                </li>
            @endif
        @endforeach
    </ul>
</div>

<div class="dropdown">
    <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button"
            id="engagementExportExcelDropdown"
            data-bs-toggle="dropdown"
            data-bs-auto-close="true"
            aria-expanded="false">
        <i class="demo-psi-file-excel me-1"></i>Exporter Excel
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="engagementExportExcelDropdown" style="min-width: 17rem;">
        @foreach ($reports as $report)
            @continue(empty($report['xlsx']))
            @if (! empty($report['enabled']))
                <li>
                    <button type="button"
                            class="dropdown-item text-start engagement-export-link py-2"
                            data-format="xlsx"
                            data-report="{{ $report['id'] }}">
                        <span class="fw-medium">{{ $report['label'] }}</span>
                        @if (! empty($report['description']))
                            <small class="text-muted d-block mt-1">{{ $report['description'] }}</small>
                        @endif
                    </button>
                </li>
            @else
                <li>
                    <span class="dropdown-item disabled text-muted pe-none py-2 mb-0">
                        <span class="fw-medium">{{ $report['label'] }}</span>
                        @if (! empty($report['description']))
                            <small class="text-muted d-block mt-1">{{ $report['description'] }}</small>
                        @endif
                        <small class="d-block mt-1 fst-italic">À venir</small>
                    </span>
                </li>
            @endif
        @endforeach
    </ul>
</div>

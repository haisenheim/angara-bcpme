@php
    $docTplRoute = request()->route()?->getName() ?? '';
    $docTplActive = str_starts_with((string) $docTplRoute, 'document-templates');
@endphp
<li class="nav-item">
    <a href="{{ route('document-templates.index') }}" class="nav-link mininav-toggle {{ $docTplActive ? 'active' : '' }}" {{ $docTplActive ? 'aria-current="page"' : '' }}>
        <i class="bi bi-file-earmark-text fs-4 me-2"></i>
        <span class="nav-label mininav-content ms-1">Modèles de documents</span>
    </a>
</li>

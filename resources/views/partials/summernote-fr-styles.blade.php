@php
    /** @var string $variant lite | bs5 — bs5 requis avec Bootstrap 5 (toolbar / listes / gras) */
    $summernoteVariant = $variant ?? 'lite';
@endphp
@if($summernoteVariant === 'bs5')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css">
@else
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css">
@endif
<style>
.summernote-wrapper .note-editor.note-frame {
    border-color: #d8dee6;
    border-radius: 0.75rem;
    isolation: isolate;
}

.summernote-wrapper .note-toolbar {
    background: #f8fafc;
    border-bottom-color: #e5e7eb;
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    @if($summernoteVariant === 'bs5')
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    gap: 0.35rem;
    @else
    display: block !important;
    @endif
    visibility: visible !important;
    opacity: 1 !important;
    position: relative;
    z-index: 5;
}

.summernote-wrapper .note-editor.note-frame .note-editing-area {
    position: relative;
    z-index: 1;
}

.summernote-wrapper .note-editor.note-frame .note-btn-group.open .note-dropdown-menu,
.summernote-wrapper .note-dropdown-menu {
    z-index: 2050;
}

.summernote-wrapper .note-editing-area .note-editable {
    min-height: 180px;
    color: #1f2937;
}

.summernote-wrapper--compact .note-editing-area .note-editable {
    min-height: 100px;
}

.summernote-wrapper .note-editable strong,
.summernote-wrapper .note-editable b {
    font-weight: 700;
}

.summernote-wrapper .note-editable em,
.summernote-wrapper .note-editable i {
    font-style: italic;
}

.summernote-wrapper .note-editable ul {
    list-style-type: disc;
    padding-left: 1.5em;
}

.summernote-wrapper .note-editable ol {
    list-style-type: decimal;
    padding-left: 1.5em;
}

.summernote-wrapper .note-editable li {
    display: list-item;
    list-style: inherit;
}

{{-- Ne pas forcer display sur .note-toolbar : Summernote BS5 exige flex sur la barre d’outils (gras, listes, menus). --}}
.summernote-wrapper .note-editor .note-statusbar {
    display: block !important;
}

.summernote-wrapper .note-editor .note-btn-group {
    display: inline-flex !important;
    align-items: center;
    gap: 0.25rem;
    margin-right: 0.4rem;
}

.summernote-wrapper .note-editor .note-btn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    min-width: 2rem;
    min-height: 2rem;
    color: #334155 !important;
    background: #fff !important;
    border: 1px solid #d8dee6 !important;
    border-radius: 0.5rem !important;
}

.summernote-wrapper .note-editor .note-btn:hover,
.summernote-wrapper .note-editor .note-btn:focus {
    color: #17310b !important;
    background: rgba(136, 184, 36, 0.10) !important;
    border-color: rgba(136, 184, 36, 0.45) !important;
}

.summernote-wrapper .note-editor .dropdown-toggle::after {
    margin-left: 0.35rem;
}

.summernote-wrapper .note-editor .note-icon-caret,
.summernote-wrapper .note-editor [class^="note-icon-"],
.summernote-wrapper .note-editor [class*=" note-icon-"] {
    display: inline-block !important;
    visibility: visible !important;
}
</style>

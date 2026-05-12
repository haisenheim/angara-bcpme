<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Support\UserWorkspaceContextResolver;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentTemplateLibraryController extends Controller
{
    public function index(): View
    {
        $templates = DocumentTemplate::query()
            ->orderByDesc('sort_order')
            ->orderBy('title')
            ->get();

        return view('document_templates.index', [
            'templates' => $templates,
            'workspaceLayout' => UserWorkspaceContextResolver::layout(auth()->user()),
        ]);
    }

    public function download(DocumentTemplate $documentTemplate)
    {
        $disk = Storage::disk($documentTemplate->disk);
        if (! $disk->exists($documentTemplate->storage_path)) {
            abort(404, 'Fichier introuvable.');
        }

        return $disk->download($documentTemplate->storage_path, $documentTemplate->original_filename);
    }
}

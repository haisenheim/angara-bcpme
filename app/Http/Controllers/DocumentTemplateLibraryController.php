<?php

namespace App\Http\Controllers;

use App\Models\DocumentTemplate;
use App\Support\UserWorkspaceContextResolver;
use Illuminate\Support\Facades\Storage;
use App\Services\Inertia\Admin\CrudKit;
use Inertia\Inertia;
use Inertia\Response;

class DocumentTemplateLibraryController extends Controller
{
    public function index(): Response
    {
        $templates = DocumentTemplate::query()
            ->orderByDesc('sort_order')
            ->orderBy('title')
            ->get();

        $prefix = UserWorkspaceContextResolver::routePrefix(auth()->user());
        $dashboard = $prefix && \Illuminate\Support\Facades\Route::has($prefix.'.dashboard') ? route($prefix.'.dashboard') : route('home');

        return Inertia::render('Documents/Index', [
            'templates' => $templates->map(fn (DocumentTemplate $t) => [
                'id' => $t->id,
                'title' => $t->title,
                'description' => $t->description,
                'filename' => $t->original_filename,
                'size' => CrudKit::fileSize($t->size_bytes),
                'extension' => strtolower(pathinfo((string) $t->original_filename, PATHINFO_EXTENSION)),
                'download' => route('document-templates.download', $t),
            ])->values()->all(),
            'manageUrl' => (int) auth()->user()->role_id === 1 ? route('admin.document-templates.index') : null,
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => $dashboard],
                ['label' => 'Modèles de documents'],
            ],
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentTemplateRequest;
use App\Models\DocumentTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentTemplateController extends Controller
{
    public function index(): View
    {
        $items = DocumentTemplate::query()
            ->with('uploadedBy:id,name')
            ->orderByDesc('sort_order')
            ->orderBy('title')
            ->get();

        return view('Admin.DocumentTemplates.index', compact('items'));
    }

    public function create(): View
    {
        return view('Admin.DocumentTemplates.create');
    }

    public function store(StoreDocumentTemplateRequest $request): RedirectResponse
    {
        $upload = $request->file('file');
        $disk = 'local';
        $directory = 'document_templates';

        $path = $upload->store($directory, $disk);
        if ($path === false) {
            return redirect()
                ->route('admin.document-templates.create')
                ->withInput()
                ->withErrors(['file' => 'Échec de l’enregistrement du fichier sur le serveur.']);
        }

        DocumentTemplate::query()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'original_filename' => $upload->getClientOriginalName(),
            'disk' => $disk,
            'storage_path' => $path,
            'mime_type' => $upload->getMimeType(),
            'size_bytes' => $upload->getSize(),
            'sort_order' => (int) ($request->input('sort_order') ?? 0),
            'uploaded_by_user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.document-templates.index')
            ->with('success', 'Modèle de document ajouté.');
    }

    public function destroy(DocumentTemplate $document_template): RedirectResponse
    {
        if (Storage::disk($document_template->disk)->exists($document_template->storage_path)) {
            Storage::disk($document_template->disk)->delete($document_template->storage_path);
        }
        $document_template->delete();

        return redirect()
            ->route('admin.document-templates.index')
            ->with('success', 'Modèle supprimé.');
    }
}

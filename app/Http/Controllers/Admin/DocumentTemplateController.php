<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentTemplateRequest;
use App\Models\DocumentTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Inertia\Inertia;
use Inertia\Response;

class DocumentTemplateController extends Controller
{
    public function index(): Response
    {
        $items = DocumentTemplate::query()
            ->with('uploadedBy:id,name')
            ->orderByDesc('sort_order')
            ->orderBy('title')
            ->get();

        $table = DataTable::make(K::singlePage($items))
            ->columns([
                ['key' => 'title', 'label' => 'Titre'],
                ['key' => 'file', 'label' => 'Fichier d’origine'],
                ['key' => 'size', 'label' => 'Taille', 'align' => 'right'],
                ['key' => 'order', 'label' => 'Ordre', 'align' => 'center'],
            ])
            ->row(fn (DocumentTemplate $row) => [
                'cells' => [
                    'title' => ['title' => $row->title, 'subtitle' => $row->description],
                    'file' => $row->original_filename,
                    'size' => K::fileSize($row->size_bytes) ?? '—',
                    'order' => $row->sort_order,
                ],
                'actions' => [
                    ['key' => 'download', 'label' => 'Télécharger', 'icon' => 'download', 'href' => route('document-templates.download', $row), 'download' => true],
                    K::destroy(route('admin.document-templates.destroy', $row), 'Supprimer ce modèle ?'),
                ],
            ])
            ->empty('Aucun modèle pour le moment. Ajoutez un fichier avec le bouton « Ajouter un fichier ».');

        return Inertia::render('Admin/Crud', [
            'title' => 'Modèles de documents',
            'subtitle' => 'Documents téléchargeables par tous les utilisateurs connectés.',
            'countLabel' => 'modèle(s)',
            'table' => $table->toArray(),
            'actions' => [
                ['key' => 'library', 'label' => 'Bibliothèque (vue métier)', 'icon' => 'open_in_new', 'variant' => 'secondary', 'href' => route('document-templates.index'), 'external' => true],
                K::link('create', 'Ajouter un fichier', route('admin.document-templates.create'), 'add', 'primary'),
            ],
            'breadcrumb' => [
                ['label' => 'Administration', 'href' => route('admin.dashboard')],
                ['label' => 'Modèles de documents'],
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Form', [
            'title' => 'Nouveau modèle',
            'subtitle' => 'Le fichier sera stocké sur le serveur et proposé au téléchargement à tous les profils.',
            'form' => [
                'narrow' => true,
                'fields' => [
                    K::field('title', 'Titre affiché', 'text', ['required' => true, 'maxlength' => 255, 'value' => (string) old('title', '')]),
                    K::field('description', 'Description (optionnel)', 'textarea', ['value' => (string) old('description', '')]),
                    K::field('sort_order', 'Ordre d’affichage', 'number', ['min' => 0, 'max' => 999999, 'step' => 1, 'value' => (string) old('sort_order', 0)]),
                    K::field('file', 'Fichier', 'file', ['required' => true, 'value' => null, 'accept' => '.pdf,.doc,.docx,.xls,.xlsx,.csv,.txt,.png,.jpg,.jpeg,.odt,.ods']),
                ],
                'submit' => ['url' => route('admin.document-templates.store'), 'method' => 'post', 'label' => 'Enregistrer'],
                'cancel' => ['href' => route('admin.document-templates.index'), 'label' => 'Annuler'],
            ],
            'breadcrumb' => [
                ['label' => 'Administration', 'href' => route('admin.dashboard')],
                ['label' => 'Modèles de documents', 'href' => route('admin.document-templates.index')],
                ['label' => 'Ajouter'],
            ],
        ]);
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

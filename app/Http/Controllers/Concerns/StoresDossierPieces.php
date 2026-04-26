<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Dossier;
use App\Models\Fichier;
use App\Models\FichierType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

trait StoresDossierPieces
{
    protected function completeDossierPieceUpload(
        Request $request,
        Dossier $dossier,
        string $redirectRouteName,
        mixed $redirectRouteParameter = null
    ): RedirectResponse {
        $data = $request->validate([
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'type_id' => ['required', 'integer', Rule::exists(FichierType::class, 'id')],
        ]);

        $fileToken = sha1('dossier-piece-'.$dossier->id.'-'.microtime(true));
        $uploaded = $request->file('fichier');
        $relativePath = $this->storeDossierPieceFileOnDisk($uploaded, $fileToken);

        $redirectKey = $redirectRouteParameter ?? $dossier;

        if ($relativePath === null) {
            return redirect()
                ->route($redirectRouteName, $redirectKey)
                ->withInput()
                ->withErrors(['fichier' => 'Format de fichier non accepté (PDF, JPG ou PNG uniquement).']);
        }

        Fichier::create([
            'name' => $relativePath,
            'original_name' => $uploaded->getClientOriginalName(),
            'entreprise_id' => (int) ($dossier->entreprise_id ?? 0),
            'dossier_id' => $dossier->id,
            'type_id' => (int) $data['type_id'],
            'token' => $fileToken,
            'uploaded_at' => now(),
            'uploaded_by_user_id' => auth()->id(),
        ]);

        return redirect()
            ->route($redirectRouteName, $redirectKey)
            ->with('success', 'La pièce a été enregistrée sur le dossier.');
    }

    /**
     * Enregistre sous public/files/dossiers_pieces/ (PDF ou image).
     */
    private function storeDossierPieceFileOnDisk(UploadedFile $file, string $basename): ?string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
        if (! in_array($ext, $allowed, true)) {
            return null;
        }

        $entity = 'dossiers_pieces';
        $dir = public_path('files/'.$entity);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = $basename.'.'.$ext;
        $full = $dir.DIRECTORY_SEPARATOR.$filename;
        if (is_file($full)) {
            @unlink($full);
        }

        $file->move($dir, $filename);

        return $entity.'/'.$filename;
    }
}

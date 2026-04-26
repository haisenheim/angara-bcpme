<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Services\AnalyseCritiqueService;
use App\Services\InstructionDelegationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class InstructionClosureController extends Controller
{
    public function __construct(
        private readonly InstructionDelegationService $delegationService,
        private readonly AnalyseCritiqueService $analyseCritiqueService,
    ) {}

    public function approve(Request $request, string $token)
    {
        $data = $request->validate([
            'instruction_closure_note' => 'nullable|string|max:5000',
        ]);

        $central = 'central_app_mysql';
        $dossier = Dossier::on($central)->where('token', $token)->firstOrFail();

        if (! $this->delegationService->userCanCloseInstruction(auth()->user(), $dossier)) {
            Session::flash('info', 'Votre profil n’est pas habilité à clôturer ce dossier d’instruction (délégation de pouvoir).');

            return back();
        }

        $closedNow = false;
        DB::connection($central)->transaction(function () use ($dossier, $data, &$closedNow, $central) {
            $dLocked = Dossier::on($central)->whereKey($dossier->id)->lockForUpdate()->firstOrFail();
            if ($dLocked->isInstructionClosed()) {
                return;
            }

            $dLocked->instruction_closure_validated_at = now();
            $dLocked->instruction_closure_validated_by_user_id = auth()->id();
            $dLocked->instruction_closure_note = $data['instruction_closure_note'] ?? null;
            $dLocked->save();
            $closedNow = true;
        });

        if (! $closedNow) {
            Session::flash('info', 'Ce dossier est déjà clos (ou en cours de clôture).');

            return back();
        }

        $dossier->refresh();
        $this->analyseCritiqueService->syncInstructionDossier($dossier, 'Clôture du dossier d’instruction (délégation de pouvoir).');
        Session::flash('success', 'Dossier d’instruction clos.');

        return back();
    }

    public function reject(Request $request, string $token)
    {
        $data = $request->validate([
            'instruction_closure_reject_motif' => 'nullable|string|max:5000',
            'instruction_closure_note' => 'nullable|string|max:5000',
        ]);

        $central = 'central_app_mysql';
        $dossier = Dossier::on($central)->where('token', $token)->firstOrFail();

        if (! $this->delegationService->userCanRejectInstruction(auth()->user(), $dossier)) {
            Session::flash('info', 'Votre profil n’est pas habilité à rejeter la clôture de ce dossier d’instruction (délégation de pouvoir).');

            return back();
        }

        $rejectedNow = false;
        DB::connection($central)->transaction(function () use ($dossier, $data, &$rejectedNow, $central) {
            $dLocked = Dossier::on($central)->whereKey($dossier->id)->lockForUpdate()->firstOrFail();
            if ($dLocked->isInstructionClosed()) {
                return;
            }

            $dLocked->instruction_closure_rejected_at = now();
            $dLocked->instruction_closure_rejected_by_user_id = auth()->id();
            $dLocked->instruction_closure_reject_motif = $data['instruction_closure_reject_motif'] ?? null;
            $dLocked->instruction_closure_note = $data['instruction_closure_note'] ?? null;
            $dLocked->save();
            $rejectedNow = true;
        });

        if (! $rejectedNow) {
            Session::flash('info', 'Ce dossier est déjà clos (ou en cours de clôture).');

            return back();
        }

        $dossier->refresh();
        $message = 'Rejet de clôture du dossier d’instruction (délégation de pouvoir).';
        if (! empty($data['instruction_closure_reject_motif'])) {
            $message .= "\n\nMotif : ".$data['instruction_closure_reject_motif'];
        }
        $this->analyseCritiqueService->syncInstructionDossier($dossier, $message);
        Session::flash('success', 'Clôture instruction rejetée.');

        return back();
    }
}


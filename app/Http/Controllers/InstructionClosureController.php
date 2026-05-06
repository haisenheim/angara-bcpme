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
        $data = $request->validate(
            [
                'instruction_closure_note' => 'required|string|max:5000',
            ],
            [
                'instruction_closure_note.required' => 'La note de clôture est obligatoire.',
            ]
        );

        if (strlen(trim(strip_tags((string) $data['instruction_closure_note']))) === 0) {
            Session::flash('info', 'La note de clôture est obligatoire (texte vide non accepté).');

            return back()->withInput();
        }

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
        $data = $request->validate(
            [
                'instruction_closure_reject_motif' => 'nullable|string|max:5000',
                'instruction_closure_note' => 'required|string|max:5000',
            ],
            [
                'instruction_closure_note.required' => 'La note de clôture est obligatoire.',
            ]
        );

        if (strlen(trim(strip_tags((string) $data['instruction_closure_note']))) === 0) {
            Session::flash('info', 'La note de clôture est obligatoire (texte vide non accepté).');

            return back()->withInput();
        }

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

    /**
     * Rejet inter-pôle : la direction (DG / DGA / délégué habilité) renvoie le dossier au pôle risques
     * avec motif obligatoire, sans clôturer le dossier (réouverture du pôle risques, prompt l. 220).
     */
    public function rejectToRisques(Request $request, string $token)
    {
        $data = $request->validate(
            ['rejet_motif' => 'required|string|max:5000'],
            ['rejet_motif.required' => 'Le motif du rejet inter-pôle est obligatoire.']
        );

        if (strlen(trim(strip_tags((string) $data['rejet_motif']))) === 0) {
            Session::flash('info', 'Le motif du rejet inter-pôle est obligatoire (texte vide non accepté).');

            return back()->withInput();
        }

        $central = 'central_app_mysql';
        $dossier = Dossier::on($central)->where('token', $token)->firstOrFail();

        if ($dossier->isInstructionClosed()) {
            Session::flash('info', 'Ce dossier est déjà clos.');

            return back();
        }
        if (! $this->delegationService->userCanCloseInstruction(auth()->user(), $dossier)) {
            Session::flash('info', 'Votre profil n’est pas habilité à statuer sur ce dossier (délégation de pouvoir).');

            return back();
        }
        if (! $dossier->isSubmittedToDirectionFromRerx() && ! $dossier->isDirectionRejectedToRisques()) {
            Session::flash('info', 'Ce dossier n’est pas encore arrivé à la direction.');

            return back();
        }
        if ($dossier->isDirectionRejectedToRisques()) {
            Session::flash('info', 'Le dossier a déjà été renvoyé au pôle risques : en attente de retransmission.');

            return back();
        }

        $rejectedNow = false;
        DB::connection($central)->transaction(function () use ($dossier, $data, &$rejectedNow, $central) {
            $dLocked = Dossier::on($central)->whereKey($dossier->id)->lockForUpdate()->firstOrFail();
            if ($dLocked->isInstructionClosed() || $dLocked->isDirectionRejectedToRisques()) {
                return;
            }

            $dLocked->direction_rejected_to_risques_at = now();
            $dLocked->direction_rejected_to_risques_by_user_id = auth()->id();
            $dLocked->direction_rejected_to_risques_motif = $data['rejet_motif'];
            $dLocked->save();
            $rejectedNow = true;
        });

        if (! $rejectedNow) {
            return back();
        }

        $dossier->refresh();

        $mailer = app(\App\Services\WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Rejet inter-pôle — dossier renvoyé au pôle risques',
            title: 'La direction vous renvoie le dossier',
            body: "La direction a rejeté le dossier et vous le renvoie pour révision (sans clôturer). Vous pouvez modifier votre avis et retransmettre.\n\nMotif : ".$data['rejet_motif'],
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('rerx.dossiers.show', $dossier->token),
            event: 'reject_direction_to_risques'
        );
        $recipients = $mailer->recipientsByRole((int) config('angara.role_responsable_risques', 12));
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        Session::flash('success', 'Dossier renvoyé au pôle risques. Le responsable risques pourra modifier et retransmettre.');

        return back();
    }
}


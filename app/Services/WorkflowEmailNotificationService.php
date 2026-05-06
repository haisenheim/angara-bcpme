<?php

namespace App\Services;

use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WorkflowEmailNotificationService
{
    public function resolvedTestRecipient(): ?string
    {
        $raw = config('mail.test_recipient');
        if (! is_string($raw)) {
            return null;
        }
        $t = trim($raw);

        return $t !== '' ? $t : null;
    }

    /**
     * Envoi d'un mail de notification workflow (transmission / affectation).
     * - Toujours encapsulé dans try/catch (tolérant aux pannes SMTP).
     * - Si MAIL_TEST_RECIPIENT est défini, redirige tous les emails vers cette adresse.
     *
     * @param  array{subject:string, title:string, body:string, cta_label?:string, cta_url?:string, event?:string}  $payload
     */
    public function notifyUsers(Collection $recipients, ?User $actor, array $payload, array $context = []): void
    {
        $recipients = $recipients
            ->filter(fn ($u) => $u instanceof User)
            ->filter(fn (User $u) => filled($u->email))
            ->values();

        if ($recipients->isEmpty()) {
            return;
        }

        foreach ($recipients as $recipient) {
            $this->notifyUser($recipient, $actor, $payload, $context);
        }
    }

    /**
     * @param  array{subject:string, title:string, body:string, cta_label?:string, cta_url?:string, event?:string}  $payload
     */
    public function notifyUser(User $recipient, ?User $actor, array $payload, array $context = []): void
    {
        $to = (string) $recipient->email;
        $forced = $this->resolvedTestRecipient();
        $finalTo = $forced ?? $to;

        Log::info('Workflow email: attempt', [
            'event' => $payload['event'] ?? null,
            'subject' => $payload['subject'] ?? null,
            'intended_to' => $to,
            'final_to' => $finalTo,
            'redirected_by_test_recipient' => $forced !== null,
            'recipient_id' => $recipient->id ?? null,
            'actor_id' => $actor?->id,
        ]);

        try {
            Mail::send('emails.workflow-notification', [
                'recipient' => $recipient,
                'actor' => $actor,
                'payload' => $payload,
                'context' => $context,
                'intended_to' => $to,
                'forced_to' => $forced,
            ], function ($message) use ($finalTo, $payload) {
                $message->to($finalTo)->subject((string) ($payload['subject'] ?? 'Notification'));
            });

            Log::info('Workflow email: dispatched', [
                'event' => $payload['event'] ?? null,
                'subject' => $payload['subject'] ?? null,
                'final_to' => $finalTo,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Workflow email notification failed', [
                'to' => $finalTo,
                'intended_to' => $to,
                'subject' => $payload['subject'] ?? null,
                'event' => $payload['event'] ?? null,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
        }
    }

    /**
     * Notifie des utilisateurs par rôle, puis des adresses email brutes (hors table users),
     * sans doublon sur l’adresse. Utile si la base n’a pas encore d’utilisateurs pour les rôles 10/11.
     *
     * @param  array<int, string>  $additionalEmails
     * @param  array{subject:string, title:string, body:string, cta_label?:string, cta_url?:string, event?:string}  $payload
     */
    public function notifyUsersAndAdditionalEmails(
        Collection $roleUsers,
        array $additionalEmails,
        string $additionalRecipientLabel,
        ?User $actor,
        array $payload,
        array $context = []
    ): void {
        $sent = [];

        foreach ($roleUsers as $u) {
            if (! $u instanceof User || ! filled($u->email)) {
                continue;
            }
            $key = strtolower(trim((string) $u->email));
            if ($key === '' || isset($sent[$key])) {
                continue;
            }
            $sent[$key] = true;
            $this->notifyUser($u, $actor, $payload, $context);
        }

        foreach ($additionalEmails as $addr) {
            if (! is_string($addr)) {
                continue;
            }
            $addr = trim($addr);
            if ($addr === '' || ! filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            $key = strtolower($addr);
            if (isset($sent[$key])) {
                continue;
            }
            $sent[$key] = true;
            $this->notifyEmailAddress($addr, $additionalRecipientLabel, $actor, $payload, $context);
        }
    }

    /**
     * Envoi à une adresse hors modèle User (même gabarit que {@see notifyUser()}).
     *
     * @param  array{subject:string, title:string, body:string, cta_label?:string, cta_url?:string, event?:string}  $payload
     */
    public function notifyEmailAddress(string $email, string $displayName, ?User $actor, array $payload, array $context = []): void
    {
        $to = trim($email);
        if ($to === '' || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $shadow = (object) [
            'name' => $displayName,
            'email' => $to,
        ];

        $forced = $this->resolvedTestRecipient();
        $finalTo = $forced ?? $to;

        Log::info('Workflow email: attempt (email-address)', [
            'event' => $payload['event'] ?? null,
            'subject' => $payload['subject'] ?? null,
            'intended_to' => $to,
            'final_to' => $finalTo,
            'redirected_by_test_recipient' => $forced !== null,
            'display_name' => $displayName,
            'actor_id' => $actor?->id,
        ]);

        try {
            Mail::send('emails.workflow-notification', [
                'recipient' => $shadow,
                'actor' => $actor,
                'payload' => $payload,
                'context' => $context,
                'intended_to' => $to,
                'forced_to' => $forced,
            ], function ($message) use ($finalTo, $payload) {
                $message->to($finalTo)->subject((string) ($payload['subject'] ?? 'Notification'));
            });

            Log::info('Workflow email: dispatched (email-address)', [
                'event' => $payload['event'] ?? null,
                'subject' => $payload['subject'] ?? null,
                'final_to' => $finalTo,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Workflow email notification failed', [
                'to' => $finalTo,
                'intended_to' => $to,
                'subject' => $payload['subject'] ?? null,
                'event' => $payload['event'] ?? null,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
        }
    }

    /**
     * Si aucune notification n’a pu partir, envoie un mail « diagnostic » au destinataire de test
     * (pour ne pas rester silencieux en environnement de recette).
     *
     * @param  array{subject:string, title:string, body:string, cta_label?:string, cta_url?:string, event?:string}  $payload
     */
    public function notifyTestRecipientIfNoRealRecipient(
        ?User $actor,
        array $payload,
        array $context,
        string $reason,
        array $debug = []
    ): void {
        $test = $this->resolvedTestRecipient();
        if ($test === null) {
            Log::warning('Workflow email: aucun destinataire et pas de MAIL_TEST_RECIPIENT', array_merge([
                'reason' => $reason,
                'event' => $payload['event'] ?? null,
            ], $debug));

            return;
        }

        $body = ($payload['body'] ?? '')
            ."\n\n---\n"
            ."Diagnostic : ".$reason."\n"
            .(! empty($debug) ? json_encode($debug, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '');

        $diagPayload = [
            'subject' => '[Angara — diagnostic] '.($payload['subject'] ?? 'Notification'),
            'title' => $payload['title'] ?? 'Notification',
            'body' => $body,
            'cta_label' => $payload['cta_label'] ?? null,
            'cta_url' => $payload['cta_url'] ?? null,
            'event' => ($payload['event'] ?? 'unknown').'_diagnostic',
        ];

        $this->notifyEmailAddress($test, 'Destinataire de test', $actor, $diagPayload, $context);
    }

    /** @return Collection<int, User> */
    public function recipientsByRole(int $roleId, ?int $agenceId = null): Collection
    {
        $q = User::query()->where('role_id', $roleId)->where(function ($qq) {
            $qq->whereNull('active')
                ->orWhere('active', true)
                ->orWhere('active', 1)
                ->orWhere('active', '1');
        });
        if ($agenceId !== null) {
            $q->where('agence_id', $agenceId);
        }

        return $q->get(['id', 'name', 'email', 'role_id', 'agence_id']);
    }

    /**
     * Fabrique un payload standard (titre + texte + CTA).
     */
    public function buildPayload(string $subject, string $title, string $body, ?string $ctaLabel = null, ?string $ctaUrl = null, ?string $event = null): array
    {
        return [
            'subject' => $subject,
            'title' => $title,
            'body' => $body,
            'cta_label' => $ctaLabel,
            'cta_url' => $ctaUrl,
            'event' => $event,
        ];
    }

    /**
     * Context commun (dossier / entreprise) pour l'email.
     *
     * @return array<string, mixed>
     */
    public function contextForDossier(Dossier $dossier): array
    {
        $dossier->loadMissing(['entreprise', 'instructionProgrammes.programme', 'programme']);
        $entreprise = $dossier->entreprise;

        return [
            'dossier' => $dossier,
            'entreprise' => $entreprise,
            'dossier_label' => method_exists($dossier, 'getNameAttribute') ? $dossier->name : ($entreprise?->name ?? 'Dossier'),
            'programmes' => $dossier->programmesLabel(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function contextForEntreprise(Entreprise $entreprise): array
    {
        return [
            'entreprise' => $entreprise,
            'entreprise_label' => $entreprise->name ?? 'Entreprise',
        ];
    }
}


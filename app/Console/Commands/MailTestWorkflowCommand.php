<?php

namespace App\Console\Commands;

use App\Services\WorkflowEmailNotificationService;
use Illuminate\Console\Command;

/**
 * Diagnostic mail :
 *  - vérifie la configuration (mailer, host, from, test_recipient).
 *  - envoie un mail de test via WorkflowEmailNotificationService.
 *  - affiche dans la sortie + dans les logs ce qui se passe à chaque étape.
 *
 * Usage :
 *   php artisan mail:test-workflow                  # envoie au MAIL_TEST_RECIPIENT
 *   php artisan mail:test-workflow --to=foo@bar.com # envoie à une adresse explicite (ignore test_recipient)
 */
class MailTestWorkflowCommand extends Command
{
    protected $signature = 'mail:test-workflow
        {--to= : Adresse email cible (sinon MAIL_TEST_RECIPIENT)}';

    protected $description = 'Diagnostique l\'envoi d\'un email de notification workflow.';

    public function handle(WorkflowEmailNotificationService $svc): int
    {
        $this->info('=== Diagnostic mail workflow ANGARA ===');
        $this->line('');
        $this->line('Configuration :');
        $this->table(['Clé', 'Valeur'], [
            ['mail.default', (string) config('mail.default')],
            ['mail.mailers.smtp.host', (string) config('mail.mailers.smtp.host')],
            ['mail.mailers.smtp.port', (string) config('mail.mailers.smtp.port')],
            ['mail.mailers.smtp.encryption', (string) config('mail.mailers.smtp.encryption')],
            ['mail.mailers.smtp.username', (string) config('mail.mailers.smtp.username')],
            ['mail.from.address', (string) config('mail.from.address')],
            ['mail.from.name', (string) config('mail.from.name')],
            ['mail.test_recipient', (string) (config('mail.test_recipient') ?? 'NULL')],
        ]);
        $this->line('');

        $username = (string) config('mail.mailers.smtp.username');
        $from = (string) config('mail.from.address');
        $usernameDomain = preg_replace('/^.+@/', '', $username);
        $fromDomain = preg_replace('/^.+@/', '', $from);
        if ($usernameDomain && $fromDomain && strcasecmp($usernameDomain, $fromDomain) !== 0) {
            $this->warn(sprintf(
                'ATTENTION : Le domaine MAIL_FROM_ADDRESS (%s) ne correspond pas au domaine MAIL_USERNAME (%s).',
                $fromDomain,
                $usernameDomain
            ));
            $this->warn('   → Risque DMARC/SPF élevé : le mail peut être silencieusement classé en spam ou rejeté par le destinataire.');
            $this->warn('   → Recommandé : utiliser une adresse From du domaine '.$usernameDomain.'.');
            $this->line('');
        }

        $cliTo = $this->option('to');
        $forced = $svc->resolvedTestRecipient();
        if ($cliTo) {
            $finalTo = (string) $cliTo;
            $this->line('Cible explicite (--to) : <info>'.$finalTo.'</info> (le redirect MAIL_TEST_RECIPIENT est ignoré pour ce test)');
            // Pour ignorer le redirect, on appelle directement Mail::raw.
            try {
                \Illuminate\Support\Facades\Mail::raw(
                    "Test diagnostic ANGARA — ".now()->toDateTimeString()."\n\nCe message confirme que le serveur SMTP accepte les emails depuis cette installation.",
                    function ($m) use ($finalTo) {
                        $m->to($finalTo)->subject('[ANGARA] Test diagnostic workflow — '.now()->format('d/m/Y H:i'));
                    }
                );
                $this->info('OK : Mail::raw envoyé sans erreur à '.$finalTo);
            } catch (\Throwable $e) {
                $this->error('EXCEPTION : '.get_class($e).' — '.$e->getMessage());

                return self::FAILURE;
            }

            return self::SUCCESS;
        }

        if ($forced === null) {
            $this->error('MAIL_TEST_RECIPIENT n\'est pas défini. Renseignez-le dans .env ou utilisez --to=adresse@example.com.');

            return self::FAILURE;
        }

        $this->line('Envoi via WorkflowEmailNotificationService -> redirection vers <info>'.$forced.'</info>');

        $payload = $svc->buildPayload(
            subject: '[ANGARA] Test workflow — '.now()->format('d/m/Y H:i'),
            title: 'Diagnostic workflow ANGARA',
            body: "Ce message confirme que le pipeline de notification fonctionne :\n\n".
                "1. Le service WorkflowEmailNotificationService est résolvable.\n".
                "2. La redirection MAIL_TEST_RECIPIENT est appliquée.\n".
                "3. Le serveur SMTP a accepté l'envoi.\n\n".
                "Si vous recevez ce message dans la boîte de réception (et non en spam), le pipeline est OK.",
            ctaLabel: 'Ouvrir ANGARA',
            ctaUrl: config('app.url'),
            event: 'mail_test_workflow_command'
        );

        $svc->notifyEmailAddress($forced, 'Destinataire de test ANGARA', null, $payload, []);

        $this->info('Appel notifyEmailAddress() effectué (cf. storage/logs/laravel.log pour les détails).');
        $this->line('');
        $this->line('Si vous ne recevez pas le mail dans les 2 minutes :');
        $this->line('  1. Vérifiez le dossier spam/indésirables de '.$forced);
        $this->line('  2. Vérifiez les logs : grep "Workflow email" storage/logs/laravel.log');
        $this->line('  3. Vérifiez l\'alignement MAIL_FROM_ADDRESS / MAIL_USERNAME (DMARC).');

        return self::SUCCESS;
    }
}

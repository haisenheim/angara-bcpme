<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RehashPlainTextPasswordsCommand extends Command
{
    protected $signature = 'users:rehash-plaintext-passwords {--dry-run : Affiche les utilisateurs concernés sans modifier la BD}';

    protected $description = 'Détecte les comptes dont le mot de passe a été stocké en clair (bug du cast password=>hashed Laravel 10) et les re-hache';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $hits = 0;
        $checked = 0;

        User::query()->select(['id', 'email', 'password'])->orderBy('id')->chunkById(200, function ($users) use (&$hits, &$checked, $dryRun) {
            foreach ($users as $user) {
                $checked++;
                $pwd = (string) $user->password;
                if ($pwd === '') {
                    continue;
                }

                // Un hash bcrypt/argon2 commence toujours par "$" (ex. $2y$, $argon2id$).
                // Si la valeur ne commence pas par "$" ou que sa longueur est très différente
                // d'un hash (60 chars pour bcrypt), c'est du texte clair laissé par le bug.
                $looksHashed = str_starts_with($pwd, '$') && strlen($pwd) >= 50;
                if ($looksHashed) {
                    continue;
                }

                $hits++;
                $this->line(sprintf('  - id=%d email=%s len=%d', $user->id, $user->email, strlen($pwd)));

                if (! $dryRun) {
                    User::where('id', $user->id)->update(['password' => Hash::make($pwd)]);
                }
            }
        });

        if ($dryRun) {
            $this->warn(sprintf('[dry-run] %d compte(s) sur %d auraient été corrigés.', $hits, $checked));
        } else {
            $this->info(sprintf('%d compte(s) sur %d corrigés (mot de passe re-haché).', $hits, $checked));
        }

        return self::SUCCESS;
    }
}

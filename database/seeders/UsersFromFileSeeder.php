<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UsersFromFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Chemins des rôles et leurs IDs
        $roles = [
            'gestionnaire' => 13,
            'analyste' => 14,
            'chef' => 12,
            'responsable' => 11,
        ];

        // Lire le fichier users.txt
        $filePath = base_path('users.txt');

        if (!File::exists($filePath)) {
            $this->command->error("Le fichier users.txt n'existe pas dans le répertoire racine.");
            return;
        }

        $lines = File::lines($filePath);
        $usersCreated = 0;

        // Ignorer la première ligne (en-tête)
        $firstLine = true;

        foreach ($lines as $line) {
            // Ignorer la ligne d'en-tête
            if ($firstLine) {
                $firstLine = false;
                continue;
            }

            // Ignorer les lignes vides
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Séparer le nom et l'email (format: nom\temail ou nom\t\temail)
            $parts = preg_split('/\t+/', $line);

            if (count($parts) < 2) {
                continue;
            }

            $name = trim($parts[0]);
            $email = trim($parts[1]);

            // Vérifier que l'email est valide
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->command->warn("Email invalide ignoré: {$email}");
                continue;
            }

            // Séparer l'email en partie locale et domaine
            [$localPart, $domain] = explode('@', $email);

            // Créer 4 comptes pour chaque utilisateur
            foreach ($roles as $roleSuffix => $roleId) {
                // Générer l'email avec suffixe
                $newEmail = $localPart . '+' . $roleSuffix . '@' . $domain;

                // Vérifier si l'utilisateur existe déjà
                if (User::where('email', $newEmail)->exists()) {
                    $this->command->info("L'utilisateur {$newEmail} existe déjà, ignoré.");
                    continue;
                }

                // Créer l'utilisateur
                $user = new User();
                $user->name = $name;
                $user->email = $newEmail;
                $user->password = bcrypt('1234');
                $user->role_id = $roleId;
                $user->agence_id = 4;
                $user->representation_id = 2;
                $user->token = sha1(date('YmdHis') . $name . $roleSuffix . rand(1, 9999));
                $user->active = true;
                $user->save();

                $usersCreated++;
                $this->command->info("Utilisateur créé: {$name} ({$newEmail}) - Rôle ID: {$roleId}");
            }
        }

        $this->command->info("Total d'utilisateurs créés: {$usersCreated}");
    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class BcpmeAngaraDemoUsersSeeder extends Seeder
{
    protected string $connection = 'central_app_mysql';

    public function run(): void
    {
        if (! Schema::connection($this->connection)->hasTable('users')) {
            return;
        }

        $password = Hash::make('1234');

        foreach ($this->users() as $user) {
            DB::connection($this->connection)
                ->table('users')
                ->updateOrInsert(
                    ['email' => $user['email']],
                    array_merge($user, ['password' => $password])
                );
        }
    }

    /**
     * Utilisateurs repris du dump avec conservation des profils/coordonnees
     * utiles, mais mot de passe normalise pour faciliter les essais locaux.
     *
     * @return list<array<string, mixed>>
     */
    protected function users(): array
    {
        return [
            [
                'name' => 'Haisenheim',
                'role_id' => 1,
                'photo_uri' => 'profil/gfhgjdhhjdshgftehfgh.jpeg',
                'agence_id' => 0,
                'representation_id' => 0,
                'programme_id' => 0,
                'cooperative_id' => 0,
                'secteur_id' => 0,
                'banque_id' => 0,
                'poste_id' => 0,
                'departement_id' => 0,
                'phone' => null,
                'email' => 'clementessomba@gmail.com',
                'email_verified_at' => null,
                'remember_token' => null,
                'created_at' => '2024-08-27 10:36:45',
                'updated_at' => '2025-01-06 13:16:53',
                'active' => 1,
                'token' => 'gfhgjdhhjdshgftehfgh',
                'permissions' => null,
            ],
            [
                'name' => 'NGOAH Armelle',
                'role_id' => 16,
                'photo_uri' => null,
                'agence_id' => 1,
                'representation_id' => 1,
                'programme_id' => 0,
                'cooperative_id' => 0,
                'secteur_id' => 0,
                'banque_id' => 0,
                'poste_id' => 0,
                'departement_id' => 0,
                'phone' => '6633532466',
                'email' => 'a.ngoah43@angara.com',
                'email_verified_at' => null,
                'remember_token' => null,
                'created_at' => '2024-12-12 17:10:45',
                'updated_at' => '2024-12-12 17:10:45',
                'active' => 1,
                'token' => 'b43e4118bc4a23701e5f084c6abab1c458fdfdcd',
                'permissions' => null,
            ],
            [
                'name' => 'ELOUNDOU Francis',
                'role_id' => 16,
                'photo_uri' => null,
                'agence_id' => 1,
                'representation_id' => 1,
                'programme_id' => 0,
                'cooperative_id' => 0,
                'secteur_id' => 0,
                'banque_id' => 0,
                'poste_id' => 0,
                'departement_id' => 0,
                'phone' => '6799649451',
                'email' => 'f.eloundou73@angara.com',
                'email_verified_at' => null,
                'remember_token' => null,
                'created_at' => '2025-01-05 16:03:56',
                'updated_at' => '2025-01-05 16:03:56',
                'active' => 1,
                'token' => 'ad7d9a00f7ab0deef9795b06157e02bdc3276357',
                'permissions' => null,
            ],
        ];
    }
}

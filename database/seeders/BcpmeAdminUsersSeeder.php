<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BcpmeAdminUsersSeeder extends Seeder
{
    /**
     * Cree (ou met a jour si l'email existe deja) les comptes administrateurs
     * BC-PME demandes, avec role_id = 1 et un mot de passe par defaut.
     *
     * Idempotent : peut etre rejoue sans creer de doublon.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('1234567890');

        foreach ($this->admins() as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'phone' => $admin['phone'],
                    'role_id' => 1,
                    'active' => 1,
                    'password' => $defaultPassword,
                    'token' => sha1(Str::uuid()->toString()),
                ]
            );
        }
    }

    /**
     * @return list<array{name:string,email:string,phone:string}>
     */
    protected function admins(): array
    {
        return [
            [
                'name' => 'NDJOMO EKO RODRIGUE',
                'email' => 'rodrigue.ndjomo@bc-pme.cm',
                'phone' => '681582120',
            ],
            [
                'name' => 'MANGA WELISANE KWIN',
                'email' => 'welisane.manga@bc-pme.cm',
                'phone' => '681582999',
            ],
            [
                'name' => 'WAMBA FOKOU GABIN',
                'email' => 'loik.wamba@bc-pme.cm',
                'phone' => '675564263',
            ],
        ];
    }
}

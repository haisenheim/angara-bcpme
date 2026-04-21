<?php

namespace App\Imports;

use App\Models\Agence;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ApmeImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row) {
            // dd($row);
            $i++;
            $agence = Agence::find($row['agence_id']);
            $gestionnaire = User::where('role_id', (int) config('angara.role_gestionnaire', 16))->where('agence_id', $agence->id)->first();
            $phones = null;
            if ($row['numero_principal_mobile_money']) {
                $phones = explode('/', $row['numero_principal_mobile_money']);
            }
            Entreprise::create([
                'name' => $row['denomination_entreprise'],
                'agence_id' => $agence->id,
                'user_id' => $gestionnaire->id,
                'representation_id' => $agence->representation_id,
                'manager' => $row['nom_du_dirigeant'] ? $row['nom_du_dirigeant'] : '',
                'manager_sexe' => $row['sexe_du_dirigeant'] ? (trim($row['sexe_du_dirigeant']) == 'F' ? 'Femme' : 'Homme') : null,
                'manager_contact' => $row['numero_principal_mobile_money'] ? $row['numero_principal_mobile_money'] : '',
                'mm_phone' => $phones ? $phones[0] : '',
                'token' => sha1(time().$i),
            ]);
        }
    }
}

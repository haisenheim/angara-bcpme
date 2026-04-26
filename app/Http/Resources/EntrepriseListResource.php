<?php

namespace App\Http\Resources;

use App\Models\DossierEntreeRelation;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class EntrepriseListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    private function prospectWorkflowStatusLabel(): ?string
    {
        if (! $this->prospect) {
            return null;
        }
        if (! $this->prospect_submitted_at) {
            return 'brouillon';
        }
        if (! $this->juridique_avis_at || ! $this->conformite_avis_at) {
            return 'en_attente_avis';
        }

        return 'avis_juridique_et_conformite_recus';
    }

    private function toIso8601Date(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format(DateTimeInterface::ATOM);
        }

        try {
            return Carbon::parse($value)->toIso8601String();
        } catch (\Throwable) {
            return null;
        }
    }

    public function toArray(Request $request): array
    {
        $structRow = $this->clientStructurationRow();

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'cnps'=>$this->cnps,
            'rccm'=>$this->rccm,
            'niu'=>$this->niu,
            'agence_id' => $this->agence_id,
            'gestionnaire_id' => $this->gestionnaire_id,
            'gestionnaire_name' => $this->gestionnaire?->name,
            'promu_client_at' => $this->toIso8601Date($this->promu_client_at),
            'agence'=>$this->agence?->name,
            'representation'=>$this->representation?->name,
            'commune'=>$this->arrondissement?->name,
            'departement'=>$this->departement?->name,
            'region'=>$this->region?->name,
            'taille'=>$this->taille,
            'forme'=>$this->forme?->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'caractere'=>$this->caractere,
            'systeme'=>$this->systeme,
            'capital'=>$this->capital,
            'personnel'=>$this->nb_personnel,
            'manager'=>$this->manager,
            'token'=>$this->token,
            'prospect_submitted_at' => $this->toIso8601Date($this->prospect_submitted_at),
            'juridique_avis_at' => $this->toIso8601Date($this->juridique_avis_at),
            'conformite_avis_at' => $this->toIso8601Date($this->conformite_avis_at),
            'prospect_workflow_status' => $this->prospectWorkflowStatusLabel(),
            'client_structuration_code' => $structRow['code'],
            'client_structuration_label' => $structRow['label'],
        ];
    }

    /**
     * @return array{code: ?string, label: string}
     */
    private function clientStructurationRow(): array
    {
        if (! $this->promu_client_at) {
            return ['code' => null, 'label' => '—'];
        }

        $pres = DossierEntreeRelation::clientStructurationPresentation($this->dossierEntreeRelation);

        return ['code' => $pres['code'], 'label' => $pres['label']];
    }
}

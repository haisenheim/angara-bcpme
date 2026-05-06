<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DossierListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $org = method_exists($this->resource, 'currentOrganisation') ? $this->currentOrganisation() : null;
        $statut = method_exists($this->resource, 'instructionStatutPresentation')
            ? $this->resource->instructionStatutPresentation()
            : null;

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'entreprise'=>$this->entreprise?->name,
            'programme'=>$this->programmesLabel(),
            'gestionnaire'=>$this->gestionnaire?->name,
            'agence'=>$this->agence?->name,
            'direction'=>$this->agence?->representation?->name,
            'produit'=>$this->entreprise?->produit?->name,
            'signataire'=>$this->signatairesLabel(),
            'analyste'=>$this->analyste?->name,
            'organisation'=>$org,
            'organisation_key'=>$org['key'] ?? null,
            'organisation_label'=>$org['label'] ?? null,
            'organisation_type'=>$org['type'] ?? null,
            'organisation_entity_route'=>$org['entity_route'] ?? null,
            'created'=>$this->created_at->format('d/m/Y H:i'),
            'token'=>$this->token,
            'instruction_statut' => $statut ? [
                'code' => $statut['code'] ?? null,
                'label' => $statut['label'] ?? null,
                'badge_variant' => $statut['badge_variant'] ?? 'secondary',
                'detail' => $statut['detail'] ?? null,
            ] : null,
        ];
    }
}

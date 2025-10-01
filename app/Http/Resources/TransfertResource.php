<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransfertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'token'=>$this->token,
            'tenant_source'=>$this->cooperativeSource?->name,
            'source'=>$this->source?->name,
            'target'=>$this->target?->name,
            'gamme'=>$this->gamme?->name,
            'vehicule'=>$this->vehicule,
            'responsable'=>$this->responsable,
            'created'=>$this->created_at->format('d/m/Y'),
            'quantity'=>$this->quantity,
            'date'=>Carbon::parse($this->day)->format('d/m/Y'),
        ];
    }
}

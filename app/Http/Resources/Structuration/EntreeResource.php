<?php

namespace App\Http\Resources\Structuration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntreeResource extends JsonResource
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
            'name'=>$this->name,
            'date'=>$this->created_at->format('d/m/Y H:i'),
            'agent'=>$this->agent?->name,
            'producteur'=>$this->exploitant?->name,
            'quantity'=>$this->quantity,
            'pu'=>$this->pu,
            'total'=>$this->pu*$this->quantity,
            'token'=>$this->token,
        ];
    }
}

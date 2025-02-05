<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'=>$this->id,
            'operateur'=>$this->operateur,
            'solde'=>$this->montant,
            'status'=>$this->status,
            'phone'=>$this->phone,
        ];
        return $data;
    }
}

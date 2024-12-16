<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgrammeListResource extends JsonResource
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
            'convention'=>$this->convention,
            'signataire'=>$this->signataire,
            'budget'=>$this->budget,
            'contact'=>$this->contact,
            'type_pp'=>$this->type_pp,
            'type_pm'=>$this->type_pm,
            'dt_sig_conv'=>Carbon::parse($this->dt_sig_conv)->format('d/m/Y'),
            'token'=>$this->token,
        ];
    }
}

<?php

namespace App\Jobs;

use App\Models\Structuration\Paiement;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Stancl\Tenancy\Jobs\TenantAwareJob;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Paiement $payment;
    protected $tenant;

   // protected $DIGIBANK_OPEN_API_URL = 'https://server3.inet-cm.com:449/digibank/api-gateway/digibank-openapi';
    protected $DIGIBANK_OPEN_API_URL = "https://144.91.65.152:7446/digibank/api-gateway/digibank-openapi";
    //https://144.91.65.152:7446/digibank/api-gateway/digibank-openapi
    public function __construct(Paiement $payment, Tenant $tenant)
    {
        $this->payment = $payment;
        $this->payment->api_key = "KEY_749CE6591FAA48298B1D9440C1EA8C31";
        $this->payment->api_secret= "SECRET_C2F026BC89744CAF8EEB1CBD8974885B";
        $this->tenant = $tenant;  // Récupère le tenant courant
    }

    public function handle()
    {
        tenancy()->initialize($this->tenant->getTenantKey());

        $data = [
        "reference"=> $this->payment->token,
        "callbackUrl"=>route('util.paiement.callback', $this->tenant->token),
        "gimacWallet"=>$this->payment->compte,
        "amount"=>$this->payment->montant,
        "reason"=> "Paiement pour le service X",
        "x-intent"=>"transfer"
        ];
        Http::withBasicAuth(
            [
                'username' => $this->tenant->api_key,
                'password' => $this->tenant->api_secret
            ])->withHeaders(
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])
            ->post($this->DIGIBANK_OPEN_API_URL, $data);

            tenancy()->end();
    }
}

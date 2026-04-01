<?php
namespace App\Services;

use App\Models\Structuration\Paiement;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    protected $tenant;
    protected $DIGIBANK_OPEN_API_URL = "https://144.91.65.152:7446/digibank/api-gateway/digibank-openapi";

    public function __construct($tenant)
    {
        $this->tenant = $tenant;
        $this->tenant->api_key = "KEY_749CE6591FAA48298B1D9440C1EA8C31";
        $this->tenant->api_secret= "SECRET_C2F026BC89744CAF8EEB1CBD8974885B";
    }

    public function processPayment(Paiement $payment)
    {
        $data = [
        "reference"=> $payment->token,
        "callbackUrl"=>route('util.paiement.callback', $this->tenant->token),
        "gimacWallet"=>$payment->compte,
        "amount"=>$payment->montant,
        "reason"=> "Paiement pour le service X",
        "x-intent"=>"transfer"
        ];
        Http::withBasicAuth($this->tenant->api_key,$this->tenant->api_secret)
        ->withoutVerifying()
        ->withHeaders(
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])
            ->post($this->DIGIBANK_OPEN_API_URL, $data);

        return true; // Return true if the payment was processed successfully
    }
}

<?php

namespace App\Jobs;

use App\Models\Structuration\Paiement;
use App\Models\Tenant;
use App\Services\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public Paiement $payment;

    protected Tenant $tenant;

    public function __construct(Paiement $payment, Tenant $tenant)
    {
        $this->payment = $payment;
        $this->payment->api_key = 'KEY_749CE6591FAA48298B1D9440C1EA8C31';
        $this->payment->api_secret = 'SECRET_C2F026BC89744CAF8EEB1CBD8974885B';
        $this->tenant = $tenant;
    }

    public function handle(): void
    {
        $ps = new PaymentService($this->tenant);
        $ps->processPayment($this->payment);
    }
}

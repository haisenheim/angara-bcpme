<?php

namespace App\Http\Controllers\Util;

use App\Http\Controllers\Controller;
use App\Models\Structuration\Paiement;
use App\Models\Tenant;
use Illuminate\Http\Request;

class PayementController extends Controller
{
    //
       public function handleCallback(Request $request, $token)
    {
        $data = $request->all();

       /* if (!$this->isValidSignature($data)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        } */
       //dd($data);
        $tenant = Tenant::where('token', $token)->firstOrFail();
        $tenant->run(function() use ($data) {
            $payment = Paiement::where('token', $data['reference'])->firstOrFail();
            if ($data['status'] == 'ACCEPTED') {
                $payment->accepted_at = new \DateTime();
            }
            if ($data['status'] === 'REJECTED') {
                $payment->rejected_at = new \DateTime();
            }
            $payment->save();
        });

        app()->instance('tenant', $tenant);


        //event(new PaymentCompleted($payment));

        return response()->json(['message' => 'Callback traité avec succès']);
    }

    private function isValidSignature($data)
    {
        $expected = hash_hmac('sha256', $data['reference'] . $data['status'], env('PAYMENT_SECRET'));
        return hash_equals($expected, $data['signature'] ?? '');
    }
}

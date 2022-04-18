<?php

namespace App\Stripe;

use App\Entity\Commande;

class StripeService

{
    protected $secretKey;
    protected $publickey;
    
    public function __construct(string $secretKey, string $publicKey)
    {
        $this->secretKey = $secretKey;
        $this->publickey = $publicKey;
    }

    public function getPublicKey(): string
    {
        return $this->publickey;
    }
    
    public function getPaymentIntent(Commande $purchase)
    {
        \Stripe\Stripe::setApiKey($this->secretKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $purchase->getTotal() * 100,
            'currency' => 'eur'
        ]);
    }
}

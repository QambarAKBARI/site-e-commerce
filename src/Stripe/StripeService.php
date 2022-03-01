<?php

namespace App\Stripe;

use App\Entity\Commande;
use App\Service\Cart\CartService;

class StripeService

{
    
    protected $secretKey;
    protected $publickey;
    
    public function __construct(string $secretKey, string $publicKey)
    {
        
        $this->secretKey = $_ENV['STRIPE_SECRET_KEY'];
        $this->publickey = $publicKey;
    }

    public function getPublicKey(): string
    {
        return $_ENV['STRIPE_PUBLIC_KEY'];
    }

    public function getPaymentIntent(Commande $purchase, CartService $carteService)
    {
        \Stripe\Stripe::setApiKey($this->secretKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $carteService->getTotal(),
            'currency' => 'eur'
        ]);
    }
}

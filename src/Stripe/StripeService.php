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
        
<<<<<<< HEAD
        $this->secretKey = $secretKey;
=======
        $this->secretKey = $_ENV['STRIPE_SECRET_KEY'];
>>>>>>> 593d27318c86778a50411433e9d10418c18769ba
        $this->publickey = $publicKey;
    }

    public function getPublicKey(): string
    {
<<<<<<< HEAD
        return $this->publickey;
=======
        return $_ENV['STRIPE_PUBLIC_KEY'];
>>>>>>> 593d27318c86778a50411433e9d10418c18769ba
    }

    public function getPaymentIntent(Commande $purchase, CartService $carteService)
    {
        \Stripe\Stripe::setApiKey($this->secretKey);

        return \Stripe\PaymentIntent::create([
<<<<<<< HEAD
            'amount' => $purchase->getTotal() * 100,
=======
            'amount' => $carteService->getTotal(),
>>>>>>> 593d27318c86778a50411433e9d10418c18769ba
            'currency' => 'eur'
        ]);
    }
}

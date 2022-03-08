<?php

namespace App\Service\Cart;

use App\Entity\Produit;


class CartItem
{

    public $produit;
    public $qtt;

    public function __construct(Produit $produit, int $qtt)
    {
        $this->qtt = $qtt;
        $this->produit = $produit;
    }

    public function getTotal(): int
    {
        return $this->produit->getPrix() * $this->qtt;
    }
}

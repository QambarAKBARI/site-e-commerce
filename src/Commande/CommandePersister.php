<?php

namespace App\Commande;

use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CommandePersister
{
    protected $security;
    protected $cartservice;
    protected $em;


    public function __construct(Security  $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
    }

    public function storePurchase(Commande $purchase)
    {
        $purchase->setUser($this->security->getUser());
        $purchase->setDateCommande(new \DateTime('now'));
<<<<<<< HEAD
=======
        
>>>>>>> 593d27318c86778a50411433e9d10418c18769ba
        $this->em->persist($purchase);
    
        foreach ($this->cartService->getDetailCartItem() as $cartItem) {
            $purchaseItem = new ProduitCommande;
            $purchaseItem->setCommande($purchase)
                ->setProduit($cartItem->produit)
<<<<<<< HEAD
                ->setQuantite($cartItem->qtt)
                ->setTotal($cartItem->getTotal());
=======
                ->setQuantite($cartItem->qtt);
>>>>>>> 593d27318c86778a50411433e9d10418c18769ba
                $purchase->addProduitCommande($purchaseItem);
            $this->em->persist($purchaseItem);
    
        }

        $this->em->flush();
    }
}

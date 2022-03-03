<?php

namespace App\Controller\Commande;

use App\Entity\Commande;
use App\Stripe\StripeService;
use App\Repository\CommandeRepository;
use App\Service\Cart\CartService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandePaiementController extends AbstractController
{

    /**
     * @Route("/purchase/pay/{id}", name="purchase_payement_form")
     * @IsGranted("ROLE_USER")
     */
    public function showCardForm($id, CommandeRepository $purchaseRepository, StripeService $stripeService, CartService $cartService)
    {

<<<<<<< HEAD
        $purchase = $purchaseRepository->find($id);
=======
        dd("bonjour");
        $purchase = $purchaseRepository->find($id);
        dd($purchase);
>>>>>>> 593d27318c86778a50411433e9d10418c18769ba
        if (!$purchase || $purchase && $purchase->getUser() !== $this->getUser() || $purchase && $purchase->getStatus() === Commande::STATUS_PAID) {

            return $this->redirectToRoute('cart_show');
        }
<<<<<<< HEAD
        $intent = $stripeService->getPaymentIntent($purchase);
=======
        $intent = $stripeService->getPaymentIntent($purchase, $cartService);
>>>>>>> 593d27318c86778a50411433e9d10418c18769ba
        return $this->render('commande/paiement.html.twig', [

            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase,
            'stripePublicKey'=> $stripeService->getPublicKey()

        ]);
    }
}

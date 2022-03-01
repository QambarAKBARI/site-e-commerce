<?php

namespace App\Controller\Commande;

use App\Entity\Commande;
use App\Stripe\StripeService;
use App\Repository\CommandeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandePaiementController extends AbstractController
{

    /**
     * @Route("/purchase/pay/{id}", name="purchase_payement_form")
     * @IsGranted("ROLE_USER")
     */
    public function showCardForm($id, CommandeRepository $purchaseRepository, StripeService $stripeService)
    {
        $purchase = $purchaseRepository->find($id);

        if (!$purchase || $purchase && $purchase->getUser() !== $this->getUser() || $purchase && $purchase->getStatus() === Commande::STATUS_PAID) {

            return $this->redirectToRoute('cart_show');
        }
        $intent = $stripeService->getPaymentIntent($purchase);
        return $this->render('purchase/payment.html.twig', [

            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase,
            'stripePublicKey'=> $stripeService->getPublicKey()

        ]);
    }
}

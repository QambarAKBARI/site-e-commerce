<?php

namespace App\Controller\Commande;

use App\Entity\Commande;
use App\Service\Cart\CartService;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandePaiementSuccessController extends AbstractController
{

    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, CommandeRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService, EventDispatcherInterface $dispatcher)
    {
        $purchase = $purchaseRepository->find($id);
        if (!$purchase || $purchase && $purchase->getUser() !== $this->getUser() || $purchase && $purchase->getStatus() === Commande::STATUS_PAID) {
            $this->addFlash('warning', "la commande n'éxiste pas");
            return $this->redirectToRoute('purchase_index');
        }

        $purchase->setStatus(Commande::STATUS_PAID);
        $em->flush();

        $cartService->empty();
        $purchaseEvent = new PurchaseSuccessEvent($purchase);
        $dispatcher->dispatch($purchaseEvent, 'purchase.success');

        $this->addFlash('success', "la commande a été payé, vous receverez un mail dans les plus brefs délais lorsque la commande sera traité");
        return $this->redirectToRoute('purchase_index');
    }
}

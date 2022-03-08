<?php

namespace App\Controller\Commande;

use Knp\Snappy\Pdf;
use Twig\Environment;
use App\Entity\Commande;
use App\Service\Cart\CartService;
use App\Repository\CommandeRepository;
use App\Service\Pdf\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandePaiementSuccessController extends AbstractController
{

    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, CommandeRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService, MailerInterface $mailer)
    {
        $purchase = $purchaseRepository->find($id);
        if (!$purchase || $purchase && $purchase->getUser() !== $this->getUser() || $purchase && $purchase->getStatus() === Commande::STATUS_PAID) {
            $this->addFlash('warning', "la commande n'éxiste pas");
            return $this->redirectToRoute('purchase_index');
        }

        $purchase->setStatus(Commande::STATUS_PAID);
        $em->flush();

        $cartService->empty();
        $email = (new TemplatedEmail()) 
        ->from('company@shop.com')
        ->to($purchase->getUser()->getEmail())
        ->subject("Merci pour votre commande n({$purchase->getId()}) a bien été confirmée")
        ->htmlTemplate('emails/commande_success.html.twig')
        ->context([
            'purchase' => $purchase,
            'user' => $purchase->getUser()
        ]);
        $mailer->send($email);

        $this->addFlash('success', "la commande a été payé, vous receverez un mail dans les plus brefs délais lorsque la commande sera traité");
        return $this->redirectToRoute('purchase_index');
    }
}

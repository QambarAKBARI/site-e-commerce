<?php

namespace App\Controller;

use App\Form\CommandeType;
use App\Service\Cart\CartService;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{

    /**
     * @var ProduitRepository
     */
    protected $produitRepository;
    /**
     * @var CartService
     */
    protected $cartservice;

    public function __construct(ProduitRepository $produitRepository, CartService $cartService)
    {
        $this->produitRepository = $produitRepository;
        $this->cartService = $cartService;
    }



    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, Request $request)
    {

        $produit = $this->produitRepository->find($id);
        if (!$produit) {
            throw $this->createNotFoundException("le produit $id n'éxiste pas");
        }

        $this->addFlash('success', "le produit a bien été ajouté au panier");
        $this->cartService->add($id);
        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }
        if ($request->query->get('returnToHome')) {
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show()
    {
        $form = $this->createForm(CommandeType::class);
        $detaileCart = $this->cartService->getDetailCartItem();
        $total = $this->cartService->getTotal();
        return $this->render('cart/index.html.twig', [
            'total' => $total,
            'items' => $detaileCart,
            'confirmationForm'=>$form->createView()

        ]);
    }

    /**
     * @Route("/cart/deletet/{id}", name="cart_delete", requirements = {"id" = "\d+"})
     */
    public function delete($id)
    {
        $produit = $this->produitRepository->find($id);
        if (!$produit) {
            throw $this->createNotFoundException("Le produit $id demandé n'éxiste pas et ne peut être supprimé !");
        }
        $this->cartService->remove($id);

        $this->addFlash('success', "le produit a bien été supprimé du panier");

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart/decrements/{id}", name="cart_decrement", requirements={"id":"\d+"})
     */
    public function decrement($id)
    {

        $produit = $this->produitRepository->find($id);
        if (!$produit) {

            throw $this->createNotFoundException("le produit $id n'éxiste pas");
        }
        $this->cartService->decrement($id);

        $this->addFlash('success', "le produit a bien été enlevé au panier");
        return $this->redirectToRoute("cart_show");
    }

        /**
     * @Route("/cart/deleteAll", name="cart_delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove('cart');
        return $this->redirectToRoute('cart_show');
    }


}

<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\CommandeType;
use App\Service\Cart\CartService;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{


    protected $produitRepository;

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
        if($produit) {
        

            $this->addFlash('success', "le produit a bien été ajouté au panier");
            $this->cartService->add($id);
            
        }else{
            $this->addFlash('danger', "le produit $id n'éxiste pas");
        }

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }
        if ($request->query->get('returnToHome')) {
            return $this->redirectToRoute('home');
        }
        if ($request->query->get('returnToProduct')) {
            return $this->redirectToRoute('show_produit', [
                'id' => $produit->getId()  
            ]);
        }

    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function showCart()
    {
        $form = $this->createForm(CommandeType::class);
        $detaileCart = $this->cartService->getDetailCartItem();
        $total = $this->cartService->getTotal();
        return $this->render('cart/index.html.twig', [
            'total' => $total,
            'items' => $detaileCart,
            'confirmationForm'=>$form->createView(),
            'cartService' => $this->cartservice
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
    public function decrement($id, Request $request)
    {

        $produit = $this->produitRepository->find($id);
        if (!$produit) {

            throw $this->createNotFoundException("le produit $id n'éxiste pas");
        }
        $this->cartService->decrement($id);

        $this->addFlash('success', "le produit a bien été enlevé au panier");
        if ($request->query->get('returnToProduct')) {
            return $this->redirectToRoute('show_produit', [
                'id' => $produit->getId()  
            ]);
        }
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

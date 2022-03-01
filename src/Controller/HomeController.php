<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $produits, SessionInterface $session): Response
    {
        //$panier = [];
        $panier = $session->get('cart', []);
        return $this->render('home/index.html.twig', [
            'produits' => $produits->findAll(),
            'panier'   => $panier
        ]);
    }


     /**
     * @Route("/show/{id}", name="show_produit")
     */
    public function show_produit(Produit $produit): Response
    {
        return $this->render('home/show.html.twig', [
            'produit' => $produit,
 
        ]);
    }
}

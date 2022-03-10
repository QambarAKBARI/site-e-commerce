<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Service\Cart\CartService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $produits, SessionInterface $session, Request $request, CartService $cartService): Response
    {

        $limit = 8;
        $page = (int)$request->query->get("page", 1);
        $total = $produits->getTotalProducts();

        //$panier = [];
        return $this->render('home/index.html.twig', [
            'produits' => $produits->getPaginatedProducts($page, $limit),
            'totalPage'   => $total,
            'limit' => $limit,
            'page' => $page,
            'cartService' => $cartService,
        ]);
    }


     /**
     * @Route("/show/{id}", name="show_produit", requirements={"id":"\d+"})
     * @Route("/avis/new_avis", name="avis_add")
     */
    public function show_produit(Request $request,ManagerRegistry $em): Response
    {
        $avis = new Avis();
        $idProduit = $request->get('id');
        $produit = $em->getRepository(Produit::class)->find($idProduit);
        $user = $this->security->getUser();
        $avis->setUser($user)
             ->setProduit($produit)
             ->setDateDepot(new \DateTime('now'));

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $avis = $form->getData();
            $em = $em->getManager(); 
    
            $em->persist($avis);
            $em->flush();

            $this->addFlash('success', 'Votre message a bien été ajouté !!');
        }

        $form = $this->createForm(AvisType::class, $avis);
        return $this->render('home/show.html.twig', [
            'produit' => $produit,
            'formAvis' => $form->createView(), 
        ]);
    }


}

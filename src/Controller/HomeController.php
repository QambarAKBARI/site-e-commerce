<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\Produit;
use App\Form\SearchType;
use App\Repository\ProduitRepository;
use App\Service\Cart\CartService;
use App\Service\Search\SearchService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function index(ProduitRepository $prodRepo, Request $request, CartService $cartService): Response
    {

        $data = new SearchService();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $produits = $prodRepo->findSearch($data);
        }
        $produits = $prodRepo->findSearch($data);

        return $this->render('home/index.html.twig', [
            'produits' => $produits,
            'cartService' => $cartService,
            'formSearch'  => $form->createView()
        ]);
    }


     /**
     * @Route("/show/{id}", name="show_produit", requirements={"id":"\d+"})
     * @Route("/avis/new_avis", name="avis_add")
     */
    public function show_produit(Request $request,ManagerRegistry $em, CartService $cartService): Response
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
        $detailDeCart = $cartService->getDetailCartItem();
        return $this->render('home/show.html.twig', [
            'produit' => $produit,
            'items' => $detailDeCart,
            'formAvis' => $form->createView(), 
        ]);
    }


}

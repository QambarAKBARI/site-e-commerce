<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
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
     * 
     */
    public function show_produit(Produit $produit, Avis $avis): Response
    {
        $form = $this->createForm(AvisType::class, $avis);
        return $this->render('home/show.html.twig', [
            'produit' => $produit,
            'formAvis' => $form->createView(),
 
        ]);
    }



    /**
     * @Route("/avis/new_avis", name="avis_add")
     * @Route("/avis/avis_edit/edit/{id}", name="avis_edit", requirements={"id":"\d+"})
     */
    public function new(ManagerRegistry $em ,Request $request, Avis $avis = null) {

        if(!$avis){
            $avis = new Avis();
        }
        //dd($avis);
        $idProduit = $request->get('id');
        $produit = $em->getRepository(Produit::class)->find($idProduit);
        $user = $this->security->getUser();
        $avis->setUser($user)
             ->setProduit($produit)
             ->setDateDepot(new \DateTime('now'));
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);


            $avis = $form->getData();
            $em = $em->getManager(); 
    
            $em->persist($avis);
            $em->flush();

            $this->addFlash('success', 'Votre avis a bien été ajouté !!');
            return $this->redirectToRoute('show_produit', [
                "id" => $produit->getId()
            ]);


    }
}

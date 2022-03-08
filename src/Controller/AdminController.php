<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Produit;
use App\Form\MarqueType;
use App\Entity\Categorie;
use App\Form\ProduitType;
use App\Form\CategorieType;
use App\Entity\SousCategorie;
use App\Form\SousCategorieType;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(ProduitRepository $produits): Response
    {
        return $this->render('admin/index.html.twig', [
            'produits' => $produits->findAll(),
        ]);
    }

    /**
     * @Route("/admin/commandes/list", name="commandes_list")
     */
    public function showCommandes(CommandeRepository $commandes): Response
    {
        return $this->render('admin/commandes.html.twig', [
            'commandes' => $commandes->findAll(),
        ]);
    }

    /**
     * @Route("/admin/users/list", name="users_list")
     */
    public function showUsers(UserRepository $users): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new_categorie", name="new_categorie")
     */
    public function new(ManagerRegistry $em ,Request $request): Response {

        $categorie = new Categorie();

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $categorie = $form->getData();
            $em = $em->getManager(); 
    
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Categorie a bien été ajouté !!');
            return $this->redirectToRoute('home');
        }

        return $this->render('admin/categorie.html.twig', [
            'formCategorie' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/new_marque", name="new_marque")
     */
    public function new_marque(ManagerRegistry $em ,Request $request): Response {

        $marque = new Marque();

        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $marque = $form->getData();
            $em = $em->getManager(); 
    
            $em->persist($marque);
            $em->flush();

            $this->addFlash('success', 'marque a bien été ajouté !!');
            return $this->redirectToRoute('home');
        }

        return $this->render('admin/marque.html.twig', [
            'formMarque' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/new_sous_categorie", name="new_sous_categorie")
     */
    public function new_sous_categorie(ManagerRegistry $em ,Request $request): Response {

        $sousCategorie = new SousCategorie();

        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $sousCategorie = $form->getData();
            $em = $em->getManager(); 
    
            $em->persist($sousCategorie);
            $em->flush();

            $this->addFlash('success', 'sousCategorie a bien été ajouté !!');
            return $this->redirectToRoute('home');
        }

        return $this->render('admin/sousCategorie.html.twig', [
            'formSousCategorie' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/new_produit", name="new_produit")
     * @Route("/admin/edit/{id}", name="edit_produit")
     */
    public function new_or_edit_produit(ManagerRegistry $em ,Request $request, Produit $produit = null): Response {
        if(!$produit){
            $produit = new Produit();
        }
        

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $produit = $form->getData();
            $em = $em->getManager(); 
    
            $em->persist($produit);
            $em->flush();

            $this->addFlash('success', 'produit a bien été ajouté !!');
            return $this->redirectToRoute('home');
        }

        return $this->render('admin/produit.html.twig', [
            'formProduit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="delete_product")
     */
    public function delete(ManagerRegistry $em, Produit $produit) : Response {

        $em = $em->getManager();

        $em->remove($produit);
        $em->flush();
        $this->addFlash('success', 'Produit a été supprimé !!');
        return $this->redirectToRoute('home');
    }

    
}

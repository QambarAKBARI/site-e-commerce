<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisController extends AbstractController
{

    /**
     * @Route("/avis", name="avis_index")
     * @IsGranted("ROLE_USER", message="vous devez être connecté pour acceder à vos commandes")
     */
    public function index()
    {
        /** @var User */
        $user = $this->getUser();

        return $this->render('avis/index.html.twig', [
            'avis' => $user->getAvis()
        ]);
    }
}

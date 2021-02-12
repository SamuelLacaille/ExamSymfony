<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActeurController extends AbstractController
{
    /**
     * @Route("/acteur", name="acteur")
     */
    public function index(): Response
    {
        $nom = "Johnson";
        $prenom = "Dwayne";
        $age = 48;



        return $this->render('acteur/index.html.twig', [
            'controller_name' => 'ActeurController',
            'nom' => $nom,
            'prenom' => $prenom,
            'age' => $age
        ]);
    }
}

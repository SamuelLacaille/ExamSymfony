<?php

namespace App\Controller;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    /**
     * @Route("/film", name="film")
     */
    public function index(): Response
    {
        $annee = 2010;
        $titre = "Expendables";

        return $this->render('film/index.html.twig', [
            'controller_name' => 'FilmController',
            'annee' => $annee,
            'titre' => $titre
        ]);
    }
}

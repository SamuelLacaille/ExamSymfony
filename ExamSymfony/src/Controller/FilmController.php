<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    /**
     * @Route("/films", name="film")
     * @param FilmRepository $filmRepository
     * @return Response
     */
    public function getFilms(FilmRepository $filmRepository): Response
    {

        $films = $filmRepository->findAll();

        return $this->render('film/index.html.twig', [
            'films' => $films
        ]);
    }

}

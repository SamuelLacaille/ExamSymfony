<?php

namespace App\Controller;

use App\Entity\Film;
use App\formulaires\FilmType;
use App\Repository\FilmRepository;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FilmController extends AbstractController
{

    private SerializerService $serializerService;
    private EntityManagerInterface $em;

    public function __construct(serializerService $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializerService = $serializer;
        $this->em = $entityManager;
    }

    /**
     * @Route("/acteur", name="acteur", methods={"GET"})
     * @param FilmRepository $filmRepository
     * @return Response
     */
    public function index(FilmRepository $filmRepository): Response
    {
        return JsonResponse::fromJsonString($this->serializerService->RelationSerializer($filmRepository->findAll(), 'json'));
    }




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



    /**
     * @Route("/film/nouveau", name="film_nouveau", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function nouveauFilm(Request $request, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data) {

            if ($data['titre'] && $data['annee']) {

                $film = new Film();

                $form = $this->createForm(FilmType::class, $film);

                $form->submit($data);

                $validate = $validator->validate($film,null,'RegisterFilm');

                if(count($validate) !== 0) {
                    foreach ($validate as $error) {
                        return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
                    }
                }

                $this->em->persist($film);

                $this->em->flush();



                return new JsonResponse("Film ajoute", Response::HTTP_CREATED);

            } else {
                return new JsonResponse("Erreur", RESPONSE::HTTP_NO_CONTENT);
            }
        } else {
            return new JsonResponse("Erreur", RESPONSE::HTTP_NO_CONTENT);
        }

    }


    /**
     * @Route("/film/supprimer/{id}", name="film_supprimer", methods={"DELETE"})
     * @param FilmRepository $filmRepository
     * @param int $id
     * @return Response
     */
    public function deleteEcole(FilmRepository $filmRepository, $id = 0): Response
    {
        $film = $filmRepository->find($id);


        if ($film) {

            $this->em->remove($film);
            $this->em->flush();

            return new JsonResponse("film supprime", Response::HTTP_OK);
        } else {
            return new JsonResponse("Rien a supprimer", Response::HTTP_BAD_REQUEST);
        }
    }


}

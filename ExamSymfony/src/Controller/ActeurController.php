<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\formulaires\ActeurType;
use App\Repository\ActeurRepository;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ActeurController extends AbstractController
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
     * @param ActeurRepository $acteurRepository
     * @return Response
     */
    public function index(ActeurRepository $acteurRepository): Response
    {
        return JsonResponse::fromJsonString($this->serializerService->RelationSerializer($acteurRepository->findAll(), 'json'));
    }



    /**
     * @Route("/acteurs", name="acteur")
     * @param ActeurRepository $acteurRepository
     * @return Response
     */
    public function getActeurs(ActeurRepository $acteurRepository): Response
    {
        $acteurs = $acteurRepository->findAll();
        return $this->render('acteur/index.html.twig', [
            'acteurs' => $acteurs
        ]);
    }


    /**
     * @Route("/acteur/nouveau", name="acteur_nouveau", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function nouveauActeur(Request $request, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data) {

            if ($data['nom'] && $data['prenom'] && $data['age']) {

                $acteur= new Acteur();

                $form = $this->createForm(ActeurType::class, $acteur);

                $form->submit($data);

                $validate = $validator->validate($acteur,null,'RegisterActeur');

                if(count($validate) !== 0) {
                    foreach ($validate as $error) {
                        return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
                    }
                }

                $this->em->persist($acteur);

                $this->em->flush();



                return new JsonResponse("Acteur ajoute", Response::HTTP_CREATED);

            } else {
                return new JsonResponse("Erreur", RESPONSE::HTTP_NO_CONTENT);
            }
        } else {
            return new JsonResponse("Erreur", RESPONSE::HTTP_NO_CONTENT);
        }

    }





}

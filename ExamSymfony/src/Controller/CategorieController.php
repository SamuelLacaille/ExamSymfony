<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\formulaires\CategorieType;
use App\Repository\CategorieRepository;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategorieController extends AbstractController
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
     * @param CategorieRepository $categorieRepository
     * @return Response
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return JsonResponse::fromJsonString($this->serializerService->RelationSerializer($categorieRepository->findAll(), 'json'));
    }






    /**
     * @Route("/categories", name="categorie")
     * @param CategorieRepository $categorieRepository
     * @return Response
     */
    public function getCategories(CategorieRepository $categorieRepository): Response
    {

        $categories = $categorieRepository->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories
        ]);
    }



    /**
     * @Route("/categorie/nouveau", name="categorie_nouveau", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function nouveauCategorie(Request $request, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data) {

            if ($data['nom']) {

                $categorie= new Categorie();

                $form = $this->createForm(CategorieType::class, $categorie);

                $form->submit($data);

                $validate = $validator->validate($categorie,null,'RegisterCategorie');

                if(count($validate) !== 0) {
                    foreach ($validate as $error) {
                        return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
                    }
                }

                $this->em->persist($categorie);

                $this->em->flush();



                return new JsonResponse("Categorie ajoutee", Response::HTTP_CREATED);

            } else {
                return new JsonResponse("Erreur", RESPONSE::HTTP_NO_CONTENT);
            }
        } else {
            return new JsonResponse("Erreur", RESPONSE::HTTP_NO_CONTENT);
        }

    }

    /**
     * @Route("/categorie/supprimer/{id}", name="categorie_supprimer", methods={"DELETE"})
     * @param CategorieRepository $categorieRepository
     * @param int $id
     * @return Response
     */
    public function deleteCategorie(CategorieRepository $categorieRepository, $id = 0): Response
    {
        $categorie = $categorieRepository->find($id);


        if ($categorie) {

            $this->em->remove($categorie);
            $this->em->flush();

            return new JsonResponse("categorie supprimee", Response::HTTP_OK);
        } else {
            return new JsonResponse("Rien a supprimer", Response::HTTP_BAD_REQUEST);
        }
    }


}

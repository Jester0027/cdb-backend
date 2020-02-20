<?php

namespace App\Controller;

use App\Entity\AnimalCategory;
use App\Repository\AnimalCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api")
 */
class AnimalCategoryController extends AbstractController
{

    /**
     * @Route("/animal_categories", name="create_animal_category", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager
    ): JsonResponse {
        $animalCategory = $serializer->deserialize($request->getContent(), AnimalCategory::class, 'json');
        $manager->persist($animalCategory);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/animal_categories/{id}", name="update_animal_category", methods={"PUT"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\AnimalCategory $animalCategoryToUpdate
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(
        Request $request,
        AnimalCategory $animalCategoryToUpdate,
        EntityManagerInterface $manager
    ): JsonResponse {
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = $key;
                $setter = 'set' . $name;
                $animalCategoryToUpdate->$setter($value);
            }
        }
        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

    /**
     * @Route("/animal_categories/{id}", name="show_animal_category", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\AnimalCategory $animalCategory
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(
        Request $request,
        AnimalCategory $animalCategory,
        SerializerInterface $serializer
    ): Response {
        $getAnimals = $request->query->get('animals') === "true" ? true : false;
        $data = $serializer->serialize($animalCategory, 'json', [
            'ignored_attributes' => [
                'animalCategory',
                $getAnimals ? '' : 'animals',
                'refuge'
            ],
            'skip_null_values' => true
        ]);

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/animal_categories", name="animal_categories", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\AnimalCategoryRepository $animalCategoryRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        AnimalCategoryRepository $animalCategoryRepository,
        SerializerInterface $serializer
    ): Response {
        $getAnimals = $request->query->get('animals') === "true" ? true : false;
        $animalCategories = $animalCategoryRepository->findAll();
        $data = $serializer->serialize($animalCategories, 'json', [
            'ignored_attributes' => [
                'animalCategory',
                $getAnimals ? '' : 'animals',
                'refuge'
            ],
            'skip_null_values' => true
        ]);

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/animal_categories/{id}", name="delete_animal_category", methods={"DELETE"})
     *
     * @param \App\Entity\AnimalCategory $animalCategory
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(AnimalCategory $animalCategory, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($animalCategory);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }
}

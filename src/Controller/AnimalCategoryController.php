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
}

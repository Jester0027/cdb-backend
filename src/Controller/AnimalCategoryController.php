<?php

namespace App\Controller;

use App\Entity\AnimalCategory;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use App\Repository\AnimalCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        AnimalCategory $animalCategory,
        SerializerInterface $serializer
    ): Response {
        $data = $serializer->serialize($animalCategory, 'json', SerializationContext::create()->setGroups(["category"]));

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
        AnimalCategoryRepository $animalCategoryRepository,
        SerializerInterface $serializer
    ): Response {
        $animalCategories = $animalCategoryRepository->findAll();
        $data = $serializer->serialize($animalCategories, 'json', SerializationContext::create()->setGroups(["category"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

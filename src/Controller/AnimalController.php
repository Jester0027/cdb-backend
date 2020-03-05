<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use JMS\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class AnimalController extends AbstractController
{
    /**
     * @Route("/animals/{id}", name="show_animal", methods={"GET"})
     *
     * @param \App\Entity\Animal $animal
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function show(Animal $animal, SerializerInterface $serializer): Response
    {
        $data = $serializer->serialize($animal, 'json', SerializationContext::create()->setGroups(["animal"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/animals", name="animals", methods={"GET"})
     *
     * @param \App\Repository\AnimalRepository $animalRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function index(AnimalRepository $animalRepository, SerializerInterface $serializer): Response
    {
        $animals = $animalRepository->findAll();
        $data = $serializer->serialize($animals, 'json', SerializationContext::create()->setGroups(["animal"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

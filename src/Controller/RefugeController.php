<?php

namespace App\Controller;

use App\Entity\Refuge;
use App\Repository\RefugeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class RefugeController extends AbstractController
{
    /**
     * @Route("/refuges/{id}", name="show_refuge", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Refuge $refuge
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Refuge $refuge, SerializerInterface $serializer): Response
    {
        $getAnimals = $request->query->get('animals') === "true" ? true : false;
        $data = $serializer->serialize($refuge, 'json', [
            "ignored_attributes" => [
                'refuge',
                $getAnimals ? '' : 'animals',
                'animalCategory'
            ],
            "skip_null_values" => true,
        ]);

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/refuges", name="refuge", methods={"GET"})
     */
    public function index(RefugeRepository $refugeRepository, SerializerInterface $serializer, Request $request)
    {
        $getAnimals = $request->query->get('animals') === "true" ? true : false;
        $refuges = $refugeRepository->findAll();
        $data = $serializer->serialize($refuges, 'json', [
            'ignored_attributes' => [
                'refuge',
                $getAnimals ? '' : 'animals',
                'animalCategory'
            ],
            'skip_null_values' => true
        ]);

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Refuge;
use App\Repository\AnimalRepository;
use App\Repository\RefugeRepository;
use App\Representation\AnimalsPagination;
use App\Representation\RefugesPagination;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class RefugeController extends AbstractController
{
    /**
     * @Route("/refuges/slug/{slug}", name="show_refuge_slug", methods={"GET"})
     * @Route("/refuges/{id}", name="show_refuge", methods={"GET"})
     *
     * @param Request $request
     * @param Refuge $refuge
     * @param SerializerInterface $serializer
     * @param AnimalRepository $animalRepository
     * @return Response
     */
    public function show(Request $request, Refuge $refuge, SerializerInterface $serializer, AnimalRepository $animalRepository): Response
    {
        if ($request->query->get('animals')) {
            $page = $request->query->getInt('page', 1);
            $animals = new AnimalsPagination($animalRepository->searchFromRefuge($refuge, 10, $page));
            $refuge->setAnimalsPagination($animals);
        }
        $data = $serializer->serialize($refuge, 'json', SerializationContext::create()->setGroups(["refuge", "animals"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/refuges", name="refuge", methods={"GET"})
     *
     * @param RefugeRepository $refugeRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return Response
     */
    public function index(RefugeRepository $refugeRepository, SerializerInterface $serializer, Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $refuges = new RefugesPagination($refugeRepository->search(10, $page));
        $data = $serializer->serialize($refuges, 'json', SerializationContext::create()->setGroups(["refuge"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

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
     * @Route("/refuges", name="new_refuge", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return void
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager
    ) {
        $refuge = $serializer->deserialize($request->getContent(), Refuge::class, 'json');
        $manager->persist($refuge);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/refuges/{id}", name="update_refuge", methods={"PUT"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Refuge $refugeToUpdate
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return void
     */
    public function update(
        Request $request,
        Refuge $refugeToUpdate,
        EntityManagerInterface $manager
    ) {
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = $key;
                $setter = 'set' . $name;
                $refugeToUpdate->$setter($value);
            }
        }
        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

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

    /**
     * @Route("/refuges/{id}", name="delete_refuge", methods={"DELETE"})
     *
     * @param \App\Entity\Refuge $refuge
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(Refuge $refuge, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($refuge);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "Refuge deleted"]);
    }
}

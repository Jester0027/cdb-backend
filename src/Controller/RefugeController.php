<?php

namespace App\Controller;

use App\Entity\Refuge;
use App\Repository\RefugeRepository;
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
     * @Route("/refuges/{id}", name="show_refuge", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Refuge $refuge
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Refuge $refuge, SerializerInterface $serializer): Response
    {
        $data = $serializer->serialize($refuge, 'json', SerializationContext::create()->setGroups(["refuge"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/refuges", name="refuge", methods={"GET"})
     */
    public function index(RefugeRepository $refugeRepository, SerializerInterface $serializer, Request $request)
    {
        $refuges = $refugeRepository->findAll();
        $data = $serializer->serialize($refuges, 'json', SerializationContext::create()->setGroups(["refuge"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

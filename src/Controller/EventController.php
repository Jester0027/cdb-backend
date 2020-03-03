<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\EventThemeRepository;
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
class EventController extends AbstractController
{
    /**
     * @Route("/events/{id}", name="show_event", methods={"GET"})
     *
     * @param \App\Entity\Event $event
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function show(Event $event, SerializerInterface $serializer): Response
    {
        $data = $serializer->serialize($event, 'json', [
            'ignored_attributes' => ['event'],
            'skip_null_values' => true
        ]);

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/events", name="events", methods={"GET"})
     *
     * @param \App\Repository\EventRepository $eventRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(EventRepository $eventRepository, SerializerInterface $serializer): Response
    {
        $events = $eventRepository->findAll();
        $data = $serializer->serialize($events, 'json', [
            'ignored_attributes' => ['event'],
            'skip_null_values' => true
        ]);

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

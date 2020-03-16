<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Representation\EventsPagination;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
        $data = $serializer->serialize($event, 'json', SerializationContext::create()->setGroups(["event"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/events", name="events", methods={"GET"})
     *
     * @param \App\Repository\EventRepository $eventRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(EventRepository $eventRepository, SerializerInterface $serializer, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $events = new EventsPagination($eventRepository->search(10, $page));
        $data = $serializer->serialize($events, 'json', SerializationContext::create()->setGroups(["event"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

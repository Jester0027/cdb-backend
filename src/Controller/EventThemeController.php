<?php

namespace App\Controller;

use App\Entity\EventTheme;
use App\Repository\EventRepository;
use JMS\Serializer\SerializerInterface;
use App\Repository\EventThemeRepository;
use App\Representation\EventsPagination;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @route("/api")
 */
class EventThemeController extends AbstractController
{
    /**
     * @Route("/event_themes/slug/{slug}", name="show_event_theme_slug", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\EventTheme $eventTheme
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showFromSlug(Request $request, EventTheme $eventTheme, SerializerInterface $serializer, EventRepository $eventRepository): Response
    {
        if ($request->query->get('events')) {
            $page = $request->query->getInt('page', 1);
            $events = new EventsPagination($eventRepository->searchFromTheme($eventTheme, 10, $page));
            $eventTheme->setEventsPagination($events);
        }
        $data = $serializer->serialize($eventTheme, 'json', SerializationContext::create()->setGroups(["theme", "events"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/event_themes/{id}", name="show_event_theme", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\EventTheme $eventTheme
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, EventTheme $eventTheme, SerializerInterface $serializer, EventRepository $eventRepository): Response
    {
        if ($request->query->get('events')) {
            $page = $request->query->getInt('page', 1);
            $events = new EventsPagination($eventRepository->searchFromTheme($eventTheme, 10, $page));
            $eventTheme->setEventsPagination($events);
        }
        $data = $serializer->serialize($eventTheme, 'json', SerializationContext::create()->setGroups(["theme", "events"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/event_themes", name="event_themes", methods={"GET"})
     *
     * @param \App\Repository\EventThemeRepository $eventThemeRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, EventThemeRepository $eventThemeRepository, SerializerInterface $serializer): Response
    {
        $eventThemes = $eventThemeRepository->findAll();
        $data = $serializer->serialize($eventThemes, 'json', SerializationContext::create()->setGroups(["theme"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

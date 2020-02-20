<?php

namespace App\Controller;

use App\Entity\EventTheme;
use App\Repository\EventThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @route("/api")
 */
class EventThemeController extends AbstractController
{
    /**
     * @Route("/event_themes", name="create_event_theme", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): JsonResponse
    {
        $eventTheme = $serializer->deserialize($request->getContent(), EventTheme::class, 'json');
        $manager->persist($eventTheme);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/event_themes/{id}", name="update_event_theme", methods={"PUT"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\EventTheme $eventTheme
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request, EventTheme $eventThemeToUpdate, EntityManagerInterface $manager): JsonResponse
    {
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = $key;
                $setter = 'set' . $name;
                $eventThemeToUpdate->$setter($value);
            }
        }
        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

    /**
     * @Route("/event_themes/{id}", name="show_event_theme", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\EventTheme $eventTheme
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, EventTheme $eventTheme, SerializerInterface $serializer): Response
    {
        $getEvents = $request->query->get('events') === 'true' ? true : false;
        $data = $serializer->serialize($eventTheme, 'json', [
            'ignored_attributes' => [
                $getEvents ? '' : 'event',
                'eventTheme'
            ],
            'skip_null_values' => true
        ]);

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
        $getEvents = $request->query->get('events') === 'true' ? true : false;
        $eventThemes = $eventThemeRepository->findAll();
        $data = $serializer->serialize($eventThemes, 'json', [
            'ignored_attributes' => [
                $getEvents ? '' : 'event',
                'eventTheme'
            ],
            'skip_null_values' => true
        ]);
        
        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/event_themes/{id}", name="delete_event_theme", methods={"DELETE"})
     *
     * @param \App\Entity\EventTheme $eventTheme
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(EventTheme $eventTheme, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($eventTheme);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "Event theme deleted"]);
    }
}

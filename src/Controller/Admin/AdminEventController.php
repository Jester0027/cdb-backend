<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Repository\EventThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEventController extends AbstractController
{
    /**
     * @Route("/events", name="create_event", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\EventThemeRepository $eventThemeRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(
        Request $request,
        EventThemeRepository $eventThemeRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $manager
    ): JsonResponse {
        $event = $serializer->deserialize($request->getContent(), Event::class, 'json');
        $themeName = $event->getEventTheme()->getName();
        $eventTheme = $eventThemeRepository->findOneBy(["name" => $themeName]);
        $event->setEventTheme($eventTheme);
        $manager->persist($event);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/events/{id}", name="update_event", methods={"PUT"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Event $eventToUpdate
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(
        Request $request,
        EventThemeRepository $eventThemeRepository,
        Event $eventToUpdate,
        EntityManagerInterface $manager
    ): JsonResponse {
        $data = json_decode($request->getContent());
        $themeName = $data->eventTheme->name ?? '';
        $theme = $eventThemeRepository->findOneBy(["name" => $themeName]);
        $data->eventTheme = $theme;
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = $key;
                $setter = 'set' . $name;
                $eventToUpdate->$setter($value);
            }
        }
        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

    /**
     * @Route("/events/{id}", name="delete_event", methods={"DELETE"})
     *
     * @param \App\Entity\Event $event
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(Event $event, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($event);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "Event deleted"]);
    }
}

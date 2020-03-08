<?php

namespace App\Controller\Admin;

use App\Entity\EventTheme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEventThemeController extends AbstractController
{
    /**
     * @Route("/event_themes", name="create_event_theme", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager
    ): JsonResponse {
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
    public function update(
        Request $request,
        EventTheme $eventThemeToUpdate,
        EntityManagerInterface $manager
    ): JsonResponse {
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
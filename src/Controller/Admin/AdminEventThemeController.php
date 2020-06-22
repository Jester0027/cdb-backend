<?php

namespace App\Controller\Admin;

use App\Entity\EventTheme;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/admin")
 */
class AdminEventThemeController extends AbstractController
{
    /**
     * @Route("/event_themes", name="create_event_theme", methods={"POST"})
     * @IsGranted("ROLE_MANAGER")
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        ValidatorInterface $validator
    ): Response
    {
        $eventTheme = $serializer->deserialize($request->getContent(), EventTheme::class, 'json');
        $errors = $validator->validate($eventTheme);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $manager->persist($eventTheme);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/event_themes/{id}", name="update_event_theme", methods={"PUT"})
     * @IsGranted("ROLE_MANAGER")
     *
     * @param Request $request
     * @param EventTheme $eventThemeToUpdate
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function update(
        Request $request,
        EventTheme $eventThemeToUpdate,
        EntityManagerInterface $manager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    )
    {
        $eventTheme = $serializer->deserialize($request->getContent(), EventTheme::class, 'json');
        $errors = $validator->validate($eventTheme);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $eventThemeToUpdate->setName($eventTheme->getName());
        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

    /**
     * @Route("/event_themes/{id}", name="delete_event_theme", methods={"DELETE"})
     * @IsGranted("ROLE_SUPERADMIN")
     *
     * @param EventTheme $eventTheme
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function delete(EventTheme $eventTheme, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($eventTheme);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "Event theme deleted"]);
    }
}

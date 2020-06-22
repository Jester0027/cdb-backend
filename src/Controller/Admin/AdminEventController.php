<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use JMS\Serializer\SerializerInterface;
use App\Repository\EventThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vich\UploaderBundle\Handler\UploadHandler;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * @Route("/api/admin")
 */
class AdminEventController extends AbstractController
{
    /**
     * @Route("/events", name="create_event", methods={"POST"})
     * @IsGranted("ROLE_MANAGER")
     *
     * @param Request $request
     * @param EventThemeRepository $eventThemeRepository
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function create(
        Request $request,
        EventThemeRepository $eventThemeRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $event = $serializer->deserialize($request->getContent(), Event::class, 'json');
        $themeSlug = $event->getEventTheme()->getSlug();
        $eventTheme = $eventThemeRepository->findOneBy(["slug" => $themeSlug]);
        $event->setEventTheme($eventTheme);
        $errors = $validator->validate($event);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $manager->persist($event);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/events/{id}", name="update_event", methods={"PUT"})
     * @IsGranted("ROLE_MANAGER")
     *
     * @param Request $request
     * @param EventThemeRepository $eventThemeRepository
     * @param Event $eventToUpdate
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function update(
        Request $request,
        EventThemeRepository $eventThemeRepository,
        Event $eventToUpdate,
        EntityManagerInterface $manager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    )
    {
        $event = $serializer->deserialize($request->getContent(), Event::class, 'json');
        $themeSlug = $event->getEventTheme()->getSlug();
        $eventTheme = $eventThemeRepository->findOneBy(["slug" => $themeSlug]);
        $event->setEventTheme($eventTheme);
        $errors = $validator->validate($event);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $eventToUpdate->setTitle($event->getTitle())
            ->setEventDate($event->getEventDate())
            ->setAddress($event->getAddress())
            ->setCity($event->getCity())
            ->setZipCode($event->getZipCode())
            ->setCoordinates($event->getCoordinates())
            ->setDescription($event->getDescription())
            ->setEventTheme($event->getEventTheme());

        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

    /**
     * @Route("/event_picture/{id}", name="add_event_picture", methods={"POST"})
     *
     * @param Request $request
     * @param Event $eventToUpdate
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function changePicture(Request $request, Event $eventToUpdate, EntityManagerInterface $manager): JsonResponse
    {
        $picture = $request->files->get('event_picture');
        $eventToUpdate->setImageFile($picture);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "updated event picture"]);
    }

    /**
     * @Route("/event_picture/{id}", name="delete_event_picture", methods={"DELETE"})
     *
     * @param Event $eventToUpdate
     * @param EntityManagerInterface $manager
     * @param UploadHandler $uploadHandler
     * @return JsonResponse
     */
    public function deletePicture(Event $eventToUpdate, EntityManagerInterface $manager, UploadHandler $uploadHandler): JsonResponse
    {
        $uploadHandler->remove($eventToUpdate, 'imageFile');
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "deleted event picture"]);
    }

    /**
     * @Route("/events/{id}", name="delete_event", methods={"DELETE"})
     * @IsGranted("ROLE_MANAGER")
     *
     * @param Event $event
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function delete(Event $event, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($event);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "Event deleted"]);
    }
}

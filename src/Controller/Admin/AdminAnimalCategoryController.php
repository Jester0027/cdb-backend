<?php

namespace App\Controller\Admin;

use App\Entity\AnimalCategory;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/admin")
 */
class AdminAnimalCategoryController extends AbstractController
{
    /**
     * @Route("/animal_categories", name="create_animal_category", methods={"POST"})
     * @IsGranted("ROLE_MANAGER")
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @return JsonResponse|Response
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        ValidatorInterface $validator
    )
    {
        $animalCategory = $serializer->deserialize($request->getContent(), AnimalCategory::class, 'json');
        $errors = $validator->validate($animalCategory);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $manager->persist($animalCategory);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"], 201);
    }

    /**
     * @Route("/animal_categories/{id}", name="update_animal_category", methods={"PUT"})
     * @IsGranted("ROLE_MANAGER")
     *
     * @param Request $request
     * @param AnimalCategory $animalCategoryToUpdate
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return JsonResponse|Response
     */
    public function update(
        Request $request,
        AnimalCategory $animalCategoryToUpdate,
        EntityManagerInterface $manager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    )
    {
        $animalCategory = $serializer->deserialize($request->getContent(), AnimalCategory::class, 'json');

        $errors = $validator->validate($animalCategory);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $animalCategoryToUpdate->setName($animalCategory->getName());

        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

    /**
     * @Route("/animal_categories/{id}", name="delete_animal_category", methods={"DELETE"})
     * @IsGranted("ROLE_SUPERADMIN")
     *
     * @param AnimalCategory $animalCategory
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function delete(AnimalCategory $animalCategory, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($animalCategory);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }
}
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        ValidatorInterface $validator
    ): JsonResponse {
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\AnimalCategory $animalCategoryToUpdate
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(
        Request $request,
        AnimalCategory $animalCategoryToUpdate,
        EntityManagerInterface $manager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $animalCategory = $serializer->deserialize($request->getContent(), AnimalCategory::class, 'json');

        $errors = $validator->validate($animalCategory);
        if(count($errors)) {
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
     * @param \App\Entity\AnimalCategory $animalCategory
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(AnimalCategory $animalCategory, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($animalCategory);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }
}
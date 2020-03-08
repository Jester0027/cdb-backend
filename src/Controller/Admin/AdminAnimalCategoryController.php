<?php

namespace App\Controller\Admin;

use App\Entity\AnimalCategory;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/admin")
 */
class AdminAnimalCategoryController extends AbstractController
{
    /**
     * @Route("/animal_categories", name="create_animal_category", methods={"POST"})
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
        $animalCategory = $serializer->deserialize($request->getContent(), AnimalCategory::class, 'json');
        $manager->persist($animalCategory);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/animal_categories/{id}", name="update_animal_category", methods={"PUT"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\AnimalCategory $animalCategoryToUpdate
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(
        Request $request,
        AnimalCategory $animalCategoryToUpdate,
        EntityManagerInterface $manager
    ): JsonResponse {
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = $key;
                $setter = 'set' . $name;
                $animalCategoryToUpdate->$setter($value);
            }
        }
        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

    /**
     * @Route("/animal_categories/{id}", name="delete_animal_category", methods={"DELETE"})
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
<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Repository\RefugeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnimalCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/admin")
 */
class AdminAnimalController extends AbstractController
{
    /**
     * @Route("/animals", name="new_animal", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Repository\RefugeRepository $refugeRepository
     * @param \App\Repository\AnimalCategoryRepository $animalCategoryRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return void
     */
    public function create(
        Request $request,
        RefugeRepository $refugeRepository,
        AnimalCategoryRepository $animalCategoryRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $manager
    ) {
        $animal = $serializer->deserialize($request->getContent(), Animal::class, 'json');
        $categoryName = $animal->getAnimalCategory()->getName();
        $refugeName = $animal->getRefuge()->getName();
        $category = $animalCategoryRepository->findOneBy(["name" => $categoryName]);
        $refuge = $refugeRepository->findOneBy(["name" => $refugeName]);
        $animal->setRefuge($refuge)
            ->setAnimalCategory($category);
        $manager->persist($animal);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/animals/{id}", name="update_animal", methods={"PUT"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Animal $animal
     * @param \App\Repository\RefugeRepository $refugeRepository
     * @param \App\Repository\AnimalRepository $animalRepository
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return void
     */
    public function update(
        Request $request,
        Animal $animalToUpdate,
        RefugeRepository $refugeRepository,
        AnimalCategoryRepository $animalCategoryRepository,
        EntityManagerInterface $manager
    ) {
        $data = json_decode($request->getContent());
        $categoryName = $data->animalCategory->name ?? '';
        $refugeName = $data->refuge->name ?? '';
        $category = $animalCategoryRepository->findOneBy(["name" => $categoryName]);
        $refuge = $refugeRepository->findOneBy(["name" => $refugeName]);
        $data->animalCategory = $category;
        $data->refuge = $refuge;
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = $key;
                $setter = 'set' . $name;
                $animalToUpdate->$setter($value);
            }
        }
        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

    /**
     * @Route("/animals/{id}", name="delete_animal", methods={"DELETE"})
     * 
     */
    public function delete(Animal $animal, EntityManagerInterface $manager)
    {
        $manager->remove($animal);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }
}
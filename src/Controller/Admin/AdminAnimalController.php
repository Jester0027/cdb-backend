<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Repository\RefugeRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AnimalCategoryRepository;
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
class AdminAnimalController extends AbstractController
{
    /**
     * @Route("/animals", name="new_animal", methods={"POST"})
     * @IsGranted("ROLE_MANAGER")
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
        EntityManagerInterface $manager,
        ValidatorInterface $validator
    ) {
        $animal = $serializer->deserialize($request->getContent(), Animal::class, 'json');
        $categorySlug = $animal->getAnimalCategory()->getSlug();
        $refugeSlug = $animal->getRefuge()->getSlug();
        $category = $animalCategoryRepository->findOneBy(["slug" => $categorySlug]);
        $refuge = $refugeRepository->findOneBy(["slug" => $refugeSlug]);
        $animal->setRefuge($refuge)
            ->setAnimalCategory($category);

        $errors = $validator->validate($animal);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $manager->persist($animal);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/animals/{id}", name="update_animal", methods={"PUT"})
     * @IsGranted("ROLE_MANAGER")
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
        EntityManagerInterface $manager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $data = json_decode($request->getContent());
        $categorySlug = $data->animalCategory->slug ?? '';
        $refugeSlug = $data->refuge->slug ?? '';
        $category = $animalCategoryRepository->findOneBy(["slug" => $categorySlug]);
        $refuge = $refugeRepository->findOneBy(["slug" => $refugeSlug]);
        $data->animalCategory = $category;
        $data->refuge = $refuge;
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = $key;
                $setter = 'set' . $name;
                $animalToUpdate->$setter($value);
            }
        }
        $errors = $validator->validate($animalToUpdate);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }

    /**
     * @Route("/animals/{id}", name="delete_animal", methods={"DELETE"})
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(Animal $animal, EntityManagerInterface $manager)
    {
        $manager->remove($animal);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }
}
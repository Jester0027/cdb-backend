<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api/admin")
 */
class AdminPictureController extends AbstractController
{
    /**
     * @Route("/picture/{id}", name="add_picture", methods={"POST"})
     * @IsGranted("ROLE_MANAGER")
     *
     * @param Request $request
     * @param Animal $animalToUpdate
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function add(
        Request $request,
        Animal $animalToUpdate,
        EntityManagerInterface $manager
    ): JsonResponse
    {
        $pictures = $request->files;

        foreach ($pictures as $file) {
            $picture = new Picture();
            $picture->setImageFile($file);
            $animalToUpdate->addPicture($picture);
            $manager->persist($picture);
        }
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Picture(s) created"]);
    }

    /**
     * @Route("/picture/{id}", name="delete_picture", methods={"DELETE"})
     * @IsGranted("ROLE_MANAGER")
     *
     * @param Picture $picture
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function delete(Picture $picture, EntityManagerInterface $manager)
    {
        $manager->remove($picture);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "Picture deleted"]);
    }
}

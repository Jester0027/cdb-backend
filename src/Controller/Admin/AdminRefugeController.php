<?php

namespace App\Controller\Admin;

use App\Entity\Refuge;
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
class AdminRefugeController extends AbstractController
{
    /**
     * @Route("/refuges", name="new_refuge", methods={"POST"})
     * @IsGranted("ROLE_SUPERADMIN")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return void
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        ValidatorInterface $validator
    ) {
        $refuge = $serializer->deserialize($request->getContent(), Refuge::class, 'json');
        $errors = $validator->validate($refuge);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $manager->persist($refuge);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/refuges/{id}", name="update_refuge", methods={"PUT"})
     * @IsGranted("ROLE_SUPERADMIN")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Refuge $refugeToUpdate
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return void
     */
    public function update(
        Request $request,
        Refuge $refugeToUpdate,
        EntityManagerInterface $manager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $refuge = $serializer->deserialize($request->getContent(), Refuge::class, 'json');
        $errors = $validator->validate($refugeToUpdate);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, ["Content-Type" => "application/json"]);
        }
        $refugeToUpdate->setName($refuge->getName())
            ->setAddress($refuge->getAddress())
            ->setCity($refuge->getCity())
            ->setZipCode($refuge->getZipCode())
            ->setCoordinates($refuge->getCoordinates())
            ->setDescription($refuge->getDescription())
        ;
        
        $manager->flush();
        return new JsonResponse(["code" => 200, "message" => "OK"]);
    }
    
    /**
     * @Route("/refuges/{id}", name="delete_refuge", methods={"DELETE"})
     *
     * @param \App\Entity\Refuge $refuge
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(Refuge $refuge, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($refuge);
        $manager->flush();

        return new JsonResponse(["code" => 200, "message" => "Refuge deleted"]);
    }
}

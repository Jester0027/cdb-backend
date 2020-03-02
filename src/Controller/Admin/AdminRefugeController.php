<?php

namespace App\Controller\Admin;

use App\Entity\Refuge;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminRefugeController extends AbstractController
{
    /**
     * @Route("/refuges", name="new_refuge", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return void
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager
    ) {
        $refuge = $serializer->deserialize($request->getContent(), Refuge::class, 'json');
        $manager->persist($refuge);
        $manager->flush();

        return new JsonResponse(["code" => 201, "message" => "Created"]);
    }

    /**
     * @Route("/refuges/{id}", name="update_refuge", methods={"PUT"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Refuge $refugeToUpdate
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return void
     */
    public function update(
        Request $request,
        Refuge $refugeToUpdate,
        EntityManagerInterface $manager
    ) {
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = $key;
                $setter = 'set' . $name;
                $refugeToUpdate->$setter($value);
            }
        }
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

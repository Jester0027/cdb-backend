<?php

namespace App\Controller\Admin;

use App\Entity\User;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/admin")
 */
class AdminSecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     * @IsGranted("ROLE_SUPERADMIN")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $manager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $content = json_decode($request->getContent());
        if (isset($content->username, $content->password)) {
            $user = new User();
            $user->setEmail($content->username);
            $user->setPassword($passwordEncoder->encodePassword($user, $content->password));
            $user->setRoles($user->getRoles());

            $errors = $validator->validate($user);
            if (count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, ["Content-Type" => "application/json"]);
            }

            $manager->persist($user);
            $manager->flush();

            $data = [
                "code" => 201,
                "message" => "User created"
            ];

            return new JsonResponse($data, 201);
        }

        $data = [
            "code" => 500,
            "message" => "Renseignez les clés email et password"
        ];

        return new JsonResponse($data, 500);
    }
}
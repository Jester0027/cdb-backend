<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
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
        if (isset($content->username)) {
            $user = new User();
            $user->setEmail($content->username);
            
            $str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
            $special = '!@#$:;';
            $password = '';
            for ($i = 0; $i < 15; $i++) {
                $password .= $str[mt_rand(0, strlen($str) - 1)];
            }
            for ($i = 0; $i < 5; $i++) {
                $password .= $special[mt_rand(0, strlen($special) - 1)];
            }
            $password = str_shuffle($password);

            $user->setPassword($passwordEncoder->encodePassword($user, $content->password));
            $user->setRoles(['ROLE_USER', 'ROLE_MANAGER']);

            $errors = $validator->validate($user);
            if (count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, ["Content-Type" => "application/json"]);
            }

            $manager->persist($user);
            $manager->flush();

            $data = [
                "code" => 201,
                "message" => "User created with password $password"
            ];

            return new JsonResponse($data, 201);
        }

        $data = [
            "code" => 500,
            "message" => "Renseignez le pseudo"
        ];

        return new JsonResponse($data, 500);
    }

    /**
     * @Route("/users", name="users", methods={"GET"})
     * @IsGranted("ROLE_SUPERADMIN")
     */
    public function getUsers(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $users = $userRepository->findAll();
        $data = $serializer->serialize($users, User::class, SerializationContext::create()->setGroups(['user']));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

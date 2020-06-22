<?php

namespace App\Controller;

use App\Mailer\MailerService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     *
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function checkCredentials(SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $data = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(["user"]));

        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }

    /**
     * @Route("/send_password_recovery", name="send_password_recovery", methods={"POST"})
     *
     * @param Request $request
     * @param UserRepository $repository
     * @param EntityManagerInterface $manager
     * @param MailerService $mailerService
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function sendLostPasswordRecoveryToken(Request $request, UserRepository $repository, EntityManagerInterface $manager, MailerService $mailerService)
    {
        $email = json_decode($request->getContent())->email;
        $user = $repository->findOneBy(['email' => $email]);
        if (!empty($user)) {
            $token = $user->generateToken();
            $manager->flush();
            $mailerService->sendPasswordRecoveryToken($user, $token);
        }

        return new JsonResponse(['code' => 200, 'message' => 'An email has been sent to recover the password']);
    }

    /**
     * @Route("/password_recovery_change", name="password_recovery_change", methods={"POST"})
     *
     * @param Request $request
     * @param UserRepository $repository
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function passwordRecoveryChange(Request $request, UserRepository $repository, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $data = json_decode($request->getContent());
        if (empty($data->password)) {
            return new JsonResponse(['code' => 500, "message" => "INVALID_PASSWORD"], 500);
        }
        $user = $repository->findOneBy(['email' => $data->email]);
        $isValid = $user->checkToken($data->token);
        if (!$isValid) {
            return new JsonResponse(['code' => 500, "message" => "INVALID_TOKEN"], 500);
        }
        $user->setPassword($passwordEncoder->encodePassword($user, $data->password));
        $manager->flush();

        return new JsonResponse(['code' => 200, 'message' => 'Password has been changed']);
    }
}

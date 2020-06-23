<?php

namespace App\Controller;

use App\Mailer\MailerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/send_contact", name="send_contact", methods={"POST"})
     *
     * @param MailerService $mailer
     * @param Request $request
     * @param HttpClientInterface $client
     * @return JsonResponse
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function sendContact(MailerService $mailer, Request $request, HttpClientInterface $client): JsonResponse
    {
        $data = json_decode($request->getContent());
        $userKey = $data->userKey;
        $sharedKey = $_ENV['G_SECRET'];
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $res = $client->request('POST', $url, [
            'body' => [
                'secret' => $sharedKey,
                'response' => $userKey
            ]
        ])->getContent();
        $data = json_decode($res);

        if ($data->success) {
            return $mailer->sendContactDemand();
        }
        return new JsonResponse(['code' => 500, 'message' => 'INVALID_CAPTCHA'], 500);
    }
}
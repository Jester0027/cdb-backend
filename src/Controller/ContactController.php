<?php

namespace App\Controller;

use App\Mailer\MailerService;
use App\Services\ReCaptchaService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/send_contact", name="send_contact", methods={"POST"})
     *
     * @param MailerService $mailer
     * @param ReCaptchaService $captchaService
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function sendContact(MailerService $mailer, ReCaptchaService $captchaService): JsonResponse
    {
        $captchaRes = $captchaService->checkReCaptcha();

        if ($captchaRes->success) {
            return $mailer->sendContactDemand();
        }
        return new JsonResponse(['code' => 500, 'message' => 'INVALID_CAPTCHA'], 500);
    }

    /**
     * @Route("/send_event_register", name="send_event_register", methods={"POST"})
     *
     * @param MailerService $mailerService
     * @param ReCaptchaService $captchaService
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function sendEventRegistration(MailerService $mailerService, ReCaptchaService $captchaService)
    {
        $captchaRes = $captchaService->checkReCaptcha();

        if ($captchaRes->success) {
            return $mailerService->sendEventRegistration();
        }
        return new JsonResponse(['code' => 500, 'message' => 'INVALID_CAPTCHA'], 500);
    }
}
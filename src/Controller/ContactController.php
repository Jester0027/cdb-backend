<?php

namespace App\Controller;

use App\Mailer\MailerService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/send_contact", name="send_contact", methods={"POST"})
     *
     * @param MailerService $mailer
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function sendContact(MailerService $mailer): JsonResponse
    {
        return $mailer->sendContactDemand();
    }
}
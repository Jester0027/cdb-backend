<?php

namespace App\Controller;

use App\Mailer\MailerService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/send_contact", name="send_contact", methods={"POST"})
     *
     * @param \App\Mailer\MailerService $mailer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendContact(MailerService $mailer): JsonResponse
    {
        return $mailer->sendContactDemand();
    }

    /**
     * @Route("/send_volunteering", name="send_volunteering", methods={"POST"})
     *
     * @param \App\Mailer\MailerService $mailer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendVolunteering(MailerService $mailer): JsonResponse
    {
        return $mailer->sendVolunteeringDemand();
    }
}
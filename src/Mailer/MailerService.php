<?php

namespace App\Mailer;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class MailerService
{
    const CONTACT = "";
    const VOLUNTEERING = "";

    private $mailer;
    private $request;
    private $content;

    public function __construct(Swift_Mailer $mailer, RequestStack $requestStack)
    {
        $this->mailer = $mailer;
        $this->request = $requestStack->getCurrentRequest();
        $this->content = $this->request->getContent();
    }

    private function sendMail(string $to)
    {
        $data = $this->content;
        $message = (new Swift_Message())
            ->setFrom($data->from)
            ->setTo($to)
            ->setSubject($data->subject)
            ->setBody($data->content)
        ;
        $result = $this->mailer->send($message);

        if(!$result) {
            return new JsonResponse(["error" => "could not send email"], 500);
        }
        return new JsonResponse(["code" => "200", "message" => "mail sent successfully"], 200);
    }

    public function sendContactDemand()
    {
        return $this->sendMail(self::CONTACT);
    }

    public function sendVolunteeringDemand()
    {
        return $this->sendMail(self::VOLUNTEERING);
    }
}
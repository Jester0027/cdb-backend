<?php

namespace App\Mailer;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    const CONTACT = "";
    const VOLUNTEERING = "";

    private $mailer;
    private $request;
    private $content;

    public function __construct(MailerInterface $mailer, RequestStack $requestStack)
    {
        $this->mailer = $mailer;
        $this->request = $requestStack->getCurrentRequest();
        $this->content = json_decode($this->request->getContent());
    }

    private function sendMail(string $to)
    {
        $data = $this->content;
        $message = (new Email())
            ->from($data->from)
            ->to($to)
            ->subject($data->subject)
            ->text($data->content)
        ;
        $this->mailer->send($message);

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
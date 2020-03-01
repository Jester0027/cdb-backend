<?php

namespace App\Mailer;

use Symfony\Component\HttpFoundation\Request;
use Swift_Mailer;
use Swift_Message;

class MailerService
{
    const CONTACT = "";
    const VOLUNTEERING = "";

    private $mailer;
    private $request;

    public function __construct(Swift_Mailer $mailer, Request $request)
    {
        $this->mailer = $mailer;
        $this->request = $request;
    }

    private function sendMail(string $to)
    {
        $data = json_decode($this->request->getContent());
        $message = (new Swift_Message())
            ->setFrom($data->from)
            ->setTo($to)
            ->setSubject($data->subject)
            ->setBody($data->content)
        ;
        $result = $this->mailer->send($message);

        if(!$result) {
            return ["error" => "could not send mail"];
        }
        return ["code" => "200", "message" => "mail sent successfully"];
    }

    public function sendContactDemand()
    {
        $this->sendMail(self::CONTACT);
    }

    public function sendVolunteeringDemand()
    {
        $this->sendMail(self::VOLUNTEERING);
    }
}
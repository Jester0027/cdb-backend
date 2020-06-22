<?php

namespace App\Mailer;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerService
{
    const CONTACT = "info@coeurdebouviers.be";
    const NOREPLY = "noreply@coeurdebouviers.be";

    private $mailer;
    private $request;
    private $content;

    public function __construct(MailerInterface $mailer, RequestStack $requestStack)
    {
        $this->mailer = $mailer;
        $this->request = $requestStack->getCurrentRequest();
        $this->content = json_decode($this->request->getContent());
    }

    /**
     * @param string $to
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
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

    /**
     * @param User $user
     * @param string $token
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function sendPasswordRecoveryToken(User $user, string $token)
    {
        $message = (new TemplatedEmail())
            ->from(self::NOREPLY)
            ->to($user->getEmail())
            ->subject("Récupération du mot de passe")
            ->htmlTemplate('emails/password-recovery.html.twig')
            ->context([
                'user' => $user,
                'token' => $token
            ])
        ;

        $this->mailer->send($message);

        return new JsonResponse(["code" => "200", "message" => "mail sent successfully"], 200);
    }

    /**
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function sendContactDemand()
    {
        return $this->sendMail(self::CONTACT);
    }
}
<?php


namespace App\Services;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReCaptchaService
{
    private $requestStack;
    private $client;
    private $content;

    public function __construct(RequestStack $requestStack, HttpClientInterface $client)
    {
        $this->requestStack = $requestStack;
        $this->client = $client;
        $this->content = $requestStack->getCurrentRequest()->getContent();
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function checkReCaptcha()
    {
        $data = json_decode($this->content);
        $userKey = $data->userKey;
        $sharedKey = $_ENV['G_SECRET'];
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $res = $this->client->request('POST', $url, [
            'body' => [
                'secret' => $sharedKey,
                'response' => $userKey
            ]
        ])->getContent();
        return json_decode($res);
    }
}
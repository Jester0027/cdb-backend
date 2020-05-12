<?php

namespace App\Controller;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return void
     */
    public function checkCredentials(SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $data = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(["user"]));
        
        return new Response($data, 200, ["Content-Type" => "application/json"]);
    }
}

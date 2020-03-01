<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    /**
     * @Route("/send_contact", name="send_contact", methods={"POST"})
     */
    public function sendContact()
    {
        
    }
}
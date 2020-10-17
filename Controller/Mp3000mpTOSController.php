<?php

namespace mp3000mp\TOSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Mp3000mpTOSController extends AbstractController
{

    public function index()
    {
        return $this->render('@Mp3000mpTOS/tos.html.twig', [

        ]);
    }

}

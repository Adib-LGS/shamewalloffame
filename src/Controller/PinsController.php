<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="pins.home")
     */
    public function index()
    {
        return $this->render('pins/index.html.twig');
    }
}

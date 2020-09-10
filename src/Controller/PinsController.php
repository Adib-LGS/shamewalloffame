<?php

namespace App\Controller;

use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="pins.home")
     */
    public function index(PinRepository $pinRepository)
    {
        //dd($pinRepository->findAll());

        $pins = $pinRepository->findAll();

        return $this->render('pins/index.html.twig', compact('pins'));
    }
}

<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PinRepository $pinRepository): Response
    {
        //dd($pinRepository->findAll());

        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="pins.show")
     */
    public function show(Pin $pin): Response
    {
        //dd($pin);
        return $this->render('pins/show.html.twig', compact('pin'));
    }
}

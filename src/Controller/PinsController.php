<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="home", methods="GET")
     */
    public function index(PinRepository $pinRepository): Response
    {
        //dd($pinRepository->findAll());

        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/create", name="pins.create", methods="GET|POST")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;

        $formView = $this->createFormBuilder($pin)
        ->add('imageFile', VichImageType::class, [
            'label' => 'Image (JPG or PNG file)',
            'required' => false,
            'allow_delete' => true,
            'delete_label' => 'Delete',
            'download_uri' => false,
        ])
            ->add('Title', TextType::class)
            ->add('Description', TextareaType::class)
            ->getForm();

        $formView->handleRequest($request);

        if($formView->isSubmitted() && $formView->isValid()){
            //dd('create pin');
            $pin->setUser($this->getUser());
            $pin = $formView->getData();
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin successfully created !');

            return $this->redirectToRoute('home');

        }
        return $this->render('pins/create.html.twig', ['formView' => $formView->createView()]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="pins.show", methods="GET")
     */
    public function show(Pin $pin): Response
    {
        //dd($pin);
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="pins.edit", methods="GET|PUT")
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $em): Response
    {   
        //dd($pin);

        $formView = $this->createFormBuilder($pin, ['method' => 'PUT'])
        ->add('imageFile', VichImageType::class, [
            'label' => 'Image (JPG or PNG file)',
            'required' => false,
            'allow_delete' => true,
            'delete_label' => 'Delete',
            'download_uri' => false,
            'imagine_pattern' => 'squared_thumbnail_small'
        ])
            ->add('Title', TextType::class)
            ->add('Description', TextareaType::class)
            ->getForm();
        $formView->handleRequest($request);

        if($formView->isSubmitted() && $formView->isValid()){
            $em->flush();

            $this->addFlash('success', 'Pin successfully updated !');

            return $this->redirectToRoute('home');
        }
        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'formView' => $formView->createView()
        ]);
    }

     /**
     * @Route("/pins/{id<[0-9]+>}", name="pins.delete", methods="DELETE")
     */
    public function delete(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {   
        //dd('del.');
        if($this->isCsrfTokenValid('pin-delete' . $pin->getId(), $request->request->get('csrfTkn'))){
            $em->remove($pin);
            $em->flush();

            $this->addFlash('info', 'Pin successfully deleted !');
        }

        return $this->redirectToRoute('home');
    }
}

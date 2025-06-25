<?php

namespace App\Controller\BackController;

use App\Entity\Files;
use App\Entity\Sliders;
use App\Form\SlidersForm;
use App\Service\FileUploader;
use App\Repository\SlidersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/sliders')]
final class SlidersController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/', name: 'carousel')]
    public function index(Request $request, SlidersRepository $slidersRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'carousel');
        
        $slide = new Sliders();
        $form = $this->createForm(SlidersForm::class, $slide);
        $form->handleRequest($request);
        $slides = $slidersRepository->findAll();


        // Soumission et validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            
            // Gestion de l'upload de l'avatar
            if ($file = $form->get('image')->getData()) {
                $data = $this->fileUploader->upload($file);
                $fileEntity = (new Files())
                    ->setFilename($data['filename'])
                    ->setType($data['type'])
                    ->setAlt('Cover de ' . $slide->getTitre());
                $slide->setImage($fileEntity);
                $this->em->persist($fileEntity);
            }

            // Timestamps et Userstamps
            $slide->updatedTimestamps();
            $slide->updatedUserstamps($this->getUser());

            $this->em->persist($slide);
            $this->em->flush();

            $this->addFlash('success', 'Ajout effectué avec succès');
            return $this->redirectToRoute('carousel');
        }
        return $this->render('back/pages-builder/sliders.html.twig', [
            'slides' => $slides,
            'new_slide' => $form
        ]);
    }
}

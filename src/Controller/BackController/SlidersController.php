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
        $session->set('menu', 'pages_builder');
        $session->set('subMenu', 'carousel');
        
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

    #[Route('/modification', name: 'slide_update', methods: ['POST'])]
    public function update(Request $request, SlidersRepository $sliderRepository, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST') && $slide = $sliderRepository->find($request->get('slide_id'))) {
            $titre = $request->get('slide_titre');
            $lien = $request->get('slide_lien');
            $bouton = $request->get('slide_bouton');
            $online = $request->get('slide_online') ?? null;
            $description = $request->get('slide_description');

            // Gestion de l'upload de l'avatar
            if ($file = $request->files->get('slide_image')) {
                $data = $this->fileUploader->upload($file);
                $fileEntity = (new Files())
                    ->setFilename($data['filename'])
                    ->setType($data['type'])
                    ->setAlt('Cover de ' . $slide->getTitre());
                $slide->setImage($fileEntity);
                $this->em->persist($fileEntity);
            }

            // Timestamps et Userstamps
            $slide->setTitre($titre)
                ->setLien($lien)
                ->setBouton($bouton)
                ->setOnline($online)
                ->setDescription($description)
                ->updatedTimestamps();
            $slide->updatedUserstamps($this->getUser());

            $em->persist($slide);
            $em->flush();
            $this->addFlash('success', 'Modification effectué avec succès');
        }
        else $this->addFlash('error', 'Carousel introuvable !');
        return $this->redirectToRoute('carousel', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{slide}', name: 'slide_delete', methods: ['POST'])]
    public function delete(Request $request, Sliders $slide, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slide->getId(), $request->getPayload()->getString('_token'))) {
            $slide->remove($this->getUser());
            $em->flush();
        }
        return $this->redirectToRoute('carousel', [], Response::HTTP_SEE_OTHER);
    }

}

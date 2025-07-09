<?php

namespace App\Controller\BackController;

use App\Entity\NotesPublications;
use App\Form\NotesPublicationsForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NotesPublicationsRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/notes-publications')]
final class NotesPublicationsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    #[Route('/', name: 'app_notes_publications')]
    public function index(Request $request, NotesPublicationsRepository $notesPublicationsRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'notes_publications');
        
        $note = new NotesPublications();
        $form = $this->createForm(NotesPublicationsForm::class, $note);
        $form->handleRequest($request);


        // Soumission et validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // Timestamps et Userstamps
            $note->updatedTimestamps();
            $note->updatedUserstamps($this->getUser());

            $this->em->persist($note);
            $this->em->flush();

            $this->addFlash('success', 'Ajout effectué avec succès');
            return $this->redirectToRoute('app_notes_publications');
        }
        return $this->render('back/pages-builder/notes_publications.html.twig', [
            'new_note' => $form,
            'notes' => $notesPublicationsRepository->findAll()
        ]);
    }
}

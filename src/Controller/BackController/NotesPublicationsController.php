<?php

namespace App\Controller\BackController;

use Symfony\Component\HttpFoundation\JsonResponse;
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
        $session->set('menu', 'pages_builder');
        $session->set('subMenu', 'notes_publications');
        
        $note = new NotesPublications();
        $form = $this->createForm(NotesPublicationsForm::class, $note);
        $form->handleRequest($request);


        // Soumission et validation du formulaire
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            else {
                // Timestamps et Userstamps
                $note->generateSlug()->updatedTimestamps();
                $note->updatedUserstamps($this->getUser());

                $this->em->persist($note);
                $this->em->flush();

                $this->addFlash('success', 'Ajout effectué avec succès');
                return $this->redirectToRoute('app_notes_publications');
            }
        }
        return $this->render('back/pages-builder/notes_publications.html.twig', [
            'new_note' => $form,
            'notes' => $notesPublicationsRepository->findAll()
        ]);
    }

    #[Route('/modification', name: 'note_update', methods: ['POST'])]
    public function update(Request $request, NotesPublicationsRepository $notesRepository, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST') && $note = $notesRepository->find($request->get('note_id'))) {
            $titre = $request->get('note_titre');
            $resume = $request->get('note_resume');
            $online = $request->get('note_online') ?? null;
            $description = $request->get('note_description');

            // Timestamps et Userstamps
            $note->setTitre($titre)
                ->setResume($resume)
                ->setOnline($online)
                ->setDescription($description)
                ->generateSlug()
                ->updatedTimestamps();
            $note->updatedUserstamps($this->getUser());

            $em->persist($note);
            $em->flush();
            $this->addFlash('success', 'Modification effectué avec succès');
        }
        else $this->addFlash('error', 'Publication introuvable !');
        return $this->redirectToRoute('app_notes_publications', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{note}', name: 'note_delete', methods: ['POST'])]
    public function delete(Request $request, NotesPublications $note, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->getPayload()->getString('_token'))) {
            $note->remove($this->getUser());
            $em->flush();
        }
        return $this->redirectToRoute('app_notes_publications', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete-notes-selected', name: 'notes_selected_delete', methods: ['POST'])]
    public function deleteNotesSelected(Request $request, EntityManagerInterface $em, NotesPublicationsRepository $notesRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['itemsDeleted'] as $id) {
                if ($note = $notesRepository->find($id)) $note->remove($this->getUser());
                $em->flush();
            }
        }
        return new JsonResponse(true, 200);
    }
}

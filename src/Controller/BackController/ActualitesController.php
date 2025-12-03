<?php

namespace App\Controller\BackController;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Files;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Actualites;
use App\Form\ActualitesForm;
use App\Repository\ActualitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-admin/actualites')]
final class ActualitesController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/', name: 'list_actualites')]
    public function index(Request $request, ActualitesRepository $actualitesRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'pages_builder');
        $session->set('subMenu', 'actualites');
        
        $actualite = new Actualites();
        $form = $this->createForm(ActualitesForm::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            else {
                // Gestion de l'upload de l'avatar
                if ($thumbnailFile = $form->get('thumbnail')->getData()) {
                    $thumbnail = $this->fileUploader->upload($thumbnailFile);
                    $fileEntity = (new Files())
                        ->setFilename($thumbnail['filename'])
                        ->setType($thumbnail['type'])
                        ->setAlt('Vignette de ' . $actualite->getTitre());
                    $actualite->setThumbnail($fileEntity);
                    $this->em->persist($fileEntity);
                }
                if ($coverFile = $form->get('cover')->getData()) {
                    $cover = $this->fileUploader->upload($coverFile);
                    $fileEntity = (new Files())
                        ->setFilename($cover['filename'])
                        ->setType($cover['type'])
                        ->setAlt('Cover de ' . $actualite->getTitre());
                    $actualite->setCover($fileEntity);
                    $this->em->persist($fileEntity);
                }

                // Timestamps et Userstamps
                $actualite->generateSlug()->updatedTimestamps();
                $actualite->updatedUserstamps($this->getUser());

                $this->em->persist($actualite);
                $this->em->flush();

                $this->addFlash('success', 'Ajout effectué avec succès');
                return $this->redirectToRoute('list_actualites');
            }
        }
        return $this->render('back/pages-builder/actualites.html.twig', [
            'new_form' => $form,
            'actualites' => $actualitesRepository->findAll()
        ]);
    }

    #[Route('/modification', name: 'actualite_update', methods: ['POST'])]
    public function update(Request $request, ActualitesRepository $actualitesRepository, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST') && $actualite = $actualitesRepository->find($request->get('actualite_id'))) {
            $titre = $request->get('actualite_titre');
            $online = $request->get('actualite_online') ?? null;
            $description = $request->get('actualite_description');

            // Gestion de l'upload de l'avatar
            if ($thumbnailFile = $request->files->get('actualite_thumbnail')) {
                $thumbnail = $this->fileUploader->upload($thumbnailFile);
                $fileEntity = (new Files())
                    ->setFilename($thumbnail['filename'])
                    ->setType($thumbnail['type'])
                    ->setAlt('Vignette de ' . $actualite->getTitre());
                $actualite->setThumbnail($fileEntity);
                $this->em->persist($fileEntity);
            }
            if ($coverFile = $request->get('actualite_cover')) {
                $cover = $this->fileUploader->upload($coverFile);
                $fileEntity = (new Files())
                    ->setFilename($cover['filename'])
                    ->setType($cover['type'])
                    ->setAlt('Cover de ' . $actualite->getTitre());
                $actualite->setCover($fileEntity);
                $this->em->persist($fileEntity);
            }

            // Timestamps et Userstamps
            $actualite->setTitre($titre)
                ->setOnline($online)
                ->setDescription($description)
                ->updatedTimestamps();
            $actualite->updatedUserstamps($this->getUser());

            $em->persist($actualite);
            $em->flush();
            $this->addFlash('success', 'Modification effectué avec succès');
        }
        else $this->addFlash('error', 'Actualité introuvable !');
        return $this->redirectToRoute('list_actualites', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{actualite}', name: 'actualite_delete', methods: ['POST'])]
    public function delete(Request $request, Actualites $actualite, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actualite->getId(), $request->getPayload()->getString('_token'))) {
            $actualite->remove($this->getUser());
            $em->flush();
        }
        return $this->redirectToRoute('list_actualites', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete-actualites-selected', name: 'actualites_selected_delete', methods: ['POST'])]
    public function deleteActualitesSelected(Request $request, EntityManagerInterface $em, ActualitesRepository $actualitesRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['itemsDeleted'] as $id) {
                if ($actualite = $actualitesRepository->find($id)) $actualite->remove($this->getUser());
                $em->flush();
            }
        }
        return new JsonResponse(true, 200);
    }
}

<?php

namespace App\Controller\BackController;

use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Files;
use App\Form\MediasForm;
use App\Repository\FilesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-admin/mediatheque')]
final class MediasController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/', name: 'app_medias')]
    public function index(Request $request, FilesRepository $filesRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'medias');
        $session->set('subMenu', null);

        $form = $this->createForm(MediasForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('filename')->getData();

            if ($files) {
                foreach ($files as $file) {
                    $data = $this->fileUploader->upload($file);

                    $media = new Files();
                    $media
                        ->setFilename($data['filename'])
                        ->setType('medias')
                        ->setSize($data['size'])
                        ->setAlt($data['originalName']);
                    $media->updatedTimestamps();
                    $media->updatedUserstamps($this->getUser());

                    $this->em->persist($media);
                }

                $this->em->flush();
            }

            $this->addFlash('success', 'Ajout effectué avec succès.');
            return $this->redirectToRoute('app_medias');
        }

        return $this->render('back/mediatheque/liste.html.twig', [
            'form' => $form,
            'medias' => $filesRepository->findAllBy('medias'),
        ]);
    }

    #[Route('/delete/{id}', name: 'media_delete', methods: ['POST'])]
    public function delete(Request $request, Files $media, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$media->getId(), $request->getPayload()->getString('_token'))) {
            $media->remove($this->getUser());
            $em->flush();
        }
        return $this->redirectToRoute('app_medias', [], Response::HTTP_SEE_OTHER);
    }

}

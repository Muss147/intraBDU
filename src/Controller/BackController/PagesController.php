<?php

namespace App\Controller\BackController;

use App\Entity\Files;
use App\Entity\Pages;
use App\Form\PagesForm;
use App\Service\FileUploader;
use App\Repository\PagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/pages-builder')]
final class PagesController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/liste-pages', name: 'list_pages')]
    public function liste(PagesRepository $pagesRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'pages_builder');
        $session->set('subMenu', 'pages');

        return $this->render('back/pages/liste.html.twig', [
            'pages' => $pagesRepository->findAll(),
        ]);
    }

    #[Route('/pages/{id?}', name: 'page.new')]
    public function page(Request $request, $id, PagesRepository $pagesRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'pages_builder');
        $session->set('subMenu', 'pages');

        $page = $id ? $pagesRepository->find($id) : new Pages();
        $form = $this->createForm(PagesForm::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            else {
                // Gestion de l'upload des fichiers
                if ($file = $form->get('cover')->getData()) {
                    $data = $this->fileUploader->upload($file);
                    $fileEntity = (new Files())
                        ->setFilename($data['filename'])
                        ->setType($data['type'])
                        ->setSize($data['size'])
                        ->setAlt($page->getTitre());
                    $page->setCover($fileEntity);
                    $this->em->persist($fileEntity);
                }
                $page->generateSlug();
                $page->updatedTimestamps();
                $page->updatedUserstamps($this->getUser());

                $this->em->persist($page);
                $this->em->flush();
        
                $this->addFlash('success', 'Page ajoutée avec succès.');
                return $this->redirectToRoute('list_pages');
            }
        }

        return $this->render('back/pages/page.html.twig', [
            'form' => $form,
            'page' => $page,
        ]);
    }

    #[Route('/delete/{page}', name: 'page_delete', methods: ['POST'])]
    public function deletePage(Request $request, Pages $page): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getId(), $request->getPayload()->getString('_token'))) {
            $page->remove($this->getUser());
            $this->em->flush();
        }
        $this->addFlash('success', 'Suppression effectuée avec succès.');
        return $this->redirectToRoute('list_pages');
    }

}

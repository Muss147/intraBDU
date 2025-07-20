<?php

namespace App\Controller\BackController;

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
        $session->set('menu', 'actualites');
        
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
                if ($file = $form->get('cover')->getData()) {
                    $data = $this->fileUploader->upload($file);
                    $fileEntity = (new Files())
                        ->setFilename($data['filename'])
                        ->setType($data['type'])
                        ->setAlt('Cover de ' . $actualite->getTitre());
                    $actualite->setCover($fileEntity);
                    $this->em->persist($fileEntity);
                }

                // Timestamps et Userstamps
                $actualite->updatedTimestamps();
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
}

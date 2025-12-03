<?php

namespace App\Controller\BackController;

use App\Entity\Files;
use App\Entity\Agents;
use App\Form\AgentsForm;
use App\Form\ChartesForm;
use App\Service\FileUploader;
use App\Repository\AgentsRepository;
use App\Repository\FilesRepository;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/chartes-graphiques')]
final class ChartesController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/', name: 'liste_chartes')]
    public function index(Request $request, FilesRepository $charteRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'chartes');
        $session->set('subMenu', null);

        $charte = new Files();
        $charte->setType('Chartes');
        $form = $this->createForm(ChartesForm::class, $charte);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if(!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            else {
                // Gestion de l'upload des fichiers
                if ($file = $form->get('filename')->getData()) {
                    $data = $this->fileUploader->upload($file);
                    $charte
                        ->setFilename($data['filename'])
                        ->setSize($data['size'])
                        // ->setAlt($charte->getNom())
                    ;
                }
                $charte->updatedTimestamps();
                $charte->updatedUserstamps($this->getUser());

                $this->em->persist($charte);
                $this->em->flush();
        
                $this->addFlash('success', 'Charte ajouté avec succès.');
                return $this->redirectToRoute('liste_chartes');
            }
        }
        return $this->render('back/pages-builder/chartes_graphiques.html.twig', [
            'form' => $form,
            'chartes' => $charteRepository->findAllBy('Chartes'),
        ]);
    }

    #[Route('/edit/{charte}/', name: 'edit_charte')]
    public function editCharte(Request $request, Files $charte): Response
    {
        if ($request->isMethod('POST')) {
            $alt = $request->get('filealt') ?? null;

            // Gestion de l'upload des fichiers
            if ($file = $request->files->get('filename')) {
                $data = $this->fileUploader->upload($file);
                $charte
                    ->setFilename($data['filename'])
                    ->setSize($data['size'])
                ;
            }
            $charte->setAlt($alt)->updatedTimestamps();
            $charte->updatedUserstamps($this->getUser());

            $this->em->persist($charte);
            $this->em->flush();
            
            $this->addFlash('success', 'Charte modifié avec succès.');
        }
        return $this->redirectToRoute('liste_chartes');
    }

    #[Route('/delete/{charte}', name: 'charte_delete', methods: ['POST'])]
    public function deleteCharte(Request $request, Files $charte): Response
    {
        if ($this->isCsrfTokenValid('delete'.$charte->getId(), $request->getPayload()->getString('_token'))) {
            $charte->remove($this->getUser());
            $this->em->flush();
        }
        $this->addFlash('success', 'Suppression effectuée avec succès.');
        return $this->redirectToRoute('liste_chartes');
    }
}

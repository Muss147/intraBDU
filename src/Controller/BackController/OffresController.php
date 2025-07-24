<?php

namespace App\Controller\BackController;

use App\Form\OffresForm;
use App\Entity\OffresEmploi;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffresEmploiRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/offres')]
final class OffresController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private EntityManagerInterface $em
    ) {}

    #[Route('/', name: 'list_offres')]
    public function index(OffresEmploiRepository $offresEmploiRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'offres');
        $session->set('subMenu', 'liste');

        return $this->render('back/offres/liste.html.twig', [
            'offres' => $offresEmploiRepository->findAll(),
        ]);
    }

    #[Route('/details/{offre?}', name: 'add_offre')]
    public function details(Request $request, $offre, OffresEmploiRepository $offresEmploiRepository, ParametresRepository $parametresRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'offres');
        $session->set('subMenu', 'liste');

        if (!$offre || !$offre = $offresEmploiRepository->find($offre)) $offre = new OffresEmploi();
        if (count($offre->getMissions()) === 0) $offre->setMissions(['']);
        if (count($offre->getProfils()) === 0) $offre->setProfils(['']);

        $form = $this->createForm(OffresForm::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            else {
                $offre->generateSlug();
                $offre->updatedTimestamps();
                $offre->updatedUserstamps($this->getUser());

                $this->em->persist($offre);
                $this->em->flush();
        
                $this->addFlash('success', 'Offre d\'emploi ajouté(e) avec succès.');
                return $this->redirectToRoute('list_offres');
            }
        }
        return $this->render('back/offres/add.html.twig', [
            'offre' => $offre,
            'form_offre' => $form,
            'metiers' => $parametresRepository->findByType('metiers'),
            'agences' => $parametresRepository->findByType('agences'),
            'directions' => $parametresRepository->findByType('directions'),
        ]);
    }

    #[Route('/{offre}/delete', name: 'offre_delete', methods: ['POST'])]
    public function delete(Request $request, OffresEmploi $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->getPayload()->getString('_token'))) {
            $offre->remove($this->getUser());
            $this->em->flush();
        }
        return $this->redirectToRoute('list_offres', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete-offres-selected', name: 'offres_selected_delete', methods: ['POST'])]
    public function deleteOffresSelected(Request $request, OffresEmploiRepository $offresRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['offresDeleted'] as $id) {
                if ($offre = $offresRepository->find($id)) $offre->remove($this->getUser());
                $this->em->flush();
            }
        }
        return new JsonResponse(true, 200);
    }
}

<?php

namespace App\Controller\BackController;

use App\Repository\IncidentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/incidents')]
final class IncidentsController extends AbstractController
{
    #[Route('/', name: 'list_incidents')]
    public function listeIncidents(Request $request, IncidentsRepository $incidentsRepository, SessionInterface $session, MailerInterface $mailer): Response
    {
        $session->set('menu', null);
        $session->set('subMenu', 'incidents');

        return $this->render('back/incidents-alertes/incidents.html.twig', [
            'incidents' => $incidentsRepository->findAll(),
        ]);
    }


    #[Route('/correction', name: 'correct_incident')]
    public function edit(Request $request, IncidentsRepository $incidentsRepository, EntityManagerInterface $em)
    {
        if ($request->isMethod('POST') && $incident = $incidentsRepository->find($request->get('fiche_id'))) {
            $description = $request->get('fiche_correction');
            $incident->setCorrigee(true)->setCorrections($description);

            $em->persist($incident);
            $em->flush();
    
            $this->addFlash('success', 'Incident corrigé avec succès.');
        }
        return $this->redirectToRoute('list_incidents');
    }
}

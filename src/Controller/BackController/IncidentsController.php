<?php

namespace App\Controller\BackController;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\IncidentsRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
}

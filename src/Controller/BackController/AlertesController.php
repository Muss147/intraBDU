<?php

namespace App\Controller\BackController;

use App\Repository\AlertesRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/espace-admin/alertes')]
final class AlertesController extends AbstractController
{
    #[Route('/', name: 'list_alertes')]
    public function listealertes(Request $request, AlertesRepository $alertesRepository, SessionInterface $session, MailerInterface $mailer): Response
    {
        $session->set('menu', null);
        $session->set('subMenu', 'alertes');

        return $this->render('back/incidents-alertes/alertes.html.twig', [
            'alertes' => $alertesRepository->findAll(),
        ]);
    }
}

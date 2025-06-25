<?php

namespace App\Controller\BackController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/espace-admin')]
final class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function dashboard(SessionInterface $session): Response
    {
        $session->set('menu', 'dashboard');
        $session->set('subMenu', '');
        return $this->render('back/dashboard.html.twig');
    }
}

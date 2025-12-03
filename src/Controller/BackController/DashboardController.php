<?php

namespace App\Controller\BackController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AgentsRepository;
use App\Repository\OffresEmploiRepository;
use App\Service\PageStatsService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PageViewRepository;
use App\Service\DashboardService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/espace-admin')]
final class DashboardController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private PageStatsService $pageStatsService,
        private PageViewRepository $pageViewRepository,
        private AgentsRepository $agentsRepository,
        private OffresEmploiRepository $offresRepository,
    ) {}

    #[Route('/', name: 'dashboard')]
    public function dashboard(SessionInterface $session): Response
    {
        $session->set('menu', 'dashboard');
        $session->set('subMenu', '');
        
        $month = date('m');
        $year = date('Y');

        $newUsers = $this->agentsRepository->countNewUsersLast30Days();
        $pagesViewed = $this->pageViewRepository->countByMonth($year, $month);
        $totalPages = $this->pageStatsService->getTotalPages();
        $percentViewed = $totalPages > 0 ? round($pagesViewed * 100 / $totalPages) : 0;
        $sessions = $this->pageViewRepository->countSessionsByMonth($year, $month);
        $bounceRate = $this->pageViewRepository->getBounceRate($year, $month);
        $avgSessionDuration = $this->pageViewRepository->getAverageSessionDuration($year, $month);

        return $this->render('back/dashboard.html.twig', [
            'newUsers' => $newUsers,
            'pagesViewed' => $pagesViewed,
            'percentViewed' => $percentViewed,
            'sessions' => $sessions,
            'bounceRate' => $bounceRate,
            'avgSessionDuration' => $avgSessionDuration,
            'offres' => $this->offresRepository->findAll()
        ]);
    }
}

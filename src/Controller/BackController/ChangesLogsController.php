<?php

namespace App\Controller\BackController;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ActivityLog;
use App\Repository\ActivityLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-admin/changes-logs')]
final class ChangesLogsController extends AbstractController
{
    #[Route('/', name: 'changes_logs')]
    public function index(Request $request, ActivityLogRepository $activityLogRepository, SessionInterface $session): Response
    {
        // Définition du menu actif
        $session->set('menu', 'logs');
        $session->set('subMenu', '');

        return $this->render('back/security/changes_logs.html.twig', [
            'logs' => $activityLogRepository->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_log', methods: ['POST'])]
    public function delete(Request $request, ActivityLog $log, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$log->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($log);
            $entityManager->flush();
        }

        return $this->redirectToRoute('changes_logs', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/empty-all-logs', name: 'empty_logs', methods: ['POST'])]
    public function emptyAllLogs(Request $request, ActivityLogRepository $activityLogRepository, EntityManagerInterface $entityManager): Response
    {
        $logs = $activityLogRepository->findAll();
        if ($this->isCsrfTokenValid('delete'.count($logs), $request->getPayload()->getString('_token'))) {
            foreach ($logs as $key => $log) $entityManager->remove($log);
            $entityManager->flush();
        }

        return $this->redirectToRoute('changes_logs', [], Response::HTTP_SEE_OTHER);
    }

}

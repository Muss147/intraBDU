<?php

namespace App\Controller\BackController;

use App\Entity\ProjectImpact;
use App\Repository\AgentsRepository;
use App\Form\ProjectImpactType;
use App\Repository\ProjectImpactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/espace-admin/projects')]
class ProjectImpactController extends AbstractController
{
    #[Route('/projects/{id?}', name: 'project_index')]
    public function form(
        Request $request,
        EntityManagerInterface $em,
        ProjectImpactRepository $repo,
        ProjectImpact $project = null
    ): Response {
        $project ??= new ProjectImpact();
        $project->setMonth((new \DateTime())->format('Y-m'));

        $form = $this->createForm(ProjectImpactType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($project);
            $em->flush();

            $this->addFlash('success', 'Projet enregistré');

            return $this->redirectToRoute('project_index');
        }

        return $this->render('back/etoile-du-mois/index.html.twig', [
            'projects' => $repo->findBy([], ['createdAt' => 'DESC']),
            'form' => $form
        ]);
    }

    #[Route('/publish/{month}', name: 'admin_project_publish')]
    public function publish(
        string $month,
        ProjectImpactRepository $repo,
        EntityManagerInterface $em
    ): Response {
        $projects = $repo->findBy([
            'month' => $month,
            'isArchived' => false
        ]);

        foreach ($projects as $project) {
            $project->setIsPublished(true);
        }

        $em->flush();

        $this->addFlash('success', 'Projets publiés pour '.$month);

        return $this->redirectToRoute('project_index');
    }

    #[Route('/close/{month}', name: 'admin_project_close')]
    public function close(
        string $month,
        ProjectImpactRepository $repo,
        EntityManagerInterface $em
    ): Response {
        $projects = $repo->findBy(['month' => $month]);

        foreach ($projects as $project) {
            $project->setIsPublished(false);
        }

        $em->flush();

        $this->addFlash('success', 'Vote clôturé pour '.$month);

        return $this->redirectToRoute('project_index');
    }
}
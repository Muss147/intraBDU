<?php

namespace App\Controller;

use App\Entity\Incidents;
use App\Form\IncidentsForm;
use App\Repository\IncidentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/incidents')]
final class IncidentsController extends AbstractController
{
    #[Route('/incidents/fiche-de-declaration', name: 'fiche_declaration')]
    public function ficheDeclaration(Request $request, IncidentsRepository $incidentsRepository): Response
    {
        $incident = new Incidents();
        $form = $this->createForm(IncidentsForm::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // 
        }
        return $this->render('front/incidents/fiche-declaration.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/', name: 'app_incidents')]
    public function index(): Response
    {
        return $this->render('incidents/index.html.twig', [
            'controller_name' => 'IncidentsController',
        ]);
    }
}

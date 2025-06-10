<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FrontController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function home(): Response
    {
        return $this->render('front/home.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    
    #[Route('/documents-de-reference', name: 'doc_reference')]
    public function documents(): Response
    {
        return $this->render('front/documents_reference.html.twig');
    }
}

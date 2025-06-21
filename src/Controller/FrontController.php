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
        return $this->render('front/documents-reference/index.html.twig');
    }

    #[Route('/documents-de-reference/prodecures', name: 'procedures')]
    public function procedures(): Response
    {
        return $this->render('front/documents-reference/procedures.html.twig');
    }

    #[Route('/documents-de-reference/autres-documents', name: 'autres_docs')]
    public function autresDocs(): Response
    {
        return $this->render('front/documents-reference/autres-doc.html.twig');
    }

    #[Route('/dispositif-d-alerte', name: 'dispositif_alerte')]
    public function dispositifAlerte(): Response
    {
        return $this->render('front/dispositif-alerte/index.html.twig');
    }

    #[Route('/foire-aux-questions', name: 'faq_alerte')]
    public function faqAlerte(): Response
    {
        return $this->render('front/dispositif-alerte/faq.html.twig');
    }

    #[Route('/fiche-de-declaration-d-incident', name: 'fiche_declaration')]
    public function ficheDeclaration(): Response
    {
        return $this->render('front/dispositif-alerte/fiche-declaration.html.twig');
    }

    #[Route('/mediatheque', name: 'app_media')]
    public function media(): Response
    {
        return $this->render('front/mediatheque.html.twig');
    }

    #[Route('/annuaire-bdu', name: 'annuaire')]
    public function annuaire(): Response
    {
        return $this->render('front/annuaire.html.twig');
    }

    #[Route('/politique-et-ethique', name: 'politique_ethique')]
    public function politiqueEthique(): Response
    {
        return $this->render('front/politique-ethique.html.twig');
    }

    #[Route('/my-faq', name: 'my_faq')]
    public function myFAQ(): Response
    {
        return $this->render('front/my-faq.html.twig');
    }
}

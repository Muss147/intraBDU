<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/le-guide-du-banquier')]
final class GuideDuBanquierController extends AbstractController
{
    #[Route('/my-faq', name: 'guide_myFaq')]
    public function myFAQ(): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Le guide du banquier', 'route' => ''],
            ['label' => 'My FAQ', 'route' => 'guide_myFaq'],
        ];

        return $this->render('front/le-guide-du-banquier/my-faq.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    #[Route('/notes-et-publications', name: 'guide_convertisseur')]
    public function guideConvertisseur(): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Le guide du banquier', 'route' => ''],
            ['label' => 'Notes et publications', 'route' => 'guide_convertisseur'],
        ];

        return $this->render('front/le-guide-du-banquier/convertisseur-devises.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    #[Route('/simulateur-de-credit', name: 'guide_simulateur')]
    public function guideSimulateur(): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Le guide du banquier', 'route' => ''],
            ['label' => 'Simulateur de crédit', 'route' => 'guide_simulateur'],
        ];

        return $this->render('front/le-guide-du-banquier/simulateur-credit.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }
}
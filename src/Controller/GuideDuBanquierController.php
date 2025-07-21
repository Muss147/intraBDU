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
        return $this->render('front/le-guide-du-banquier/my-faq.html.twig');
    }

    #[Route('/notes-et-publications', name: 'guide_convertisseur')]
    public function guideConvertisseur(): Response
    {
        return $this->render('front/le-guide-du-banquier/convertisseur-devises.html.twig');
    }

    #[Route('/simulateur-de-credit', name: 'guide_simulateur')]
    public function guideSimulateur(): Response
    {
        return $this->render('front/le-guide-du-banquier/simulateur-credit.html.twig');
    }
}
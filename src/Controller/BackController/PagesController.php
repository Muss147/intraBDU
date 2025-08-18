<?php

namespace App\Controller\BackController;

use App\Entity\Pages;
use App\Form\PagesForm;
use App\Repository\PagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-admin/pages-builder')]
final class PagesController extends AbstractController
{
    #[Route('/liste-pages', name: 'pages')]
    public function liste(PagesRepository $pagesRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'pages_builder');
        $session->set('subMenu', 'pages');

        return $this->render('back/pages/liste.html.twig', [
            'pages' => $pagesRepository->findAll(),
        ]);
    }

    #[Route('/pages/{id?}', name: 'page.new')]
    public function page(Request $request, $id, PagesRepository $pagesRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'pages_builder');
        $session->set('subMenu', 'pages');

        $page = $id ? $pagesRepository->find($id) : new Pages();
        $form = $this->createForm(PagesForm::class, $page);
        $form->handleRequest($request);

        return $this->render('back/pages/page.html.twig', [
            'form' => $form,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Repository\DocumentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/documents-de-reference')]
final class DocumentsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/', name: 'doc_reference')]
    public function documents(): Response
    {
        return $this->render('front/documents-reference/index.html.twig');
    }

    #[Route('/procedures', name: 'procedures')]
    public function procedures(DocumentsRepository $documentsRepository): Response
    {
        return $this->render('front/documents-reference/procedures.html.twig', [
            'moreViewed' => $documentsRepository->moreViewed(),
            'documents' => $documentsRepository->findByType('procedures')
        ]);
    }

    #[Route('/type-{type}', name: 'type_docs')]
    public function autresDocs($type, Request $request, PaginatorInterface $paginator, DocumentsRepository $documentsRepository): Response
    {
        $pagination = $paginator->paginate(
            $documentsRepository->findByType($type), // Doctrine query ou tableau
            $request->query->getInt('page', 1),     // page actuelle
            9                                       // éléments par page
        );

        return $this->render('front/documents-reference/autres-doc.html.twig', [
            'documents' => $pagination,
            'type' => $type
        ]);
    }

    #[Route('/politique-et-ethique', name: 'politique_ethique')]
    public function politiqueEthique(): Response
    {
        return $this->render('front/documents-reference/politique-ethique.html.twig');
    }

    #[Route('/charte-graphique', name: 'chartes')]
    public function Chartes(): Response
    {
        return $this->render('front/documents-reference/charte-graphique.html.twig');
    }
}

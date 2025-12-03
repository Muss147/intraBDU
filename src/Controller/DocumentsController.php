<?php

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Documents;
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
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Documents de reference', 'route' => 'doc_reference'],
        ];

        return $this->render('front/documents-reference/index.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    #[Route('/procedures', name: 'procedures')]
    public function procedures(DocumentsRepository $documentsRepository): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Procedures', 'route' => 'procedures'],
        ];
        
        return $this->render('front/documents-reference/procedures.html.twig', [
            'moreViewed' => $documentsRepository->moreViewed(),
            'breadcrumbs' => $breadcrumbs,
            'documents' => $documentsRepository->findByType('procedures')
        ]);
    }

    #[Route('/procedures/{id}/increment-view', name: 'documents_increment_view', methods: ['POST'])]
    public function incrementView(Documents $doc, EntityManagerInterface $em): JsonResponse
    {
        $doc->setVues($doc->getVues() + 1);
        $em->flush();

        return new JsonResponse(['success' => true, 'views' => $doc->getVues()]);
    }

    #[Route('/type-{type}', name: 'type_docs')]
    public function autresDocs($type, Request $request, PaginatorInterface $paginator, DocumentsRepository $documentsRepository): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Type '. $type, 'route' => 'type_docs'],
        ];

        $pagination = $paginator->paginate(
            $documentsRepository->findByType($type), // Doctrine query ou tableau
            $request->query->getInt('page', 1),     // page actuelle
            9                                       // éléments par page
        );

        return $this->render('front/documents-reference/autres-doc.html.twig', [
            'documents' => $pagination,
            'breadcrumbs' => $breadcrumbs,
            'type' => $type
        ]);
    }

    #[Route('/politique-et-ethique', name: 'politique_ethique')]
    public function politiqueEthique(DocumentsRepository $documentsRepository): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Politique et éthique', 'route' => 'politique_ethique'],
        ];

        return $this->render('front/documents-reference/politique-ethique.html.twig', [
            'politiques' => $documentsRepository->findByType('politiques')
        ]);
    }
}

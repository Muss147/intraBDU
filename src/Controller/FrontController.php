<?php

namespace App\Controller;

use App\Entity\Agents;
use App\Entity\NotesPublications;
use App\Repository\FilesRepository;
use App\Repository\AgentsRepository;
use App\Repository\SlidersRepository;
use App\Repository\DocumentsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NotesPublicationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FrontController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function home(
        SlidersRepository $slidersRepository,
        NotesPublicationsRepository $notesPublicationsRepository
        ): Response
    {
        return $this->render('front/home.html.twig', [
            'slides' => $slidersRepository->findAllOnline(),
            'notes' => $notesPublicationsRepository->findAllOnline(3),
        ]);
    }
    
    #[Route('/documents-de-reference', name: 'doc_reference')]
    public function documents(): Response
    {
        return $this->render('front/documents-reference/index.html.twig');
    }

    #[Route('/documents-de-reference/prodecures', name: 'procedures')]
    public function procedures(DocumentsRepository $documentsRepository): Response
    {
        return $this->render('front/documents-reference/procedures.html.twig', [
            'moreViewed' => $documentsRepository->moreViewed(),
            'documents' => $documentsRepository->findByType('procedures')
        ]);
    }

    #[Route('/documents-de-reference/autres-documents', name: 'autres_docs')]
    public function autresDocs(DocumentsRepository $documentsRepository): Response
    {
        return $this->render('front/documents-reference/autres-doc.html.twig', [
            'documents' => $documentsRepository->findByType('Autres documents')
        ]);
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

    #[Route('/mediatheque', name: 'mediatheque')]
    public function media(FilesRepository $filesRepository): Response
    {
        return $this->render('front/mediatheque.html.twig', [
            'medias' => $filesRepository->findAllBy('medias')
        ]);
    }

    #[Route('/annuaire', name: 'annuaire')]
    public function annuaire(Request $request, AgentsRepository $agentsRepository, PaginatorInterface $paginator): Response
    {
        $term = $request->request->get('research', $request->query->get('research', ''));

        $query = $agentsRepository->getFilteredQuery($term);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9 // 9 agents par page
        );

        if ($request->isXmlHttpRequest() || $request->headers->get('Turbo-Frame')) {
            return $this->render('front/annuaire/_agents.html.twig', [
                'pagination' => $pagination,
                'term' => $term
            ]);
        }
        return $this->render('front/annuaire/index.html.twig', [
            'pagination' => $pagination,
            'term' => $term
        ]);
    }

    #[Route('/annuaire/agent/{id}', name: 'agent_modal')]
    public function showAgentModal(Agents $agent): Response
    {
        return $this->render('front/annuaire/_agent_modal.html.twig', [
            'agent' => $agent,
        ]);
    }

    #[Route('/mot-du-dg', name: 'mot_du_dg')]
    public function motDuDG(): Response
    {
        return $this->render('front/mot-du-dg.html.twig');
    }

    #[Route('/politique-et-ethique', name: 'politique_ethique')]
    public function politiqueEthique(): Response
    {
        return $this->render('front/politique-ethique.html.twig');
    }

    #[Route('/notes-et-publications', name: 'notes_publications')]
    public function notesPublications(NotesPublicationsRepository $notesPublicationsRepository): Response
    {
        return $this->render('front/notes-publications/liste.html.twig', [
            'notes' => $notesPublicationsRepository->findAllOnline(),
        ]);
    }

    #[Route('/le-guide-du-banquier/my-faq', name: 'guide_myFaq')]
    public function myFAQ(): Response
    {
        return $this->render('front/le-guide-du-banquier/my-faq.html.twig');
    }

    #[Route('/le-guide-du-banquier/notes-et-publications', name: 'guide_convertisseur')]
    public function guideConvertisseur(): Response
    {
        return $this->render('front/le-guide-du-banquier/convertisseur-devises.html.twig');
    }

    #[Route('/le-guide-du-banquier/simulateur-de-credit', name: 'guide_simulateur')]
    public function guideSimulateur(): Response
    {
        return $this->render('front/le-guide-du-banquier/simulateur-credit.html.twig');
    }
}

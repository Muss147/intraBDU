<?php

namespace App\Controller;

use App\Entity\Votes;
use App\Entity\Agents;
use App\Form\VotesForm;
use App\Entity\VoteNote;
use App\Repository\FilesRepository;
use App\Repository\AgentsRepository;
use App\Repository\SlidersRepository;
use App\Repository\DocumentsRepository;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        Request $request,
        EntityManagerInterface $em,
        SlidersRepository $slidersRepository,
        ParametresRepository $criteresRepository,
        AgentsRepository $agentsRepository,
        NotesPublicationsRepository $notesPublicationsRepository
        ): Response
    {
        $criteres = $criteresRepository->findByType('criteres'); // Tous les critères
        $vote = new Votes();
        $vote->setVotedAt(new \DateTime());

        foreach ($criteres as $critere) {
            $voteNote = new VoteNote();
            $voteNote->setCritere($critere);
            $vote->addNote($voteNote);
        }

        $form = $this->createForm(VotesForm::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $votantInput = $form->get('votant')->getData();
                if (!$votant = $agentsRepository->findVotantByTerms($votantInput)) {
                    $this->addFlash('error', 'Cette information ne correspond à aucun agent dans notre base de données. Veuillez réessayer.');
                    return $this->render('front/vote/_form.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
                elseif ($votant->getVote()) {
                    $this->addFlash('error', 'Vous avez déjà effectué un vote. Veuillez attendre les résultats.');
                    return $this->render('front/vote/_form.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $vote->setVotant($votant);
                $em->persist($vote);
                $em->flush();

                $this->addFlash('success', 'Merci pour votre vote !');
                if ($request->headers->get('Turbo-Frame') === 'vote_form_frame') {
                    return $this->render('front/vote/_thanks.html.twig');
                }
                return $this->redirectToRoute('annuaire');
            }

            // Formulaire invalide : on renvoie dans le bon Turbo Frame
            if ($request->headers->get('Turbo-Frame') === 'vote_form_frame') {
                return $this->render('front/vote/_form.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('front/home.html.twig', [
            'slides' => $slidersRepository->findAllOnline(),
            'notes' => $notesPublicationsRepository->findAllOnline(3),
            'agents' => $agentsRepository->findAll(),
            'form' => $form->createView(),
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
    public function annuaire(
        Request $request,
        ParametresRepository $criteresRepository,
        EntityManagerInterface $em,
        AgentsRepository $agentsRepository,
        PaginatorInterface $paginator
    ): Response {
        //////// FEATURES DE VOTE ///////////
        $criteres = $criteresRepository->findByType('criteres'); // Tous les critères
        $vote = new Votes();
        $vote->setVotedAt(new \DateTime());

        foreach ($criteres as $critere) {
            $voteNote = new VoteNote();
            $voteNote->setCritere($critere);
            $vote->addNote($voteNote);
        }

        $form = $this->createForm(VotesForm::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $votantInput = $form->get('votant')->getData();
                if (!$votant = $agentsRepository->findVotantByTerms($votantInput)) {
                    $this->addFlash('error', 'Cette information ne correspond à aucun agent dans notre base de données. Veuillez réessayer.');
                    return $this->render('front/vote/_form.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
                elseif ($votant->getVote()) {
                    $this->addFlash('error', 'Vous avez déjà effectué un vote. Veuillez attendre les résultats.');
                    return $this->render('front/vote/_form.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }

                $vote->setVotant($votant);
                $em->persist($vote);
                $em->flush();

                $this->addFlash('success', 'Merci pour votre vote !');
                if ($request->headers->get('Turbo-Frame') === 'vote_form_frame') {
                    return $this->render('front/vote/_thanks.html.twig');
                }
                return $this->redirectToRoute('annuaire');
            }

            // Formulaire invalide : on renvoie dans le bon Turbo Frame
            if ($request->headers->get('Turbo-Frame') === 'vote_form_frame') {
                return $this->render('front/vote/_form.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        //////// FEATURES DE L'ANNUAIRE ///////////
        $term = $request->request->get('research', $request->query->get('research', ''));

        $query = $agentsRepository->getFilteredQuery($term);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        if ($request->headers->get('Turbo-Frame') === 'annuaire_results') {
            return $this->render('front/annuaire/_agents.html.twig', [
                'pagination' => $pagination,
                'term' => $term,
            ]);
        }

        return $this->render('front/annuaire/index.html.twig', [
            'pagination' => $pagination,
            'term' => $term,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/annuaire/agent/{id}', name: 'agent_modal')]
    public function showAgentModal(Agents $agent): Response
    {
        return $this->render('front/annuaire/_agent_modal.html.twig', [
            'agent' => $agent,
        ]);
    }
    
    #[Route('/vote', name: 'vote_agent')]
    public function vote(Request $request, ParametresRepository $criteresRepository, EntityManagerInterface $em): Response
    {
        $criteres = $criteresRepository->findByType('criteres'); // Tous les critères
        $vote = new Votes();
        $vote->setVotedAt(new \DateTime());

        foreach ($criteres as $critere) {
            $voteNote = new VoteNote();
            $voteNote->setCritere($critere);
            $vote->addNote($voteNote); // méthode générée automatiquement si relations bien faites
        }

        $form = $this->createForm(VotesForm::class, $vote);
        $form->handleRequest($request);

        // dd($request->headers->get('Turbo-Frame'));
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($vote);
            $em->flush();

            $this->addFlash('success', 'Merci pour votre vote !');

            // Pour Turbo, on renvoie un fragment (Turbo Frame)
            return $this->render('front/vote/_thanks.html.twig');
        }

        return $this->render('front/vote/index.html.twig', [
            'form' => $form->createView(),
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

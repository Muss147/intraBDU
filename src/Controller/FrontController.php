<?php

namespace App\Controller;

use App\Entity\Files;
use App\Entity\Votes;
use App\Entity\Agents;
use App\Entity\Alertes;
use App\Form\VotesForm;
use App\Entity\VoteNote;
use App\Form\AlertesForm;
use App\Entity\Actualites;
use App\Service\FileUploader;
use Symfony\Component\Mime\Email;
use App\Repository\FilesRepository;
use App\Repository\PagesRepository;
use App\Repository\AgentsRepository;
use App\Repository\SlidersRepository;
use App\Repository\ActualitesRepository;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ClassementMensuelRepository;
use App\Repository\NotesPublicationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class FrontController extends AbstractController
{
    public function header(PagesRepository $pagesRepository): Response
    {
        return $this->render('front/_header.html.twig', [
            'headerPages' => $pagesRepository->findAllSimply(),
        ]);
    }

    #[Route('/', name: 'app_homepage')]
    public function home(
        Request $request,
        EntityManagerInterface $em,
        SlidersRepository $slidersRepository,
        ParametresRepository $criteresRepository,
        AgentsRepository $agentsRepository,
        ClassementMensuelRepository $classementMensuelRepository,
        NotesPublicationsRepository $notesPublicationsRepository,
        ActualitesRepository $actualitesRepository,
        PagesRepository $pagesRepository
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
        
        if ($form->isSubmitted() && $form->isValid()) {
            $votantInput = $form->get('votant')->getData();
            $votant = $agentsRepository->findVotantByTerms($votantInput);

            if (!$votant) {
                $this->addFlash('error', 'Ce matricule ne correspond à aucun agent dans notre base de données. Veuillez réessayer.');
            } else {
                // Vérifie s’il a déjà voté ce mois-ci
                $now = new \DateTime();
                $hasVotedThisMonth = $votant->getVotesEffectues()->exists(function ($key, $vote) use ($now) {
                    return $vote->getVotedAt()->format('Y-m') === $now->format('Y-m');
                });

                if ($hasVotedThisMonth) {
                    $this->addFlash('error', 'Vous avez déjà effectué un vote ce mois-ci. Veuillez attendre le mois prochain.');
                } else {
                    $vote->setVotant($votant);
                    $em->persist($vote);
                    $em->flush();

                    $this->addFlash('success', 'Merci pour votre vote !');
                }
            }
        }
        
        return $this->render('front/home.html.twig', [
            'slides' => $slidersRepository->findAllOnline(),
            'notes' => $notesPublicationsRepository->findAllOnline(3),
            'actualites' => $actualitesRepository->findAllOnline(2),
            'agents' => $agentsRepository->findAll(),
            'anniversaires' => $agentsRepository->findAnniversairesDuMois(),
            'akwaba' => $agentsRepository->findAkwabaDuMois(),
            'form' => $form->createView(),
            'pages' => $pagesRepository->findAllSimply(),
            'top' => $classementMensuelRepository->findOneByMois(new \DateTime('first day of this month'))
        ]);
    }
    
    #[Route('/page-{emplacement}/{slug}', name: 'page')]
    public function displayPage($emplacement, $slug, PagesRepository $pagesRepository): Response
    {
        if (!$slug || !$page = $pagesRepository->findOneBySlug($slug)) throw $this->createNotFoundException('Ressource non trouvée.');
        $page->setContenu(htmlspecialchars_decode($page->getContenu()));

        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Page '. ucfirst($emplacement), 'route' => ''],
            ['label' => $page->getTitre(), 'route' => ''],
        ];

        return $this->render('front/page_builder.html.twig', [
            'page' => $page,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    
    #[Route('/dispositif-d-alerte', name: 'dispositif_alerte')]
    public function dispositifAlerte(): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Dispositif d\'alerte', 'route' => 'dispositif_alerte'],
        ];

        return $this->render('front/dispositif-alerte/index.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    #[Route('/dispositif-d-alerte/deposer-une-alerte', name: 'signaler_alerte')]
    public function signalerAlerte(Request $request, EntityManagerInterface $em, MailerInterface $mailer, FileUploader $fileUploader): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Dispositif d\'alerte', 'route' => 'dispositif_alerte'],
            ['label' => 'Deposer une alerte', 'route' => 'signaler_alerte'],
        ];

        $alerte = new Alertes();
        $code = 'ALRT-' . strtoupper(bin2hex(random_bytes(3))); // ex : ALRT-8F3A2B
        $alerte->setCode($code)->setCreatedAt(new \DateTime());

        $form = $this->createForm(AlertesForm::class, $alerte);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            else {
                $files = $form->get('fichiers')->getData();
                if ($files) {
                    foreach ($files as $file) {
                        $data = $fileUploader->upload($file);

                        $media = new Files();
                        $media
                            ->setFilename($data['filename'])
                            ->setType('medias')
                            ->setSize($data['size'])
                            ->setAlt($data['originalName']);
                        $em->persist($media);
                        $alerte->addFichier($media);
                    }
                }
                $em->persist($alerte);
                $em->flush();

                // Envoi de mail avec try/catch
                if ($form->get('email')->getData()) {
                    try {
                        // Email au rapproteur
                        $emailCandidat = (new Email())
                            ->from('no-reply@bduci.com')
                            ->to($alerte->getEmail())
                            ->subject('Détails de votre signalement d\incident')
                            ->html("
                                <p>Bonjour <b>{$alerte->getPrenoms()}</b>,</p>
                                <p>Nous avons bien reçu votre signalement à l'alerte <b>N°{$alerte->getCode()}</b>.</p>
                                <p>Notre équipe la traitera dans les plus brefs délais.</p>
                                <p>Merci pour la confiance !</p>
                                <hr>
                                <p>Service Incidents - BDU</p>
                            ");
                        $mailer->send($emailCandidat);
                    } catch (TransportExceptionInterface $e) {
                        $this->addFlash('error', "Une erreur s'est produite lors de l'envoi de l'email au rapporteur : " . $e->getMessage());
                    }
                }

                try {
                    // Email au RH
                    $emailRh = (new Email())
                        ->from('no-reply@bduci.com')
                        ->to('support@bduci.com')
                        ->subject("Nouvelle alerte : {$alerte->getCode()}")
                        ->html("
                            <p><strong>{$alerte->getPrenoms()}</strong> a signalé l'alerte <strong>{$alerte->getCode()}</strong>.</p>
                            <p>Catégorie : {$alerte->getCategorie()}<br>
                            <p>Email : {$alerte->getEmail()}<br>
                            Téléphone : {$alerte->getTelephone()}<br>
                            <p>Voir dans l'espace d'administration.</p>
                        ");
                    $mailer->send($emailRh);
                } catch (TransportExceptionInterface $e) {
                    $this->addFlash('error', "Erreur lors de la notification au service RH : " . $e->getMessage());
                }
        
                $this->addFlash('success', 'Le formulaire a été enregistrée avec succès. Nous vous enverrons un email sur les détails de votre signalement.');
                return $this->redirectToRoute('signaler_alerte');
            }
        }
        return $this->render('front/dispositif-alerte/signaler-alerte.html.twig', [
            'form' => $form,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    #[Route('/dispositif-d-alerte/foire-aux-questions', name: 'faq_alerte')]
    public function faqAlerte(): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Dispositif d\'alerte', 'route' => 'dispositif_alerte'],
            ['label' => 'Foire aux questions', 'route' => ''],
        ];

        return $this->render('front/dispositif-alerte/faq.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    #[Route('/mediatheque', name: 'mediatheque')]
    public function media(Request $request, FilesRepository $filesRepository, PaginatorInterface $paginator): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Médiatheque', 'route' => 'mediatheque'],
        ];

        $pagination = $paginator->paginate(
            $filesRepository->findAllBy('medias'), // Doctrine query ou tableau
            $request->query->getInt('page', 1),     // page actuelle
            12                                       // éléments par page
        );

        return $this->render('front/mediatheque.html.twig', [
            'medias' => $pagination,
            'breadcrumbs' => $breadcrumbs,
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

        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Annuaire', 'route' => 'annuaire'],
        ];

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
        
        if ($form->isSubmitted() && $form->isValid()) {
            $votantInput = $form->get('votant')->getData();
            $votant = $agentsRepository->findVotantByTerms($votantInput);

            if (!$votant) {
                $this->addFlash('error', 'Ce matricule ne correspond à aucun agent dans notre base de données. Veuillez réessayer.');
            } else {
                // Vérifie s’il a déjà voté ce mois-ci
                $now = new \DateTime();
                $hasVotedThisMonth = $votant->getVotesEffectues()->exists(function ($key, $vote) use ($now) {
                    return $vote->getVotedAt()->format('Y-m') === $now->format('Y-m');
                });

                if ($hasVotedThisMonth) {
                    $this->addFlash('error', 'Vous avez déjà effectué un vote ce mois-ci. Veuillez attendre le mois prochain.');
                } else {
                    $vote->setVotant($votant);
                    $em->persist($vote);
                    $em->flush();

                    $this->addFlash('success', 'Merci pour votre vote !');
                }
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
                'breadcrumbs' => $breadcrumbs,
                'term' => $term,
            ]);
        }

        return $this->render('front/annuaire/index.html.twig', [
            'pagination' => $pagination,
            'breadcrumbs' => $breadcrumbs,
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
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Mot du DG', 'route' => 'mot_du_dg'],
        ];

        return $this->render('front/mot-du-dg.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    #[Route('/notes-et-publications', name: 'notes_publications')]
    public function notesPublications(Request $request, NotesPublicationsRepository $notesPublicationsRepository, PaginatorInterface $paginator): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Notes et publications', 'route' => 'notes_publications'],
        ];

        $pagination = $paginator->paginate(
            $notesPublicationsRepository->findAllOnline(), // Doctrine query ou tableau
            $request->query->getInt('page', 1),     // page actuelle
            6                                       // éléments par page
        );

        return $this->render('front/notes-publications/liste.html.twig', [
            'notes' => $pagination,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    #[Route('/actualites', name: 'actualites')]
    public function actualites(Request $request, ActualitesRepository $actualitesRepository, PaginatorInterface $paginator): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Actualités', 'route' => 'actualites'],
        ];

        $pagination = $paginator->paginate(
            $actualitesRepository->findAllOnline(), // Doctrine query ou tableau
            $request->query->getInt('page', 1),     // page actuelle
            6                                       // éléments par page
        );

        return $this->render('front/actualites/liste.html.twig', [
            'actualites' => $pagination,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    #[Route('/actualites/{slug}', name: 'actualite.details')]
    public function detailsActualite(Request $request, $slug, ActualitesRepository $actualitesRepository): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Actualités', 'route' => 'actualites'],
            ['label' => $slug, 'route' => ''],
        ];

        return $this->render('front/actualites/details.html.twig', [
            'actu' => $actualitesRepository->findOneBySlug($slug),
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    #[Route('/politique-de-confidentialite', name: 'politique_de_conf')]
    public function politiqueDeConf(): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Politique de confidentialite', 'route' => 'politique_de_conf'],
        ];

        return $this->render('front/politique-de-conf.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    #[Route('/organigramme', name: 'organigramme')]
    public function organigramme(): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Organigramme', 'route' => 'organigramme'],
        ];

        return $this->render('front/organigramme.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    #[Route('/mon-livret-d-acceuil', name: 'livret.acceuil')]
    public function livretAcceuil(): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Mon livret d\'acceuil', 'route' => ''],
        ];

        return $this->render('front/livret-acceuil.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    #[Route('/charte-graphique', name: 'charte_graphique')]
    public function charteGraphique(FilesRepository $charteRepository): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Chartes graphiques', 'route' => ''],
        ];

        return $this->render('front/documents-reference/charte-graphique.html.twig', [
            'breadcrumbs' => $breadcrumbs,
            'chartes' => $charteRepository->findAllBy('Chartes'),
        ]);
    }
}

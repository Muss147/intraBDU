<?php

namespace App\Controller;

use App\Entity\Files;
use App\Service\FileUploader;
use App\Entity\Votes;
use App\Entity\Agents;
use App\Entity\Alertes;
use App\Form\VotesForm;
use App\Entity\VoteNote;
use App\Form\AlertesForm;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use App\Repository\FilesRepository;
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

final class FrontController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function home(
        Request $request,
        EntityManagerInterface $em,
        SlidersRepository $slidersRepository,
        ParametresRepository $criteresRepository,
        AgentsRepository $agentsRepository,
        ClassementMensuelRepository $classementMensuelRepository,
        NotesPublicationsRepository $notesPublicationsRepository,
        ActualitesRepository $actualitesRepository
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
            'actualites' => $actualitesRepository->findAllOnline(2),
            'agents' => $agentsRepository->findAll(),
            'anniversaires' => $agentsRepository->findAnniversairesDuMois(),
            'akwaba' => $agentsRepository->findAkwabaDuMois(),
            'form' => $form->createView(),
            'top' => $classementMensuelRepository->findOneByMois(new \DateTime('first day of this month'))
        ]);
    }
    
    #[Route('/dispositif-d-alerte', name: 'dispositif_alerte')]
    public function dispositifAlerte(): Response
    {
        return $this->render('front/dispositif-alerte/index.html.twig');
    }

    #[Route('/dispositif-d-alerte/deposer-une-alerte', name: 'signaler_alerte')]
    public function signalerAlerte(Request $request, EntityManagerInterface $em, MailerInterface $mailer, FileUploader $fileUploader): Response
    {
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
            'form' => $form
        ]);
    }

    #[Route('/dispositif-d-alerte/foire-aux-questions', name: 'faq_alerte')]
    public function faqAlerte(): Response
    {
        return $this->render('front/dispositif-alerte/faq.html.twig');
    }

    #[Route('/mediatheque', name: 'mediatheque')]
    public function media(Request $request, FilesRepository $filesRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $filesRepository->findAllBy('medias'), // Doctrine query ou tableau
            $request->query->getInt('page', 1),     // page actuelle
            12                                       // éléments par page
        );

        return $this->render('front/mediatheque.html.twig', [
            'medias' => $pagination
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

    #[Route('/notes-et-publications', name: 'notes_publications')]
    public function notesPublications(Request $request, NotesPublicationsRepository $notesPublicationsRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $notesPublicationsRepository->findAllOnline(), // Doctrine query ou tableau
            $request->query->getInt('page', 1),     // page actuelle
            6                                       // éléments par page
        );

        return $this->render('front/notes-publications/liste.html.twig', [
            'notes' => $pagination,
        ]);
    }

    #[Route('/actualites', name: 'actualites')]
    public function actualites(Request $request, ActualitesRepository $actualitesRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $actualitesRepository->findAllOnline(), // Doctrine query ou tableau
            $request->query->getInt('page', 1),     // page actuelle
            6                                       // éléments par page
        );

        return $this->render('front/notes-publications/actualites.html.twig', [
            'actualites' => $pagination,
        ]);
    }

    #[Route('/politique-de-confidentialite', name: 'politique_de_conf')]
    public function politiqueDeConf(): Response
    {
        return $this->render('front/politique-de-conf.html.twig');
    }

    #[Route('/organigramme', name: 'organigramme')]
    public function organigramme(): Response
    {
        return $this->render('front/organigramme.html.twig');
    }
}

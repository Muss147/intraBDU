<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\Files;
use App\Entity\Postuler;
use App\Form\PostulerForm;
use App\Entity\OffresEmploi;
use App\Repository\PostulerRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffresEmploiRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/offres-emploi')]
final class OffresController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/', name: 'app_offres')]
    public function index(Request $request, OffresEmploiRepository $offresEmploiRepository, ParametresRepository $parametresRepository, PaginatorInterface $paginator): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'offres-emploi', 'route' => 'app_offres'],
        ];

        $search = [
            'keywords' => $request->request->get('keywords', $request->query->get('keywords', '')),
            'lieu' => $request->request->get('lieu', $request->query->get('lieu', '')),
        ];

        $filters = [
            'metier' => $request->request->get('metier', $request->query->get('metier', '')),
            'experience' => $request->request->get('experience', $request->query->get('experience', '')),
            'niveau_poste' => $request->request->get('niveau_poste', $request->query->get('niveau_poste', '')),
            'secteur' => $request->request->get('secteur', $request->query->get('secteur', '')),
            'niveau_formation' => $request->request->get('niveau_formation', $request->query->get('niveau_formation', '')),
        ];

        $query = $offresEmploiRepository->getFilteredQuery($search, $filters);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        if ($request->headers->get('Turbo-Frame') === 'offres_results') {
            return $this->render('front/offres-emploi/_offres.html.twig', [
                'pagination' => $pagination,
                'search' => $search,
                'filters' => $filters,
            ]);
        }
        return $this->render('front/offres-emploi/index.html.twig', [
            'offres' => $offresEmploiRepository->findAll(),
            'metiers' => $parametresRepository->findByType('metiers'),
            'secteurs' => $parametresRepository->findByType('secteurs'),
            'pagination' => $pagination,
            'breadcrumbs' => $breadcrumbs,
            'search' => $search,
            'filters' => $filters,
        ]);
    }

    #[Route('/details/{offre}', name: 'details_offre')]
    public function details(Request $request,#[MapEntity(expr: "repository.findOneBySlug(offre)")] OffresEmploi $offre, PostulerRepository $postulerRepository, MailerInterface $mailer): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'offres-emploi', 'route' => 'app_offres'],
            ['label' => $offre->getTitre(), 'route' => 'details_offre'],
        ];

        $url = $request->getUri();
        $post = new Postuler();
        $post->setOffre($offre)->setCreatedAt(new \DateTime());

        $form = $this->createForm(PostulerForm::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                // ✅ Redirection même si invalide
                return $this->redirectToRoute('details_offre', [
                    'offre' => $offre->getSlug(),
                ]);
            }
            
            if ($exist = $postulerRepository->findExistingWirer(
                $form->get('nomAgent')->getData(),
                $form->get('email')->getData(),
                $offre->getId()
            )) {
                $this->addFlash('error', 'Vous avez déjà postulé à cette offre. Merci de consulter vos mails pour le suivi de la condidature.');
                // ✅ Redirection même si doublon
                return $this->redirectToRoute('details_offre', [
                    'offre' => $offre->getSlug(),
                ]);
            }

            // Gestion de l'upload des fichiers
            if ($file = $form->get('cv')->getData()) {
                $data = $this->fileUploader->upload($file);
                $fileEntity = (new Files())
                    ->setFilename($data['filename'])
                    ->setType($data['type'])
                    ->setSize($data['size'])
                    ->setAlt('resume_'. $form->get('nomAgent')->getData());
                $post->setCv($fileEntity);
                $this->em->persist($fileEntity);
            }
            $this->em->persist($post);
            $this->em->flush();

            // Envoi de mail avec try/catch
            try {
                // Email au candidat
                $emailCandidat = (new Email())
                    ->from('no-reply@bduci.com')
                    ->to($post->getEmail())
                    ->subject('Confirmation de votre candidature')
                    ->html("
                        <p>Bonjour <b>{$post->getNomAgent()}</b>,</p>
                        <p>Nous avons bien reçu votre candidature à l'offre <b>{$offre->getTitre()}</b>.</p>
                        <p>Notre équipe RH la traitera dans les plus brefs délais.</p>
                        <p>Merci et bonne chance !</p>
                        <hr>
                        <p>Service Recrutement - BDU</p>
                    ");
                $mailer->send($emailCandidat);
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('error', "Une erreur s'est produite lors de l'envoi de l'email au candidat : " . $e->getMessage());
            }

            try {
                // Email au RH
                $emailRh = (new Email())
                    ->from('no-reply@bduci.com')
                    ->to('rh@bduci.com')
                    ->subject("Nouvelle candidature : {$offre->getTitre()}")
                    ->html("
                        <p><strong>{$post->getNomAgent()}</strong> a postulé à l'offre <strong>{$offre->getTitre()}</strong>.</p>
                        <p>Email : {$post->getEmail()}<br>
                        Téléphone : {$post->getTelephone()}<br>
                        Poste : {$post->getPosteOccupe()}</p>
                        <p>Voir dans l'espace d'administration.</p>
                    ");
                $mailer->send($emailRh);
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('error', "Erreur lors de la notification au service RH : " . $e->getMessage());
            }
    
            $this->addFlash('success', 'Votre candidature a été enregistrée avec succès. Un mail vous sera envoyé pour le suivi de la candidature.');
            return $this->redirectToRoute('details_offre', [
                'offre' => $offre->getSlug(),
                'form' => $form,
                'currentUrl' => $url,
            ]);
        }
        return $this->render('front/offres-emploi/details.html.twig', [
            'offre' => $offre,
            'form' => $form,
            'currentUrl' => $url,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}

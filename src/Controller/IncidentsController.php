<?php

namespace App\Controller;

use App\Entity\Files;
use App\Service\FileUploader;
use App\Entity\Incidents;
use App\Form\IncidentsForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use App\Repository\IncidentsParamsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/incidents')]
final class IncidentsController extends AbstractController
{
    #[Route('/incidents/fiche-de-declaration', name: 'fiche_declaration')]
    public function ficheDeclaration(Request $request, IncidentsParamsRepository $paramsRepository, EntityManagerInterface $em, MailerInterface $mailer, FileUploader $fileUploader): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Incidents', 'route' => ''],
            ['label' => 'Fiche de déclaration', 'route' => 'fiche_declaration'],
        ];

        $incident = new Incidents();
        // Initialisation propre de 3 lignes de solutions vides
        $solutions = array_fill(0, 3, [
            'action' => '',
            'responsable' => '',
            'delai' => '',
        ]);

        $incident->setSolutions($solutions)->setCreatedAt(new \DateTime());
        $form = $this->createForm(IncidentsForm::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            else {
                $files = $form->get('pieceJointe')->getData();
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
                        $incident->addPieceJointe($media);
                    }
                }
                $em->persist($incident);
                $em->flush();

                // Envoi de mail avec try/catch
                if ($incident->getEmail()) {
                    try {
                        // Email au rapproteur
                        $emailCandidat = (new Email())
                            ->from('no-reply@bduci.com')
                            ->to($incident->getEmail())
                            ->subject('Détails de votre déclaration d\incident')
                            ->html("
                                <p>Bonjour <b>{$incident->getRapporteur()}</b>,</p>
                                <p>Nous avons bien reçu votre déclaration à l'incident <b>N°{$incident->getCode()}</b>.</p>
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
                        ->subject("Nouvelle incident : {$incident->getCode()}")
                        ->html("
                            <p><strong>{$incident->getRapporteur()}</strong> a déclaré l'incident <strong>{$incident->getCode()}</strong>.</p>
                            <p>Email : {$incident->getEmail()}<br>
                            Téléphone : {$incident->getTelephone()}<br>
                            Étape : {$incident->getEtape()}</p>
                            <p>Voir dans l'espace d'administration.</p>
                        ");
                    $mailer->send($emailRh);
                } catch (TransportExceptionInterface $e) {
                    $this->addFlash('error', "Erreur lors de la notification au service RH : " . $e->getMessage());
                }
        
                $this->addFlash('success', 'Le formulaire a été enregistrée avec succès. Nous vous enverrons un email sur les détails de votre déclaration.');
                return $this->redirectToRoute('fiche_declaration');
            }
        }
        return $this->render('front/incidents/fiche-declaration.html.twig', [
            'form' => $form,
            'breadcrumbs' => $breadcrumbs,
            'categories' => $paramsRepository->findByType('categories'),
            'sous-categories' => $paramsRepository->findByType('sous-categories'),
            'processus' => $paramsRepository->findByType('processus'),
            'sous-processus' => $paramsRepository->findByType('sous-processus'),
        ]);
    }

    #[Route('/', name: 'app_incidents')]
    public function index(): Response
    {
        $breadcrumbs = [
            ['label' => 'Accueil', 'route' => 'app_homepage'],
            ['label' => 'Incidents', 'route' => ''],
            ['label' => 'Fiche de déclaration', 'route' => 'app_incidents'],
        ];

        return $this->render('incidents/index.html.twig', [
            'controller_name' => 'IncidentsController',
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}

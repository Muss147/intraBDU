<?php

namespace App\Controller\BackController;

use App\Entity\Postuler;
use App\Form\OffresForm;
use App\Entity\OffresEmploi;
use Symfony\Component\Mime\Email;
use App\Repository\PostulerRepository;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffresEmploiRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[Route('/espace-admin/offres')]
final class OffresController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private EntityManagerInterface $em
    ) {}

    #[Route('/', name: 'list_offres')]
    public function index(OffresEmploiRepository $offresEmploiRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'offres');
        $session->set('subMenu', 'liste');

        return $this->render('back/offres/liste.html.twig', [
            'offres' => $offresEmploiRepository->findAll(),
        ]);
    }

    #[Route('/details/{offre?}', name: 'add_offre')]
    public function details(Request $request, $offre, OffresEmploiRepository $offresEmploiRepository, ParametresRepository $parametresRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'offres');
        $session->set('subMenu', 'liste');

        if (!$offre || !$offre = $offresEmploiRepository->find($offre)) $offre = new OffresEmploi();
        if (count($offre->getMissions()) === 0) $offre->setMissions(['']);
        if (count($offre->getProfils()) === 0) $offre->setProfils(['']);

        $form = $this->createForm(OffresForm::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$offre->getSlug()) {
                $offre->generateSlug();
            }

            $offre->updatedTimestamps();
            $offre->updatedUserstamps($this->getUser());

            $this->em->persist($offre);
            $this->em->flush();

            $this->addFlash('success', 'Offre d\'emploi ajouté(e) avec succès.');
            return $this->redirectToRoute('list_offres');
        }
        return $this->render('back/offres/add.html.twig', [
            'offre' => $offre,
            'form_offre' => $form,
            'metiers' => $parametresRepository->findByType('metiers'),
            'agences' => $parametresRepository->findByType('agences'),
            'directions' => $parametresRepository->findByType('directions'),
        ]);
    }

    #[Route('/candidatures/{offre}', name: 'candidatures_offres')]
    public function candidatures(Request $request, OffresEmploi $offre): Response
    {
        return $this->render('back/offres/candidatures.html.twig', [
            'agents' => $offre->getPostulants(),
            'offre' => $offre
        ]);
    }

    #[Route('/candidatures/{candidature}/action', name: 'candidature_action')]
    public function candidatureActions(Request $request, Postuler $candidature, MailerInterface $mailer): Response
    {
        $offre = $candidature->getOffre();
        if ($request->isMethod('POST')) {
            $isValid = $request->get('validated') === 'true';
            $candidature->setValidated($isValid);

            $this->em->persist($candidature);
            $this->em->flush();
                
            // Envoi du mail au candidat
            try {
                $subject = $isValid
                    ? "Votre candidature à l'offre « {$offre->getTitre()} » a été retenue"
                    : "Suite à votre candidature à l'offre « {$offre->getTitre()} »";

                $message = $isValid
                    ? "<p>Bonjour <strong>{$candidature->getNomAgent()}</strong>,</p>
                    <p>Votre candidature a été retenue pour l'offre <strong>{$offre->getTitre()}</strong>.</p>
                    <p>Nous prendrons contact avec vous prochainement pour un entretien.</p>"
                    : "<p>Bonjour <strong>{$candidature->getNomAgent()}</strong>,</p>
                    <p>Après étude de votre candidature pour le poste <strong>{$offre->getTitre()}</strong>, nous sommes au regret de ne pas pouvoir y donner suite.</p>
                    <p>Nous vous remercions pour l’intérêt porté à notre entreprise.</p>";

                $email = (new Email())
                    ->from('no-reply@bduci.com')
                    ->to($candidature->getEmail())
                    ->subject($subject)
                    ->html($message . "<hr><p>BDU - Service Recrutement</p>");

                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('error', "Une erreur s'est produite lors de l'envoi de l'email au candidat : " . $e->getMessage());
            }

            $this->addFlash('success', 'Requête effectuée avec succès. Un mail de notification sera envoyé au candidat.');
            return $this->redirectToRoute('candidatures_offres', ['offre' => $offre->getId()]);
        }
        return $this->render('back/offres/candidatures.html.twig', [
            'agents' => $offre->getPostulants(),
            'offre' => $offre
        ]);
    }

    #[Route('/{offre}/delete', name: 'offre_delete', methods: ['POST'])]
    public function delete(Request $request, OffresEmploi $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->getPayload()->getString('_token'))) {
            $offre->remove($this->getUser());
            $this->em->flush();
        }
        return $this->redirectToRoute('list_offres', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete-offres-selected', name: 'offres_selected_delete', methods: ['POST'])]
    public function deleteOffresSelected(Request $request, OffresEmploiRepository $offresRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['offresDeleted'] as $id) {
                if ($offre = $offresRepository->find($id)) $offre->remove($this->getUser());
                $this->em->flush();
            }
        }
        return new JsonResponse(true, 200);
    }
}

<?php

namespace App\Controller\BackController;

use App\Entity\Files;
use App\Entity\Agents;
use App\Form\AgentsForm;
use App\Service\FileUploader;
use App\Repository\AgentsRepository;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/agents')]
final class AgentsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/', name: 'app_agents')]
    public function index(Request $request, AgentsRepository $agentsRepository, ParametresRepository $parametresRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'agents');
        $session->set('subMenu', null);

        $agent = new Agents();
        $form = $this->createForm(AgentsForm::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if(!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            else {
                // Gestion de l'upload des fichiers
                if ($file = $form->get('photo')->getData()) {
                    $data = $this->fileUploader->upload($file);
                    $fileEntity = (new Files())
                        ->setFilename($data['filename'])
                        ->setType($data['type'])
                        ->setSize($data['size'])
                        ->setAlt($agent->getNom());
                    $agent->setPhoto($fileEntity);
                    $this->em->persist($fileEntity);
                }
                $agent->updatedTimestamps();
                $agent->updatedUserstamps($this->getUser());

                $this->em->persist($agent);
                $this->em->flush();

                // if ($request->get('welcomeMail') && $request->get('welcomeMail') != !0)
        
                $this->addFlash('success', 'Agent ajouté avec succès.');
                return $this->redirectToRoute('app_agents');
            }
        }
        return $this->render('back/agents/list.html.twig', [
            'form' => $form,
            'agents' => $agentsRepository->findAll(),
            'directions' => $parametresRepository->findByType('directions'),
            'services' => $parametresRepository->findByType('services'),
        ]);
    }

    #[Route('/details/{agent}/', name: 'details_agents')]
    public function details(Request $request, Agents $agent, AgentsRepository $agentsRepository, ParametresRepository $parametresRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'agents');

        $form = $this->createForm(AgentsForm::class, $agent);
        $form->handleRequest($request);

        // Classement
        $classement = $agentsRepository->getClassement();
        $rang = count($agentsRepository->findAll()); // Par défaut, rang = nombre total d'agents
        $index = 1;

        foreach ($classement as $entry) {
            if ($entry['agent']->getId() === $agent->getId()) {
                $rang = $index;
                break; // Dès qu'on trouve l'agent, on arrête
            }
            $index++;
        }

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
            else {
                $service = $request->get('service');
                if ($service) $agent->setService($parametresRepository->find($request->get('service')));

                // Gestion de l'upload des fichiers
                if ($file = $form->get('photo')->getData()) {
                    $data = $this->fileUploader->upload($file);
                    $fileEntity = (new Files())
                        ->setFilename($data['filename'])
                        ->setType($data['type'])
                        ->setSize($data['size'])
                        ->setAlt($agent->getNom());
                    $agent->setPhoto($fileEntity);
                    $this->em->persist($fileEntity);
                }
                $agent->updatedTimestamps();
                $agent->updatedUserstamps($this->getUser());

                $this->em->persist($agent);
                $this->em->flush();

                // if ($request->get('welcomeMail') && $request->get('welcomeMail') != !0)
        
                $this->addFlash('success', 'Agent modifié avec succès.');
                return $this->redirectToRoute('details_agents', ['agent' => $agent->getId()]);
            }
        }
        return $this->render('back/agents/details.html.twig', [
            'form' => $form,
            'agent' => $agent,
            'rang' => $rang,
            'historique' => $agent->getHistoriqueMensuel($this->em),
            'agences' => $parametresRepository->findByType('agences'),
            'directions' => $parametresRepository->findByType('directions'),
            'services' => $parametresRepository->findByType('services'),
        ]);
    }

    #[Route('/delete/{agent}', name: 'agent_delete', methods: ['POST'])]
    public function deleteCategorie(Request $request, Agents $agent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agent->getId(), $request->getPayload()->getString('_token'))) {
            $agent->remove($this->getUser());
            $this->em->flush();
        }
        $this->addFlash('success', 'Suppression effectuée avec succès.');
        return $this->redirectToRoute('app_agents');
    }

    #[Route('/delete-agents-selected', name: 'agents_selected_delete', methods: ['POST'])]
    public function deleteUsersSelected(Request $request, AgentsRepository $agentsRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['agentsToDelete'] as $id) {
                if ($agent = $agentsRepository->find($id)) $agent->remove($this->getUser());
                $this->em->flush();
            }
        }
        return new JsonResponse(true, 200);
    }
}

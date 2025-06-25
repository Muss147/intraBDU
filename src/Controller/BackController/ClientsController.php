<?php

namespace App\Controller\BackController;

use App\Entity\Clients;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/clients')]
final class ClientsController extends AbstractController
{
    #[Route('/', name: 'liste_clients')]
    public function index(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $session->set('menu', 'clients');
        $session->set('subMenu', '');
        $clients = $entityManager->getRepository(Clients::class)->findAll();

        $client = new Clients();
        $formClient = $this->createForm(ClientsType::class, $client);
        $formClient->handleRequest($request);

        if ($formClient->isSubmitted() && $formClient->isValid()) {

            $client->updatedTimestamps();
            $client->updatedUserstamps($this->getUser());

            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client ajouté avec succès.');
            return $this->redirectToRoute('liste_clients', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('clients/liste.html.twig', [
            'form_client' => $formClient,
            'clients' => $clients,
        ]);
    }

    #[Route('/{client}/details', name: 'detail_client')]
    public function view(Request $request, Clients $client, EntityManagerInterface $entityManager, SessionInterface $session, ClientsRepository $clientsRepository): Response
    {
        $session->set('menu', 'clients');
        $session->set('subMenu', '');

        $edit_client = $this->createForm(ClientsType::class, $client);
        $edit_client->handleRequest($request);

        if ($edit_client->isSubmitted() && $edit_client->isValid()) {
            
            $client->updatedTimestamps();
            $client->updatedUserstamps($this->getUser());
            
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Modification de <b>'. $client->getNom() .'</b> effectué avec succès.');
            return $this->redirectToRoute('detail_client', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('clients/details.html.twig', [
            'client' => $client,
            'edit_client' => $edit_client,
        ]);
    }

    #[Route('/{id}/delete', name: 'clients_delete', methods: ['POST'])]
    public function deleteClient(Request $request, Clients $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->getPayload()->getString('_token'))) {
            $client->remove($this->getUser());
            $entityManager->flush();
        }

        return $this->redirectToRoute('clients', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete-selection', name: 'clients_delete_select', methods: ['POST'])]
    public function delete_select(Request $request, EntityManagerInterface $entityManager, ClientsRepository $clientsRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['clientsDeleted'] as $id) {
                if ($client = $clientsRepository->find($id)) $client->remove($this->getUser());
                $entityManager->flush();
            }
        }
        return new JsonResponse($data, 200);
    }
}

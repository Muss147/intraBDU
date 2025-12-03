<?php

namespace App\Controller\BackController;

use App\Entity\Actions;
use App\Entity\Permissions;
use App\Form\PermissionsType;
use App\Repository\PermissionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/espace-admin/permissions')]
final class PermissionsController extends AbstractController
{
    #[Route('/', name: 'permissions')]
    public function list(Request $request, SessionInterface $session, PermissionsRepository $permissionsRepository, EntityManagerInterface $entityManager): Response
    {
        $session->set('menu', 'users_manage');
        $session->set('subMenu', 'permissions');
        
        $permission = new Permissions();
        $form = $this->createForm(PermissionsType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $permission->generateSlug();
            $permission->updatedTimestamps();
            $permission->updatedUserstamps($this->getUser());

            $entityManager->persist($permission);
            $entityManager->flush();

            $this->addFlash('success', 'Permission ajouté avec succès.');
            return $this->redirectToRoute('permissions', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('back/roles/permissions.html.twig', [
            'new_permission' => $form,
            'permissions' => $permissionsRepository->findAll()
        ]);
    }

    #[Route('/modification', name: 'edit_permissions')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();
        if (($request->isMethod('POST')) && $permission = $entityManager->getRepository(Permissions::class)->find($data['permission_id'])) {

            // Nettoyer les anciennes actions (optionnel selon le besoin)
            foreach ($permission->getActions() as $existingAction) {
                $permission->removeAction($existingAction);
                $entityManager->remove($existingAction);
            }

            foreach ($data['actions'] as $key => $value) {
                $action = !empty($value['id']) ? $entityManager->getRepository(Actions::class)->find($value['id']) : new Actions();
                $action->setLibelle($value['libelle']);
                
                $permission->addAction($action);
                $entityManager->persist($action);
            }

            $permission->setLibelle($data['permission_libelle'])->generateSlug();
            $permission->updatedTimestamps();
            $permission->updatedUserstamps($this->getUser());

            $entityManager->persist($permission);
            $entityManager->flush();

            $this->addFlash('success', 'Permission <b>'. $permission->getLibelle() .'</b> modifiée avec succès.');
        }
        return $this->redirectToRoute('permissions', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete', name: 'permission_delete', methods: ['POST'])]
    public function delete(Request $request, Permissions $permission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$permission->getId(), $request->getPayload()->getString('_token'))) {
            $permission->remove($this->getUser());
            $entityManager->flush();
        }

        return $this->redirectToRoute('permissions', [], Response::HTTP_SEE_OTHER);
    }

}

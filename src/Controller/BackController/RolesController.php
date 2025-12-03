<?php

namespace App\Controller\BackController;

use App\Entity\Roles;
use App\Entity\Users;
use App\Form\RolesType;
use App\Entity\Permissions;
use App\Entity\Autorisations;
use App\Entity\ActionsAutorisation;
use App\Repository\RolesRepository;
use App\Repository\UsersRepository;
use App\Repository\ActionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PermissionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/roles')]
final class RolesController extends AbstractController
{
    #[Route('/', name: 'liste_roles')]
    public function liste(Request $request, 
        EntityManagerInterface $em, 
        SessionInterface $session, 
        RolesRepository $rolesRepository, 
        PermissionsRepository $permissionsRepository
    ): Response
    {
        $session->set('menu', 'users_manage');
        $session->set('subMenu', 'roles');

        $role = new Roles();
        $permissions = $em->getRepository(Permissions::class)->findAll();

        foreach ($permissions as $permission) {
            $autorisation = new Autorisations();
            $autorisation->setPermission($permission);
            $autorisation->setRole($role);

            // Pré-remplir les actions possibles de cette permission
            foreach ($permission->getActions() as $action) {
                $actionAutorisation = new ActionsAutorisation();
                $actionAutorisation->setAction($action);
                $actionAutorisation->setChecked(false); // par défaut décoché
                $actionAutorisation->setAutorisation($autorisation);

                $autorisation->addAction($actionAutorisation);
            }

            $role->getAutorisations()->add($autorisation);
        }

        $form = $this->createForm(RolesType::class, $role);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $role->generateSlug()->updatedTimestamps();
            $role->updatedUserstamps($this->getUser());

            $em->persist($role);
            $em->flush();
    
            $this->addFlash('success', 'Rôle créé avec succès.');
            return $this->redirectToRoute('liste_roles');
        }

        return $this->render('back/roles/liste.html.twig', [
            'roles' => $rolesRepository->findAll(),
            'new_form' => $form,
            'permissions' => $permissionsRepository->findAll()
        ]);
    }

    #[Route('/{role}/details', name: 'details_role')]
    public function details(Request $request, EntityManagerInterface $em, SessionInterface $session, Roles $role, PermissionsRepository $permissionsRepository): Response
    {
        $session->set('menu', 'users_manage');
        $session->set('subMenu', 'roles');

        $form = $this->createForm(RolesType::class, $role);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $role->generateSlug();
            $role->updatedTimestamps();
            $role->updatedUserstamps($this->getUser());

            $em->persist($role);
            $em->flush();
    
            $this->addFlash('success', 'Rôle <b>'. $role->getLibelle() .'</b> modifié avec succès.');
            return $this->redirectToRoute('details_role', ['role' => $role->getId()]);
        }

        return $this->render('back/roles/details.html.twig', [
            'role' => $role,
            'edit_form' => $form,
            'permissions' => $permissionsRepository->findAll()
        ]);
    }

    #[Route('/modification', name: 'edit_role')]
    public function edit(Request $request, 
        EntityManagerInterface $em, 
        RolesRepository $rolesRepository, 
        PermissionsRepository $permissionsRepository, 
        ActionsRepository $actionsRepository
    ): Response
    {
        if ($request->isMethod('POST') && $role = $rolesRepository->find($request->get('role_id'))) {
            $libelle = $request->get('role_libelle');
            $description = $request->get('role_description');
            $allAccess = $request->get('allAccess') ?? 0;
            $permissions = $request->get('permissions') ?? [];
            
            foreach ($role->getAutorisations() as $item) $role->removeAutorisation($item);

            foreach ($permissions as $permisLibelle => $value) {
                // $autorisation = $role->getAutorisations()
                //     ->filter(fn($a) => $a->getPermission()->getLibelle() === $permisLibelle)
                //     ->first();
                // if ($autorisation) {
                //     foreach ($autorisation->getActions() as $key => $action) {
                //         if (isset($value[$key])) $action->setChecked(true);
                //         else $action->setChecked(false);
                //     }
                // }
                // else {
                    $permission = $permissionsRepository->findOneByLibelle($permisLibelle);
                    $autorisation = new Autorisations();
                    $autorisation->setPermission($permission)->setRole($role);
                    
                    foreach ($permission->getActions() as $index => $action) {
                        $enrg = new ActionsAutorisation();
                        $checked = isset($value[$index]);
                        $enrg->setAction($action)->setChecked($checked);
                        $em->persist($enrg);

                        $autorisation->addAction($enrg);
                    }
                    $em->persist($autorisation);
                    $role->addAutorisation($autorisation);
                // }
            }

            $role->setLibelle($libelle)->setAllAccess($allAccess)->setDescription($description);
            $role->generateSlug();
            $role->updatedTimestamps();
            $role->updatedUserstamps($this->getUser());

            $em->persist($role);
            $em->flush();
    
            $this->addFlash('success', 'Rôle <b>'. $role->getLibelle() .'</b> modifié avec succès.');
        }
        return $this->redirectToRoute('liste_roles');
    }

    #[Route('/{role}/delete', name: 'role_delete', methods: ['POST'])]
    public function deleteRole(Request $request, Roles $role, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->getPayload()->getString('_token'))) {
            $role->remove($this->getUser());
            $entityManager->flush();
        }
        return $this->redirectToRoute('liste_roles', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{role}/delete-user/{user}', name: 'delete_role_user', methods: ['POST'])]
    public function deleteRoleUser(Request $request, Roles $role, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_role'.$role->getId().'_user'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $role->removeUser($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('liste_roles', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{role}/delete-selection', name: 'delete_role_user_selected', methods: ['POST'])]
    public function deleteRoleUsersSelected(Request $request, EntityManagerInterface $entityManager, Roles $role, UsersRepository $usersRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['rolesDeleted'] as $id) {
                if ($user = $usersRepository->find($id)) $role->removeUser($user);
                $entityManager->flush();
            }
        }
        return new JsonResponse($data, 200);
    }
}

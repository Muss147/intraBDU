<?php

namespace App\Controller\BackController;

use App\Entity\Files;
use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\RolesRepository;
use App\Repository\UsersRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/espace-admin/users')]
final class UsersController extends AbstractController
{
    private $fileUploader;
    private $entityManager;
    private $slugger;
    private $resetPasswordHelper;

    public function __construct(FileUploader $fileUploader, EntityManagerInterface $entityManager, SluggerInterface $slugger, ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->fileUploader = $fileUploader;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    #[Route('/', name: 'liste_users')]
    public function list(Request $request, UsersRepository $usersRepository, RolesRepository $rolesRepository, SessionInterface $session, MailerInterface $mailer, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Définition du menu actif
        $session->set('menu', 'users_manage');
        $session->set('subMenu', 'users');

        // Création du formulaire d'ajout d'utilisateur
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user, ['form_type' => 'add']);
        $form->handleRequest($request);

        // Soumission et validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $existingUser = $usersRepository->findOneByEmail($email);

            if ($existingUser) {
                $this->addFlash('error', $existingUser->getDeletedAt()
                    ? 'Email existant dans la corbeille. Supprimez-le avant de continuer.'
                    : 'Email déjà utilisé. Veuillez en choisir un autre.'
                );
            } else {
                // Gestion de l'upload de l'avatar
                if ($file = $form->get('avatar')->getData()) {
                    $data = $this->fileUploader->upload($file);
                    $fileEntity = (new Files())
                        ->setFilename($data['filename'])
                        ->setType($data['type'])
                        ->setAlt('Avatar de ' . $user->getNomComplet());
                    $user->setAvatar($fileEntity);
                    $this->entityManager->persist($fileEntity);
                }

                // Timestamps et Userstamps
                $hashedPassword = $passwordHasher->hashPassword($user, 'Azerty123');
                $user->setPassword($hashedPassword)->updatedTimestamps();
                $user->updatedUserstamps($this->getUser());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // Envoi de l'e-mail d'activation
                $token = $this->resetPasswordHelper->generateResetToken($user);
                $resetLink = $this->generateUrl('app_reset_password', ['token' => $token->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
                
                $emailMessage = (new Email())
                    ->from('hello@moussa-fofana.com')
                    ->to($user->getEmail())
                    ->subject('Bienvenue')
                    ->html($this->renderView('back/users/_email_userActivation.html.twig', [
                        'user' => $user,
                        'resetLink' => $resetLink,
                    ]));
                $mailer->send($emailMessage);

                // $this->addFlash('success', 'Utilisateur ajouté. Un email d’activation a été envoyé à son adresse : <b>'. $user->getEmail() .'</b>');
                $this->addFlash('success', 'Utilisateur ajouté. Le mot de passe par defaut est : <b>Azerty123</b>');
                return $this->redirectToRoute('liste_users');
            }
        }
        // Affichage initial ou avec erreurs
        return $this->render('back/users/liste.html.twig', [
            'users' => $usersRepository->findAll(),
            'roles' => $rolesRepository->findAll(),
            'new_user' => $form,
        ]);
    }

    #[Route('/{user}/resume', name: 'user_resume')]
    public function detailOverview(Request $request, Users $user, SessionInterface $session): Response
    {
        // Définition du menu actif
        $session->set('menu', 'users_manage');
        $session->set('subMenu', 'users');
        $session->set('userMenu', 'overview');

        return $this->render('back/users/details_overview.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/{user}/modification', name: 'user_setting', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, EntityManagerInterface $entityManager, SessionInterface $session, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Définition du menu actif
        $session->set('menu', 'users_manage');
        $session->set('subMenu', 'users');
        $session->set('userMenu', 'setting');

        $form_edit = $this->createForm(UsersType::class, $user, ['form_type' => 'edit']);
        $form_change_email = $this->createForm(UsersType::class, $user, ['form_type' => 'change_email']);
        $form_reset_password = $this->createForm(UsersType::class, new Users(), ['form_type' => 'reset_password']);
        // $form_deactivate = $this->createForm(UsersType::class, $user, ['form_type' => 'deactivate_account']);
        
        if (isset($request->get('users')['nomComplet'])) $form_edit->handleRequest($request);
        if (isset($request->get('users')['email'])) $form_change_email->handleRequest($request);
        if (isset($request->get('users')['password'])) $form_reset_password->handleRequest($request);
        // if (isset($request->get('users')['activated'])) $form_deactivate->handleRequest($request);

        if ($form_edit->isSubmitted() && $form_edit->isValid()) {
            $user->updatedTimestamps();
            $user->updatedUserstamps($this->getUser());
            $entityManager->flush();
            
            $this->addFlash('success', 'Les informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('user_setting', ['user' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($form_change_email->isSubmitted() && $form_change_email->isValid()) {
            // Vérification du mot de passe actuel
            $currentPassword = $form_change_email->get('currentPassword')->getData();
            if (!empty($currentPassword)) {
                if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                    // Mot de passe incorrect
                    $this->addFlash('error', 'Le mot de passe actuel n\'est correct. Veuillez réessayer.');
                } else {
                    // Si un nouvel email est saisi, on le met à jour
                    $newEmail = $form_change_email->get('email')->getData();
                    if (!empty($newEmail)) {
                        $user->setEmail($newEmail)->updatedTimestamps();
                        $user->updatedUserstamps($this->getUser());
                    }
                    // Enregistrement des changements
                    $entityManager->flush();

                    $this->addFlash('success', 'L\'adresse email a été mis à jour avec succès.');
                }
            }
            return $this->redirectToRoute('user_setting', ['user' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($form_reset_password->isSubmitted() && $form_reset_password->isValid()) {
            // Vérification du mot de passe actuel
            $currentPassword = $form_reset_password->get('currentPassword')->getData();
            if (!empty($currentPassword)) {
                if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                    // Mot de passe incorrect
                    $this->addFlash('error', 'Le mot de passe actuel n\'est pas correct. Veuillez réessayer.');
                } else {
                    // Si un nouveau mot de passe est saisi, on le met à jour
                    $newPassword = $form_reset_password->get('password')->getData();
                    if (!empty($newPassword)) {
                        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                        $user->setPassword($hashedPassword)->updatedTimestamps();
                        $user->updatedUserstamps($this->getUser());
                    }
                    // Enregistrement des changements
                    $entityManager->flush();

                    $this->addFlash('success', 'Le mot de passe a été mis à jour avec succès.');
                }
            }
            return $this->redirectToRoute('user_setting', ['user' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
        // if ($form_deactivate->isSubmitted() && $form_deactivate->isValid()) {
        if ($request->isMethod('POST')) {
            $user->setActivated(false)->updatedTimestamps();
            $user->updatedUserstamps($this->getUser());
            $entityManager->flush();
            
            $this->addFlash('success', 'L\utilisateur a été désactivé.');
            return $this->redirectToRoute('user_setting', ['user' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/users/details_setting.html.twig', [
            'user' => $user,
            'form_edit' => $form_edit,
            'form_change_email' => $form_change_email,
            'form_reset_password' => $form_reset_password,
            // 'form_deactivate' => $form_deactivate,
        ]);
    }

    #[Route('/{user}/logs', name: 'user_logs', methods: ['GET'])]
    public function logs(Users $user, SessionInterface $session): Response
    {
        // Définition du menu actif
        $session->set('menu', 'users_manage');
        $session->set('subMenu', 'users');
        $session->set('userMenu', 'logs');

        return $this->render('back/users/details_logs.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{user}/delete', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $user->remove($this->getUser());
            $em->flush();
        }
        return $this->redirectToRoute('liste_users', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete-users-selected', name: 'users_selected_delete', methods: ['POST'])]
    public function deleteUsersSelected(Request $request, EntityManagerInterface $em, UsersRepository $usersRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['usersDeleted'] as $id) {
                if ($user = $usersRepository->find($id)) $user->remove($this->getUser());
                $em->flush();
            }
        }
        return new JsonResponse(true, 200);
    }
}

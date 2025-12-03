<?php

namespace App\EventListener;

use App\Entity\Users;
use App\Entity\ActivityLog;
use App\Service\IpLocationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LoginListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private TokenStorageInterface $tokenStorage,
        private RouterInterface $router,
        private RequestStack $requestStack,
        private IpLocationService $ipLocationService,
        private Security $security
    ) {}

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $request = $event->getRequest();

        if (!$user->isActivated()) {
            // Déconnecter l'utilisateur
            $this->tokenStorage->setToken(null);
            
            // Invalider la session de l'utilisateur
            $request->getSession()->invalidate();
            
            // Redirection via une réponse à gérer dans le contrôleur
            $response = new RedirectResponse($this->router->generate('app_desactivated'));
            // Retourner la réponse
            $request->getSession()->set('_security.main.target_path', $this->router->generate('app_desactivated'));
        }
        elseif ($user instanceof \App\Entity\Users) {
            $user->setLastLogin(new \DateTime());
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $event->getUser();

        $this->logLoginAttempt($user, 'OK', $request);
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $email = $request->get('_username') ?? $request->request->get('email');

        // On tente de récupérer l'utilisateur s'il existe
        $user = $this->em->getRepository(Users::class)->findOneBy(['email' => $email]);

        if ($user) $this->logLoginAttempt($user, 'ERR', $request, $email);
    }

    private function logLoginAttempt(?Users $user, string $status, $request, ?string $email = null): void
    {
        $ip = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');
        $location = $this->ipLocationService->getLocation($ip);

        $log = new ActivityLog();
        $log->setType('Login');
        $log->setMethod('Login');
        $log->setIpAddress($ip);
        $log->setDevice($userAgent);
        $log->setLocation($location);
        $log->setStatus($status);
        $log->setUser($user);
        $log->setCreatedAt(new \DateTime());

        $this->em->persist($log);
        $this->em->flush();
    }
}

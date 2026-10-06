<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/login_redirect', name: 'app_login_redirect')]
    public function redirectAfterLogin(): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();

        // Vérification STRICTE pour l'entrepreneur
        // On vérifie si ROLE_ENTREPRENEUR est présent 
        // (Note: Symfony ajoute toujours ROLE_USER par défaut dans l'entité)
        if (in_array('ROLE_ENTREPRENEUR', $roles)) {
            return $this->redirectToRoute('app_entrepreneur_dashboard');
        }

        // Vérification pour le propriétaire (ROLE_USER simple)
        if (count($roles) === 1 && $roles[0] === 'ROLE_USER') {
            return $this->redirectToRoute('app_proprietaire_dashboard');
        }

        // Par défaut ou pour les autres (Inspecteur, etc.)
        return $this->redirectToRoute('app_login'); 
    }
    
}

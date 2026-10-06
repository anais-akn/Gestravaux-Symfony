<?php
// src/Security/AuthenticationSuccessHandler.php

namespace App\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private RouterInterface $router) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_INSPECTEUR', $roles)) {
            return new RedirectResponse($this->router->generate('app_inspecteur_dashboard'));
        }

        if (in_array('ROLE_ENTREPRENEUR', $roles)) {
            return new RedirectResponse($this->router->generate('app_entrepreneur_dashboard'));
        }

        // Propriétaire par défaut
        return new RedirectResponse($this->router->generate('app_proprietaire_dashboard'));
    }
}
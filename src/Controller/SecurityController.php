<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/conexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirige les utilisateurs authentifiés vers une autre route
        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    #[Route(path: '/deconexion', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut être vide: elle sera interceptée par le mécanisme de déconnexion de Symfony
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

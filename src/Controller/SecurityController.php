<?php

namespace App\Controller;

use App\Repository\SitterRepository;
use App\Repository\UserParentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, SitterRepository $sitterRepository, UserParentRepository $userParentRepository): Response
    {

        if ($this->getUser()) {
            // Vérifiez le rôle de l'utilisateur connecté et redirigez en conséquence
            // Si il a le role est Parent, redirection vers l'acceuil
            if ($this->isGranted('ROLE_PARENT')) {
                return $this->redirectToRoute('insert_parent');
            }
            // Si il a le role est Sitter, redirection vers l'acceuil
            if ($this->isGranted('ROLE_SITTER')) {
                return $this->redirectToRoute('sitter_profil');
            }


        }


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

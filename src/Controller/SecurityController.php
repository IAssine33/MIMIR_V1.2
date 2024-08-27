<?php

namespace App\Controller;

use App\Repository\SitterRepository;
use App\Repository\UserParentRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, SitterRepository $sitterRepository, UserParentRepository $userParentRepository): Response
    {

        /*
                      $currentUser = $this->geter();

                              if ($currentUser !== null && $this->isGranted('ROLE_PARENT')) {
                                  return $this->redirectToRoute('app_home');
                              }
                              if ($currentUser !== null && $this->isGranted('ROLE_SITTER')) {
                                  return $this->redirectToRoute('app_home');
                              }

               if ($currentUser !== null && $this->isGranted('ROLE_SITTER')) {
                   return $this->redirectToRoute('insert_sitter');
               }
            */

        // Vérifier si l'utilisateur est connecté
        if ($this->getUser()) {
            $user = $this->getUser();
            // Vérifiez si l'utilisateur est un sitter enregistré
            $sitter = $sitterRepository->findOneBy(['user' => $user]);

            if ($sitter  && $this->isGranted('ROLE_SITTER')) {

                // Si l'utilisateur est un sitter enregistré, rediriger-le vers la page acceuil
                return $this->redirectToRoute('home');
            } else {
                // Si l'utilisateur n'est pas enregistré comme sitter, rediriger vers le formulaire d'enregistrement
                return $this->redirectToRoute('insert_sitter');
            }
        }

        // Vérifier si l'utilisateur est connecté
        if ($this->getUser()) {
            $user = $this->getUser();
            // Vérifiez si l'utilisateur est un parent enregistré
            $userParent = $userParentRepository->findOneBy(['user' => $user]);

            if ($userParent && $this->isGranted('ROLE_PARENT')) {
                // Si l'utilisateur est un parent enregistré, rediriger-le vers la page acceuil
                return $this->redirectToRoute('home');
            } else{
                return $this->redirectToRoute('insert_parent');
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

<?php

namespace App\Controller\Public;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class userController extends AbstractController
{
    #[Route('/user/new', name: 'user_new')]
    public function newUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $password = $userForm->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        /*
            // Définir le rôle et la redirection en fonction du type d'utilisateur
            if ($type === 'parent') {
                $user->setRoles(['ROLE_PARENT']);
                $redirectRoute = 'app_login';
            } elseif ($type === 'sitter') {
                $user->setRoles(['ROLE_SITTER']);
                $redirectRoute = 'app_login';
            } else {
                throw new \InvalidArgumentException('Invalid user type');
            }
        */
            try {
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                return $this->render('errors/error-404.html.twig', [
                    'error' => $e->getMessage(),
                ]);
            }

            // Rediriger vers la page login
            return $this->redirectToRoute('app_login');
        }

        return $this->render('public/page/user/new_user.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }
}
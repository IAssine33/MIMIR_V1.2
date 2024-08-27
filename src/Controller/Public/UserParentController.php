<?php

namespace App\Controller\Public;

use App\Entity\User;
use App\Entity\UserParent;
use App\Form\UserParentType;
use App\Form\UserType;
use App\Repository\UserParentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserParentController extends AbstractController
{
    #[Route('/user/newParent', name: 'user_newParent')]
    public function newUserParent(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher ): Response
    {

        $userParent = new User();
        $userForm = $this->createForm(UserType::class, $userParent);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $password = $userForm->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword($userParent, $password);
            $userParent->setPassword($hashedPassword);

            try {
                $entityManager->persist($userParent);
                $entityManager->flush();
            } catch (\Exception $e) {
                return $this->render('errors/error-404.html.twig', [
                    'error' => $e->getMessage()
                ]);

            }
            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('public/page/parent/new_userParent.html.twig', ['userForm' => $userForm->createView()]);
    }

    #[Route('/user/parent/insert', name: 'insert_parent')]
    public function insertParent(Request $request, EntityManagerInterface $entityManager, UserParentRepository $userParentRepository, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();

        // Vérifier si l'utilisateur a déjà un UserParent enregistré
        $existingParent = $userParentRepository->findOneBy(['user' => $user]);
    /*
        if ($existingParent) {
            // Vérifier si certains champs du UserParent sont déjà remplis
            if (!empty($existingParent->getPhone()) || !empty($existingParent->getAdress())) {

                // Si les champs ne sont pas vides, rediriger vers la page d'accueil
                return $this->redirectToRoute('parent_profil');
            } else {
                return $this->redirectToRoute('app_login');
            }
        }
    */
        // Si l'utilisateur n'a pas de UserParent ou si les champs sont vides, j'affiche le formulaire
        // $userParent = $existingParent ?? new UserParent();
        // le meme resultat en bas
        if ($existingParent !== null) {
            $userParent = $existingParent;
        } else {
            $userParent = new UserParent();
        }
        $userParent->setUser($user);
        $userParentForm = $this->createForm(UserParentType::class, $userParent);
        $userParentForm->handleRequest($request);

        if ($userParentForm->isSubmitted() && $userParentForm->isValid()) {

            try {
                $entityManager->persist($userParent);
                $entityManager->flush();
            }catch (\Exception $e){
                return $this->render('errors/error-404.html.twig', ['error' => $e->getMessage()]);
            }
            // Rediriger vers la page de l'acceuil
            return $this->redirectToRoute('home');
        }
        return $this->render('public/page/parent/new_parent.html.twig', ['userParentForm' => $userParentForm->createView()]);
    }


}
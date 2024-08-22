<?php

namespace App\Controller\Admin;



use App\Entity\User;
use App\Form\UserType;
use App\Form\SitterType;
use App\Repository\SitterRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;



class UserController extends AbstractController
{

    #[Route('/user/new', name: 'user_new')]
    public function newSitter(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);


        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $password = $userForm->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            try{

                //$user->setRoles(['ROLE_SITTER']);
                $entityManager->persist($user);
                $entityManager->flush();
            }catch (\Exception $e){
                return $this->render('admin/errors/error-404.html.twig', [
                    'error' => $e->getMessage(),
                ]);
            }
            // Persister l'entité user


            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }


        return $this->render('admin/page/user/newUser.html.twig', [
            "userForm" =>  $userForm,
        ]);
    }



    #[Route('/sitter/update/{id}', name: 'sitter_update')]
    public function updateSitter(?int $id, Request $request, EntityManagerInterface $entityManager, SitterRepository $sitterRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $sitter = $sitterRepository->find($id);
    //    $oldPassword = $sitter->getPassword();

   //   $sitter = $id ? $entityManager->getRepository(Sitter::class)->find($id) : new Sitter();
        $sitterForm = $this->createForm(SitterType::class, $sitter);

        $sitterForm->handleRequest($request);

        if ($sitterForm->isSubmitted() && $sitterForm->isValid()) {

            $password = $sitterForm->get('password')->getData();

            if($password){
                $hashedPassword = $passwordHasher->hashPassword(
                    $sitter,
                    $password,
                );
                $sitter->setPassword($hashedPassword);
            }

            $entityManager->persist($sitter);
            $entityManager->flush();

            $this->addFlash('success', 'modification du Nounou enregistrée');
        }

        return $this->render('admin/page/sitter/edit_sitter.html.twig', [
            'sitterForm' => $sitterForm->createView(),
        ]);
    }

}
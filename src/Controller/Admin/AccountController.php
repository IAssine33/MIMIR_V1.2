<?php

namespace App\Controller\Admin;

use App\Entity\Account;

use App\Form\AccountType;
use App\Form\SitterType;
use App\Repository\SitterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;



class AccountController extends AbstractController
{
    #[Route('/account/new', name: 'account_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $account = new Account();
        $accountForm = $this->createForm(AccountType::class, $account);
        $accountForm->handleRequest($request);


        if ($accountForm->isSubmitted() && $accountForm->isValid()) {

            $password = $accountForm->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword($account, $password);
            $account->setPassword($hashedPassword);

            // Persister l'entité Account
            $entityManager->persist($account);
            $entityManager->flush();

            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/page/account/newAccount.html.twig', [
            'accountForm' => $accountForm->createView(),
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
<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Form\AccountType;
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
            // Hash le mot de passe
            $account->setPassword(
                $passwordHasher->hashPassword(
                    $account,
                    $account->getPassword()
                )
            );

            // Persist l'entité Account
            $entityManager->persist($account);
            $entityManager->flush();

            // Redirige vers la page de création de Sitter ou UserParent
            return $this->redirectToRoute('sitter_new', ['accountId' => $account->getId()]);
        }

        return $this->render('account/new.html.twig', [
            'accountForm' => $accountForm->createView(),
        ]);
    }
}
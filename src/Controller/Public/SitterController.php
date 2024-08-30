<?php

namespace App\Controller\Public;

use App\Entity\Sitter;
use App\Form\SitterType;
use App\Repository\SitterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SitterController extends AbstractController
{


    #[Route('/user/sitter/insert', name: 'insert_sitter')]
   public function insertSitter(Request $request, EntityManagerInterface $entityManager, SitterRepository $sitterRepository, Security $security): Response
    {
        // Vérifiez si l'utilisateur n'est pas connecté
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifiez si l'utilisateur a le rôle 'ROLE_SITTER'
        if (!$this->isGranted('ROLE_SITTER')) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        // Vérifier si l'utilisateur a déjà un Sitter enregistré
        $existingSitter = $sitterRepository->findOneBy(['user' => $user]);



        if ($existingSitter) {
            // je recupére la sitter enregistrer avec son id
            $id = $existingSitter->getId();
            // Vérifier si certains champs du Sitter sont déjà remplis

            if (!empty($existingSitter->getBio()) || !empty($existingSitter->getCertifications())) {

                // Si les champs ne sont pas vides, rediriger vers la page de profil
                return $this->redirectToRoute('sitter_profil', ['id' => $id])   ;
            } else {
                return $this->redirectToRoute('app_login');
            }
        }

        // Si l'utilisateur n'a pas de Sitter ou si les champs sont vides, j'affiche le formulaire
        // $sitter = $existingSitter ?? new Sitter();
        // le meme resultat en bas
        if ($existingSitter !== null) {
            $sitter = $existingSitter;
        } else {
            $sitter = new Sitter();
        }
        $sitter->setUser($user);
        $sitterForm = $this->createForm(SitterType::class, $sitter);
        $sitterForm->handleRequest($request);


        if ($sitterForm->isSubmitted() && $sitterForm->isValid()) {

            try {

                //$user->setRoles(['ROLE_SITTER']);
                $entityManager->persist($sitter);
                $entityManager->flush();

            } catch (\Exception $e) {
                return $this->render('errors/error-404.html.twig', [
                    'error' => $e->getMessage(),
                ]);
            }
            // Rediriger vers la page de l'acceuil
            return $this->redirectToRoute('home');


        }


        return $this->render('public/page/sitter/new_sitter.html.twig', ['sitterForm' => $sitterForm->createView()]);
    }

    #[Route('/user/sitter/{id}', name: 'sitter_profil')]
    public function sitter_by_id(int $id, SitterRepository $sitterRepository): Response
    {
        $sitter = $sitterRepository->find($id);

        return $this->render('public/page/sitter/sitter_profil.html.twig', ['sitter' => $sitter]);

    }

    #[Route('/user/sitter/update', name: 'sitter_update')]
    public function updateSitter( Request $request, EntityManagerInterface $entityManager, SitterRepository $sitterRepository, UserPasswordHasherInterface $passwordHasher): Response
    {

        $currentUser = $this->getUser();
        /*
            if ($currentUser !== null && $this->isGranted('ROLE_SITTER')) {
                return $this->redirectToRoute('sitter_update', ['id' => $id]);
            }
        */
        $sitter = $sitterRepository->findOneBy(['user' => $currentUser]);


        $sitterForm = $this->createForm(SitterType::class, $sitter);
        $sitterForm->handleRequest($request);

        if ($sitterForm->isSubmitted() && $sitterForm->isValid()) {

            $entityManager->persist($sitter);
            $entityManager->flush();

            $this->addFlash('success', 'modification du Nounou enregistrée');
            return $this->redirectToRoute('sitter_profil');
        }

        return $this->render('public/page/sitter/edit_sitter.html.twig', ['user' => $currentUser, 'sitterForm' => $sitterForm->createView()

        ]);
    }


        #[Route('/user/sitter/delete/{id}', name: 'sitter_delete')]
        public function deleteSitter(EntityManagerInterface $entityManager, SitterRepository $sitterRepository, Security $security): Response
        {
            $currentUser = $this->getUser();

            $sitter = $sitterRepository->findOneBy(['user' => $currentUser]);

            if (!$sitter) {
                $this->addFlash('error', 'Aucune Nounou trouvé pour cet utilisateur! veuillez créez une nouvelle.');
                return $this->redirectToRoute('insert_sitter');
            }

            try {
                $entityManager->remove($sitter);
                $entityManager->flush();
                $security->logout(false);
                $this->addFlash('success', 'Suppression du Nounou ' . $sitter->getUser()->getFirstname());
            }catch (\Exception $e){
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('home');
        }

}
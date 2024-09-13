<?php

namespace App\Controller\Public;

use App\Entity\Sitter;
use App\Form\SitterType;
use App\Repository\SitterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SitterController extends AbstractController
{


    #[Route('/user/sitter/insert', name: 'insert_sitter')]
   public function insertSitter(Request $request, EntityManagerInterface $entityManager, SitterRepository $sitterRepository, Security $security, SluggerInterface $slugger, ParameterBagInterface $paramBag): Response
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

        // traiter une requête HTTP et pré-remplir un formulaire avec les données reçues de cette requête
        $sitterForm->handleRequest($request);


        if ($sitterForm->isSubmitted() && $sitterForm->isValid()) {

            $imageFile = $sitterForm->get('photo_url')->getData();
            // si il y a bien un fichier envoyé
            if ($imageFile) {
                //je récupère son nom
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // je nettoie le nom (sort les caractères spéciaux etc)

                $safeFilename = $slugger->slug($originalFilename);
                // Je rajoute un identifiant unnique au nom
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {

                    //je récupère le chemin de la racine du projet
                    $rootPath = $paramBag->get('kernel.project_dir');

                    // je déplace le fichier dans le dossier /public/upload en partant de la racine
                    // du projet, et je renomme le fichier avec le nouveau nom (slugifié et identifiant unique)
                    $imageFile->move($rootPath . '/public/assets/uploads', $newFilename);


                } catch (\Exception $e) {
                    return $this->render('errors/error-404.html.twig', [
                        'error' => $e->getMessage(),
                    ]);
                }
                // je stocke dans la propriété image
                // de l'entité article le nom du fichier
                $sitter->setPhotoUrl($newFilename);
                //$user->setRoles(['ROLE_SITTER']);
                $entityManager->persist($sitter);
                $entityManager->flush();
            }
            // Rediriger vers la page de l'acceuil
            return $this->redirectToRoute('home');


        }


        return $this->render('public/page/sitter/new_sitter.html.twig', ['sitterForm' => $sitterForm->createView()]);
    }

    #[Route('/user/sitter/profil/{id}', name: 'sitter_profil',requirements: ['id' => '\d+'])]
    public function sitter_by_id(int $id, SitterRepository $sitterRepository): Response
    {
        $sitter = $sitterRepository->find($id);
        if (!$sitter) {
            throw $this->createNotFoundException('Nounou introuvable');
        }

        return $this->render('public/page/sitter/sitter_profil.html.twig', ['sitter' => $sitter]);

    }

    #[Route('/user/sitter/update/{id}', name: 'sitter_update')]
    public function updateSitter(int $id, Request $request, EntityManagerInterface $entityManager,Sitter $sitter, SitterRepository $sitterRepository,SluggerInterface $slugger, ParameterBagInterface $parameterBag): Response
    {
        $existingImage = $sitter->getPhotoUrl();

        $sitter = $sitterRepository->find($id);

        if (!$sitter) {
            throw $this->createNotFoundException('No sitter found for id ' . $id);
        }

        $sitterForm = $this->createForm(SitterType::class, $sitter);

        //traiter une requête HTTP et pré-remplir un formulaire avec les données reçues de cette requête
        $sitterForm->handleRequest($request);

        if ($sitterForm->isSubmitted() && $sitterForm->isValid()) {
            $imageFile = $sitterForm->get('photo_url')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    //je récupère le chemin de la racine du projet
                    $rootPath = $parameterBag->get('kernel.project_dir');

                    // je déplace le fichier dans le dossier /public/upload en partant de la racine
                    // du projet, et je renomme le fichier avec le nouveau nom (slugifié et identifiant unique)
                    $imageFile->move($rootPath . '/public/assets/uploads', $newFilename);
                } catch (FileException $e) {
                    // message d'erreur
                }

            }

            $entityManager->persist($sitter);
            $entityManager->flush();

            $this->addFlash('success', 'modification du Nounou enregistrée');
            return $this->redirectToRoute('sitter_profil', ['id' => $id]);
        }


        return $this->render('public/page/sitter/edit_sitter.html.twig', ['sitterForm' => $sitterForm->createView()

        ]);
    }


        #[Route('/user/sitter/delete/{id}', name: 'sitter_delete')]
        public function deleteSitter(EntityManagerInterface $entityManager, SitterRepository $sitterRepository, Security $security): Response
        {
            $currentUser = $this->getUser();

            //recherche dans la base de données un sitter qui est lié à cet utilisateur
            $sitter = $sitterRepository->findOneBy(['user' => $currentUser]);

            if (!$sitter) {
                $this->addFlash('error', 'Aucune Nounou trouvé pour cet utilisateur! veuillez créez une nouvelle.');
                return $this->redirectToRoute('insert_sitter');
            }

            try {
                // Supprime l'entité userParent de la base de données
                $entityManager->remove($sitter);
                // Applique les changements dans la base de données (exécute la suppression)
                $entityManager->flush();
                // Déconnecte l'utilisateur en cours de la session
                $security->logout(false);
                // Ajoute un message flash de succès à afficher à l'utilisateur
                $this->addFlash('success', 'Suppression du Nounou ' . $sitter->getUser()->getFirstname());
            }catch (\Exception $e){
                // Si une erreur se produit, elle est capturée et un message flash d'erreur est ajouté
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('home');
        }

}
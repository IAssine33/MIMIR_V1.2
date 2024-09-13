<?php

namespace App\Controller\Admin;

use App\Repository\SitterRepository;
use App\Repository\UserParentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;


class AdminController extends AbstractController
{

    #[Route('/admin', name: 'admin')]
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }


    #[Route('/admin/listSitters', name: 'admin_list_sitters')]
    public function adminListSitters(SitterRepository $sitterRepository, UserRepository $userRepository){

        $sitters = $sitterRepository->findAll();
        $users = $userRepository->findAll();


        return $this->render('admin/admin_listSitters.html.twig', ['sitters' => $sitters,'users' => $users]);
    }

    #[Route('/admin/listParents', name: 'admin_list_parents')]
    public function adminListParents(UserParentRepository $userParentRepository, UserRepository $userRepository){

        $users = $userRepository->findAll();
        $userParents = $userParentRepository->findAll();

        return $this->render('admin/admin_listParents.html.twig', ['users' => $users, 'userParents' => $userParents]);
    }

}
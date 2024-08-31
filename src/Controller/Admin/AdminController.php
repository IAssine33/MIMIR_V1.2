<?php

namespace App\Controller\Admin;

use App\Repository\SitterRepository;
use App\Repository\UserParentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;


class AdminController extends AbstractController
{
    #[Route('/admin/list', name: 'admin_list')]
    public function adminList(SitterRepository $sitterRepository, UserRepository $userRepository, UserParentRepository $userParentRepository){

        $sitters = $sitterRepository->findAll();
        $users = $userRepository->findAll();
        $userParents = $userParentRepository->findAll();

        return $this->render('admin/admin_list.html.twig', ['sitters' => $sitters,'users' => $users, 'userParents' => $userParents]);
    }

}
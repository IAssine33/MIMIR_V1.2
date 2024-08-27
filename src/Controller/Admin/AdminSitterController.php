<?php

namespace App\Controller\Admin;

use App\Repository\SitterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;


class AdminSitterController extends AbstractController
{
    #[Route('/admin/sitters', name: 'admin_list_sitters')]
    public function adminListSitters(SitterRepository $sitterRepository){

        $sitters = $sitterRepository->findAll();

        return $this->render('admin/page/sitter/list_sitters.html.twig', ['sitters' => $sitters]);
    }

}
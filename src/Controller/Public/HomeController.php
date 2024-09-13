<?php

namespace App\Controller\Public;

use App\Repository\SitterRepository;
use App\Repository\UserParentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', "home")]
    public function homeSitters(SitterRepository $sitterRepository)
    {
        $sitters = $sitterRepository->findAllOrderedByUpdatedAt();


        return $this->render('public/page/home.html.twig', ['sitters' => $sitters]);

    }

    #[Route('/home/parent', "home_parent")]
    public function homeParents(UserParentRepository $parentRepository)
    {
        $parents = $parentRepository->findAllOrderedByUpdatedAtParents();

        return $this->render('public/page/parent/home_parent.html.twig', ['parents' => $parents]);
    }

}
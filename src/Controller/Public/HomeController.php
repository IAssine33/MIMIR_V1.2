<?php

namespace App\Controller\Public;

use App\Repository\SitterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', "home")]
    public function home(SitterRepository $sitterRepository)
    {
        $sitters = $sitterRepository->findAll();


        return $this->render('public/page/home.html.twig', ['sitters' => $sitters]);

    }

}
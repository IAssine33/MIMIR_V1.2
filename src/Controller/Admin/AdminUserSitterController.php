<?php

namespace App\Controller\Admin;


use App\Entity\Sitter;
use App\Form\SitterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminUserSitterController extends AbstractController
{
    #[Route('/user/sitter/insert', name: 'admin_sitter')]
    function insertAdmin( Request $request): Response
    {

        $sitter = new Sitter();
        $sitterForm = $this->createForm(SitterType::class, $sitter);
        $sitterForm->handleRequest($request);



        return $this->render('admin/page/sitter/edit_sitter.html.twig', ['sitterForm' => $sitterForm->createView(),]);
    }

}
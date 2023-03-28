<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/front/office/categorie', name: 'app_front_office_categorie')]
    public function index(): Response
    {
        return $this->render('front_office/categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }
}

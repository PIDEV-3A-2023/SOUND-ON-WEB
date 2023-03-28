<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    #[Route('/front/office/catalogue', name: 'app_front_office_catalogue')]
    public function index(): Response
    {
        return $this->render('front_office/catalogue/index.html.twig', [
            'controller_name' => 'CatalogueController',
        ]);
    }
}

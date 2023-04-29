<?php

namespace App\Controller\FrontOffice;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/front/office/categorie', name: 'app_front_office_categorie')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('front_office/categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

}

<?php

namespace App\Controller\FrontOffice;

use App\Entity\Categorie;
use App\Repository\CatalogueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    #[Route('/front/office/catalogue/{id}', name: 'app_front_office_catalogue')]
    public function index(CatalogueRepository $catalogueRepository , Categorie $categorie): Response
    {   
        return $this->render('front_office/catalogue/index.html.twig', [
            'catalogues' => $catalogueRepository->findBy(array('idCategorie' => $categorie -> getId())),
        ]);
    }
}

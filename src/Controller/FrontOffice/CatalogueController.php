<?php

namespace App\Controller\FrontOffice;

use App\Entity\Catalogue;
use App\Entity\Categorie;
use App\Repository\CatalogueRepository;
use App\Repository\CategorieRepository;
use App\Repository\RatingRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;


class CatalogueController extends AbstractController
{
    #[Route('/front/office/catalogue/all/{id}', name: 'app_front_office_catalogue')]
    public function index(CatalogueRepository $catalogueRepository , Categorie $categorie , CategorieRepository $categorieRepository , utilisateurRepository $utilisateurRepository,RatingRepository $ratingRepository): Response
    {
        $categorie->setVisiteur($categorie->getVisiteur() + 1);
        $categorieRepository->save($categorie, true);
        // Get the logged-in user
        $user = $utilisateurRepository -> find(5);

        $catalogues = $catalogueRepository->findBy(['idCategorie' => $categorie->getId()]);
        $catalogueRatings = [];
        foreach ($catalogues as $catalogue) {
            $existingRating = $ratingRepository->findOneBy([
                'owner' => $user,
                'catalogue' => $catalogue
            ]);


            $rating = 0;
            if ($existingRating) {
                $rating = $existingRating->getRating();
            }

            // Get all ratings for the current catalogue
            $allRatings = $ratingRepository->findBy(['catalogue' => $catalogue]);
            $totalRating = 0;
            $rating_number = count($allRatings);
            foreach ($allRatings as $rate) {
                $totalRating += $rate->getRating();
            }
            if ($rating_number == 0 )
                $rating_number = 1;
            /*
                        // Calculate the average rating
                        $avgRating = 0;
                        if (count($allRatings) > 0) {
                            $avgRating = $totalRating / count($allRatings);
                        }*/

            $catalogueRatings[] = [
                'catalogue' => $catalogue,
                'rating' => $rating,
                'totalrating' => $totalRating / $rating_number
            ];
        }

        return $this->render('front_office/catalogue/index.html.twig', [
            'catalogues' => $catalogueRatings
        ]);
    }

    #[Route('/front/office/catalogue/view/{id}', name: 'app_front_office_catalogue_show', methods: ['GET'])]
    public function show(Catalogue $catalogue): Response
    {
        return $this->render('front_office/catalogue/view.html.twig', [
            'catalogue' => $catalogue,
        ]);
    } 

    #[Route('/front/office/catalogue/image/{id}', name: 'app_front_office_catalogue_content')]
    public function imageContent(Catalogue $catalogue): Response
    {
        $file = $catalogue->getImage();
        $response = new Response();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, basename($catalogue->getImage()));
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'image/jpg');
        $response->setContent(file_get_contents($file));

        return $response;
    }
}

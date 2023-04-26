<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\CatalogueRepository;
use App\Repository\RatingRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rating')]
class RatingController extends AbstractController
{
    #[Route('/', name: 'app_rating_index', methods: ['GET'])]
    public function index(RatingRepository $ratingRepository): Response
    {
        return $this->render('rating/index.html.twig', [
            'ratings' => $ratingRepository->findAll(),
        ]);
    }
    #[Route('/catalogue/{idc}/rating', name: 'app_catalogue_rating', methods: ['POST'])]
    public function rateCatalogue(Request $request, $idc, RatingRepository $ratingRepository, UtilisateurRepository $utilisateurRepository,CatalogueRepository  $catalogueRepository): Response
    {
        // Get the logged-in user
        $user = $utilisateurRepository -> find(2);

        // Get the catalogue by id
        $catalogue = $catalogueRepository -> find($idc);

        // Check if the catalogue exists
        if (!$catalogue) {
            throw $this->createNotFoundException('Catalogue not found');
        }

        // Get the rating value from the submitted form
        $ratingValue = $request->request->get('rating');

        // Check if the user has already rated this catalogue
        $existingRating = $ratingRepository->findOneBy([
            'owner' => $user,
            'catalogue' => $catalogue
        ]);

        if ($existingRating) {
            // Update the existing rating
            $existingRating->setRating($ratingValue);
            $ratingRepository->save($existingRating,true);
        } else {
            // Create a new rating
            $rating = new Rating();
            $rating->setRating($ratingValue);
            $rating->setOwner($user);
            $rating->setCatalogue($catalogue);
            $ratingRepository->save($rating,true);
        }

        // Redirect back to the catalogue page
        return $this->redirectToRoute('app_front_office_catalogue', ['id' => $catalogue->getIdCategorie()]);
    }


}

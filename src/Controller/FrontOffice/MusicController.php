<?php

namespace App\Controller\FrontOffice;

use App\Entity\Musique;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/music')]
class MusicController extends AbstractController
{
    #[Route('/', name: 'app_front_office_music')]
    public function index(): Response
    {
        return $this->render('front_office/music/index.html.twig', [
            'controller_name' => 'MusicController',
        ]);
    }

    #[Route('/{id}', name: 'app_front_office_music_show', methods: ['GET'])]
    public function show(Musique $musique): Response
    {
        return $this->render('front_office/music/show.html.twig', [
            'song' => $musique,
        ]);
    }

}

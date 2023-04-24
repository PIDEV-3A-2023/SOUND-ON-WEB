<?php

namespace App\Controller\FrontOffice;

use App\Entity\Musique;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

    #[Route('/{id}', name: 'app_front_office_music_play')]
    public function play(Musique $musique): Response
    {
        return $this->render('home/music-player.html.twig', [
            'song' => $musique,
        ]);
    }

    #[Route('/file/{id}', name: 'app_front_office_music_content')]
    public function songContent(Musique $musique): Response
    {
        $file = $musique->getChemin();
        $response = new Response();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_INLINE, basename($musique->getChemin()));
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'audio/mpeg');
        $response->setContent(file_get_contents($file));

        return $response;
    }

}

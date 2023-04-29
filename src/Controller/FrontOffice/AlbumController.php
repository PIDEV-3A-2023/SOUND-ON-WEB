<?php

namespace App\Controller\FrontOffice;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/albums')]
class AlbumController extends AbstractController
{
    #[Route('/', name: 'app_front_office_album_list', methods: ['GET'])]
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('front_office/album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_front_office_album_show', methods: ['GET'])]
    public function show(Album $album): Response
    {
        return $this->render('front_office/album/show.html.twig', [
            'album' => $album,
        ]);
    }
}

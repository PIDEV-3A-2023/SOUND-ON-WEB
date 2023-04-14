<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MusicController extends AbstractController
{
    #[Route('/front/office/music', name: 'app_front_office_music')]
    public function index(): Response
    {
        return $this->render('front_office/music/index.html.twig', [
            'controller_name' => 'MusicController',
        ]);
    }
}

<?php

namespace App\Controller\RestApi;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/albums')]
class AlbumController extends AbstractController
{
    #[Route('/', name: 'album_api_list')]
    public function getAlbums(AlbumRepository $repo, SerializerInterface $serializer)
    {
        $albums = $repo->findAll();
        $json = $serializer->serialize($albums, 'json', ['groups' => "albums"]);

        return new Response($json);
    }

    #[Route('/{id}', name: 'album_api_getOne')]
    public function getAlbum(AlbumRepository $repo, SerializerInterface $serializer, $id)
    {
        $album = $repo->find($id);
        $json = $serializer->serialize($album, 'json', ['groups' => "albums"]);

        return new Response($json);
    }

    #[Route('/new', name: 'album_api_new')]
    public function addAlbum(AlbumRepository $repo, SerializerInterface $serializer,Request $request)
    {
        $album = new Album();
        $album->setNom($request->get('nom'));
        $album->setDateCreation($request->get('dateCreation'));
        $album->setIdUser($request->get('idUser'));
        $repo->save($album, true);
        $json = $serializer->serialize($album, 'json', ['groups' => "albums"]);

        return new Response($json);
    }

    #[Route('/update/{id}', name: 'album_api_new')]
    public function updateAlbum(AlbumRepository $repo, SerializerInterface $serializer,Request $request, $id)
    {
        $album = $repo->find($id);
        $album->setNom($request->get('nom'));
        $album->setDateCreation($request->get('dateCreation'));
        $album->setIdUser($request->get('idUser'));
        $repo->save($album, true);
        $json = $serializer->serialize($album, 'json', ['groups' => "albums"]);

        return new Response($json);
    }

    #[Route('/delete/{id}', name: 'album_api_new')]
    public function deleteAlbum(AlbumRepository $repo, SerializerInterface $serializer,Request $request, $id)
    {
        $album = $repo->find($id);
        $repo->remove($album, true);
        $json = $serializer->serialize($album, 'json', ['groups' => "albums"]);

        return new Response($json);
    }
}

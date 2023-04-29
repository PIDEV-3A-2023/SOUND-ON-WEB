<?php

namespace App\Controller\RestApi;

use App\Entity\Musique;
use App\Repository\MusiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/musiques')]
class MusiqueController extends AbstractController
{
    #[Route('/', name: 'musique_api_list')]
    public function getmusiques(MusiqueRepository $repo, SerializerInterface $serializer)
    {
        $musiques = $repo->findAll();
        $json = $serializer->serialize($musiques, 'json', ['groups' => "musiques"]);

        return new Response($json);
    }

    #[Route('/{id}', name: 'musique_api_getOne')]
    public function getmusique(musiqueRepository $repo, SerializerInterface $serializer, $id)
    {
        $musique = $repo->find($id);
        $json = $serializer->serialize($musique, 'json', ['groups' => "musiques"]);

        return new Response($json);
    }

    #[Route('/new', name: 'musique_api_new')]
    public function addmusique(musiqueRepository $repo, SerializerInterface $serializer,Request $request)
    {
        $musique = new Musique();
        $musique->setNom($request->get('nom'));
        $musique->setDateCreation($request->get('dateCreation'));
        $musique->setIdUser($request->get('idUser'));
        $repo->save($musique, true);
        $json = $serializer->serialize($musique, 'json', ['groups' => "musiques"]);

        return new Response($json);
    }

    #[Route('/update/{id}', name: 'musique_api_new')]
    public function updatemusique(musiqueRepository $repo, SerializerInterface $serializer,Request $request, $id)
    {
        $musique = $repo->find($id);
        $musique->setNom($request->get('nom'));
        $musique->setDateCreation($request->get('dateCreation'));
        $musique->setIdUser($request->get('idUser'));
        $repo->save($musique, true);
        $json = $serializer->serialize($musique, 'json', ['groups' => "musiques"]);

        return new Response($json);
    }

    #[Route('/delete/{id}', name: 'musique_api_new')]
    public function deletemusique(musiqueRepository $repo, SerializerInterface $serializer,Request $request, $id)
    {
        $musique = $repo->find($id);
        $repo->remove($musique, true);
        $json = $serializer->serialize($musique, 'json', ['groups' => "musiques"]);

        return new Response($json);
    }
}

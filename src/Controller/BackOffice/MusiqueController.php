<?php

namespace App\Controller\BackOffice;

use App\Entity\Musique;
use App\Form\MusiqueType;
use App\Repository\MusiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backOffice/musique')]
class MusiqueController extends AbstractController
{
    #[Route('/', name: 'app_back_office_musique_index', methods: ['GET'])]
    public function index(MusiqueRepository $musiqueRepository): Response
    {
        return $this->render('back_office/musique/index.html.twig', [
            'musiques' => $musiqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_office_musique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MusiqueRepository $musiqueRepository): Response
    {
        $musique = new Musique();
        $form = $this->createForm(MusiqueType::class, $musique);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $audioFile = $form->get('audioFile')->getData();
            $form->get('chemin')->setData('C:/uploadedFiles/Music/userid/filename');
            // $musiqueRepository->save($musique, true);
            // return $this->redirectToRoute('app_back_office_musique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/musique/new.html.twig', [
            'musique' => $musique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_office_musique_show', methods: ['GET'])]
    public function show(Musique $musique): Response
    {
        return $this->render('back_office/musique/show.html.twig', [
            'musique' => $musique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_office_musique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Musique $musique, MusiqueRepository $musiqueRepository): Response
    {
        $form = $this->createForm(MusiqueType::class, $musique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $musiqueRepository->save($musique, true);

            return $this->redirectToRoute('app_back_office_musique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/musique/edit.html.twig', [
            'musique' => $musique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_office_musique_delete', methods: ['POST'])]
    public function delete(Request $request, Musique $musique, MusiqueRepository $musiqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$musique->getId(), $request->request->get('_token'))) {
            $musiqueRepository->remove($musique, true);
        }

        return $this->redirectToRoute('app_back_office_musique_index', [], Response::HTTP_SEE_OTHER);
    }
}

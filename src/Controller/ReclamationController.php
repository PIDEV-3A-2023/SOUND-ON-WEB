<?php

namespace App\Controller;

use App\Entity\Pictures;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Entity\Utilisateur;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/user/{id}', name: 'app_reclamation_index_user', methods: ['GET'])]
    public function indexUser(ReclamationRepository $reclamationRepository,Utilisateur $utilisateur): Response
    {
        return $this->render('reclamation/indexUser.html.twig', [
            'reclamations' => $reclamationRepository->findByUtilisateur($utilisateur->getId()),
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository,UtilisateurRepository  $utilisateurRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image=$form->get('image')->getData();
            $fichier=md5(uniqid()).'.'.$image->guessExtension();
            $image->move(
              $this->getParameter('image_directory'),
              $fichier
                );
            $reclamation->setImage($fichier);
            $reclamation->setDateCreation(new \DateTime());
            $reclamation->setDateUpdate(null);
            $reclamation->setIdUser($utilisateurRepository->find(1));
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index_user', ['id'=> 1], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET', 'POST'])]
    public function show(Request $request,Reclamation $reclamation,ReponseRepository  $reponseRepository, ReclamationRepository $reclamationRepository,UtilisateurRepository  $utilisateurRepository): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setDateCreation(new \DateTime());
            $reponse->setDateUpdate(null);
            $reponse->setIdReclamation($reclamationRepository->find($reclamation->getId()));
            $reponse->setIdUser($utilisateurRepository->find(1));
            $reponseRepository->save($reponse, true);

            return $this->redirectToRoute('app_reclamation_show', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,'reponses' => $reponseRepository->findByReclamtionByUser($reclamation->getId(),1),'form'=>$form
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setDateUpdate(new \DateTime());
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}

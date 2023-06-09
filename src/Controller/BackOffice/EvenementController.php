<?php

namespace App\Controller\BackOffice;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/office/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/nosevenements', name: 'app_evenement_indexf', methods: ['GET'])]//fonction pour affichier les events dans la partie front
    public function indexf(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        return $this->render('back_office/evenement/indexf.html.twig', [
            'evenements' => $evenements,
        ]);
    }
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]//fonction pour affichier les events dans la partie back
    public function index(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        return $this->render('back_office/evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }
   

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]//fonction pour ajouter un event
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();//3malna instance mel Evenement 
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          /////code image
          
          $file = $form->get('photo')->getData();
          $filename = md5(uniqid()).'.'.$file->guessExtension();
          $file->move($this->getParameter('uploadsEvenement'),$filename);
          $evenement->setPhoto($filename);
          /////
          $entityManager->persist($evenement);
          $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]//fonction pour modifier un events 
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();
                        $filename = md5(uniqid()).'.'.$file->guessExtension();
                        $file->move($this->getParameter('uploadsEvenement'),$filename);
                        $evenement->setPhoto($filename);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]//fonction pour supprimer un event
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}

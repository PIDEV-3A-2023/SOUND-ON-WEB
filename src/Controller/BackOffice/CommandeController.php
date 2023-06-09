<?php

namespace App\Controller\BackOffice;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/office/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_back_office_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('back_office/commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_office_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeRepository $commandeRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_back_office_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_office_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('back_office/commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_office_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_back_office_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_office_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande, true);
        }

        return $this->redirectToRoute('app_back_office_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\QrcodeService;
use App\Services\MailerService;

#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/', name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(QrcodeService $qrcodeService,ReclamationRepository $reclamationRepository,Request $request,MailerService $mailerService, ReponseRepository $reponseRepository,Reclamation $reclamation,UtilisateurRepository  $utilisateurRepository): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat("Traité");
            $reclamationRepository->save($reclamation, true);
            $reponse->setIdReclamation($reclamation);
            $reponse->setDateCreation(new \DateTime);
            $reponse->setIdUser($utilisateurRepository->find(1));
            $reponseRepository->save($reponse, true);
        $content = 'La reponse est :'.$reponse->getMessage();
        $encoding = 'ISO-8859-1';
        $utf8_content = mb_convert_encoding($content, 'UTF-8', $encoding);
        $qrCode = $qrcodeService->qrcode($utf8_content);
         $mailerService->send(
            "Reclamation Traité",
            "hazzastyle25@gmail.com",
            "hazzastyle25@gmail.com",
            "reclamation/reponse.html.twig",
            [
                "name"=>$reclamation->getDescription(),
            ],
            $qrCode
        );


            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setDateUpdate(new \DateTime());
            $reponseRepository->save($reponse, true);

            return $this->redirectToRoute('app_reclamation_show', ['id' => $reponse->getIdReclamation()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $reponseRepository->remove($reponse, true);
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
}

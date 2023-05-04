<?php

namespace App\Controller;

use App\Entity\Pictures;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Entity\Utilisateur;
use App\Form\DateRangeType;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;


#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET','POST'])]
    public function index(Request $request,ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(DateRangeType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $type = $data['type'];
            $etat = $data['etat'];

            $e=$reclamationRepository->findDate($type,$etat);
            return $this->render('reclamation/index.html.twig', [
                'reclamations' => $e,'form' => $form->createView(),
            ]);

        }
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{id}', name: 'app_reclamation_index_user', methods: ['GET','POST'])]
    public function indexUser(Request $request,ReclamationRepository $reclamationRepository,Utilisateur $utilisateur): Response
    {
        $form = $this->createForm(DateRangeType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $start = $data['start'];
            $end = $data['end'];

            $e=$reclamationRepository->findDate($start,$end);
            return $this->render('reclamation/indexUser.html.twig', [
                'reclamations' => $e,'form' => $form->createView(),
            ]);

        }
        return $this->render('reclamation/indexUser.html.twig', [
            'reclamations' => $reclamationRepository->findByUtilisateur($utilisateur->getId()),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new/{isBad}', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(int $isBad ,Request $request, ReclamationRepository $reclamationRepository,UtilisateurRepository  $utilisateurRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        if($isBad==1){
            $Bad=true;
        }else{
            $Bad=false;
        }
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
            $reclamation->setEtat("En Cours");
            $reclamation->setIdUser($utilisateurRepository->find(1));
            //filter bad words 
            $description = $reclamation->getDescription();
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', 'https://neutrinoapi.net/bad-word-filter', [
                'query' => [
                    'content' => $description
                ],
                'headers' => [
                    'User-ID' => '102030', 
                    'API-Key' => 'hIeU9MBtLMvLfaVrYfkQH9V7349W09OdCnxlrHBQdISKhJGA',
                ]
            ]);
            

            
            if ($response->getStatusCode() === 200) 
            {
                $result = $response->toArray();
                if ($result['is-bad']) {
                    // Handle bad word found
                    $this->addFlash('danger', '</i>Your comment contains inappropriate language and cannot be posted.');
                    return $this->redirectToRoute('bad_word');
                } else {
                    
            $reclamationRepository->save($reclamation, true);
                    
                    return $this->redirectToRoute('app_reclamation_index_user', ['id'=> 1], Response::HTTP_SEE_OTHER);
                }
            } 
            
            else{
                
                return new Response("Error processing request", Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'is_bad' => $Bad
        ]);
    }
    #[Route('/show/{id}', name: 'app_reclamation_show', methods: ['GET', 'POST'])]
    public function show(Request $request,Reclamation $reclamation,ReponseRepository  $reponseRepository, ReclamationRepository $reclamationRepository,UtilisateurRepository  $utilisateurRepository): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat("En Cours");
            $reclamationRepository->save($reclamation, true);
            $reponse->setDateCreation(new \DateTime());
            $reponse->setDateUpdate(null);
            $reponse->setIdReclamation($reclamationRepository->find($reclamation->getId()));
            $reponse->setIdUser($utilisateurRepository->find(1));
            $reponseRepository->save($reponse, true);

            return $this->redirectToRoute('app_reclamation_show', ['id'=>$reclamation->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,'reponses' => $reponseRepository->findByReclamtionByUser($reclamation->getId(),1),'form'=>$form->createView()
        ]);
    }

    #[Route('/edit/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
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

    #[Route('etat/{id}/edit', name: 'app_reponse_edit_etat', methods: ['GET', 'POST'])]
    public function editEtat(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {

            $reclamation->setEtat("Fermé");
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);


    }

    #[Route('/delete/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/bad_word', name: 'bad_word')]

    function Affiche_bad(ReclamationRepository $repository){
        $reclamation= $repository->findAll();
        return $this->redirectToRoute('app_reclamation_new', ['isBad'=>1], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search/', name: 'app_reclamation_ajax', methods: ['GET'])]
    public function searchajax(Request $request ,ReclamationRepository $PartRepository)
    {
        $form = $this->createForm(DateRangeType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $type = $data['type'];
            $etat = $data['etat'];

            $e=$PartRepository->findDate($type,$etat);
            return $this->render('reclamation/index.html.twig', [
                'reclamations' => $e,'form' => $form->createView(),
            ]);

        }
        $requestString=$request->get('searchValue');
        $jeux = $PartRepository->findReclamationByDescription($requestString);

        return $this->render('reclamation/ajax.html.twig', [
            "reclamations"=>$jeux,'form' => $form->createView(),
        ]);
    }
}


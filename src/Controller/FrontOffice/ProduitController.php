<?php

namespace App\Controller\FrontOffice;

use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Entity\Produit;
use App\Entity\Reponse;
use App\Entity\Utilisateur;
use App\Form\ProduitType;
use App\Repository\CommandeRepository;
use App\Repository\DetailCommandeRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use App\Service\mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/FrontOffice/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_front_office_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('front_office/produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_front_office_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);
            

            return $this->redirectToRoute('app_front_office_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front_office/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    

    #[Route('/commandeproduit/{id}', name: 'app_front_office_produit_commande')]
    public function ajouterCommande(mail $mail,NotifierInterface $notifier,SessionInterface $session,DetailCommandeRepository $detailCommandeRepository, UtilisateurRepository $utilisateurRepository, ProduitRepository $produitRepository,CommandeRepository $commandeRepository, Produit $produit)
    {
        
        if($produit -> getQuantite() != 0){
            $to='marwen.mamlouk@esprit.tn';
        $utilisateur = $utilisateurRepository -> find(1);
       /* $sms = new SmsMessage (// the phone number to send the SMS message to
            '+21620300934',
            
            // the message
            'A new login was detected!',
            // optionally, you can override default "from" defined in transports
        );*/
        $commande = new Commande();
        $commande -> setIdUser($utilisateur);
        $commande -> setDateCommande(new \DateTime());
        $commande -> setTotale($produit -> getPrix());
        $s=$produit->getLibelle();
        $x=$produit->getId();
        $v=$produit->getPrix();
        $p=$commande->getId();
        $post='Nous vous remercions pour votre achat ... 
        votre produit:    '.$x.'      '.$s.' avec un prix: '.$v.'$   ';

        if ($produit -> getQuantite() > 0){
            
            $produit->setQuantite($produit -> getQuantite() - 1);
            $produitRepository->save($produit, true);
            $notifier->send(new Notification('Produit ajouté avec succés ', ['browser']));
            $mail->sendEmail($post,$to);
        }
        $commandeRepository -> save($commande, true);
        $detailCommande = new DetailCommande();
        $detailCommande -> setIdProduit($produit );
        $detailCommande ->setIdCommande($commande );
        $detailCommande -> setQuantite(1);
        $detailCommandeRepository->save($detailCommande, true);
        

        
    }
    $cart = $session->get('cart',[]);
    unset($cart[$produit -> getId()]);
    $cart = array_values($cart);
    $session->set('cart',$cart);
    //$sentMessage = $texter->send($sms);
        return $this->redirectToRoute('app_front_office_produit_index', [], Response::HTTP_SEE_OTHER);

    }



    #[Route('/{id}', name: 'app_front_office_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('front_office/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_front_office_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_front_office_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front_office/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_front_office_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_front_office_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}

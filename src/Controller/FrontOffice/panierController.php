<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use App\Repository\ProduitRepository;
class panierController extends AbstractController
{
    #[Route('/front/office/panier', name: 'app_front_office_panier')]
    public function index(SessionInterface $session): Response
    {
        /*return $this->render('front_office/panier/index.html.twig', [
            'controller_name' => 'panierController',
        ]);*/
        $cart = $session->get('cart',[]);
        return $this->render('front_office/panier/index.html.twig', [
            'produits' => $cart,
        ]);
    }
    #[Route('/front/office/panier/cart', name: 'app_front_office_panier_cart')]
    public function panier_cart(SessionInterface $session): Response
    {
        $cart = $session->get('cart',[]);
        return $this->render('front_office/panier/cart.html.twig', [
            'cart' => sizeof($cart),
        ]);
    }
    #[Route('/front/office/panier/cart_add/{id}', name: 'app_front_office_panier_cart_add')]
    public function panier_add($id,SessionInterface $session,ProduitRepository $produitRepository): Response
    {
        $cart = $session->get('cart',[]);
        $product = $produitRepository->findByID($id);
        //$cart->array_push()
        array_push($cart,$product);
        $session->set('cart',$cart);
        
       return $this->redirectToRoute('app_front_office_produit_index');
    }
    #[Route('/front/office/panier/cart_delete/{id}', name: 'app_front_office_panier_cart_remove')]
    public function panier_delete($id,SessionInterface $session,ProduitRepository $produitRepository): Response
    {
        
        $cart = $session->get('cart',[]);
        unset($cart[$id]);
        $cart = array_values($cart);
        $session->set('cart',$cart);
        
       return $this->redirectToRoute('app_front_office_panier');
    }
}

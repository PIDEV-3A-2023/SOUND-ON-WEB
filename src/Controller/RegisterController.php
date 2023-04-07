<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{


    private $entiyManager;

    public function __construct(EntityManagerInterface $entityManager) {

        $this->entiyManager = $entityManager;
    }
    


    #[Route('/inscription', name: 'register')]
    public function index(Request $request , UserPasswordEncoderInterface $encoder)
    {
        $Utilisateur = new Utilisateur();
        $form = $this->createForm(RegisterType::class,$Utilisateur);

    $form->handleRequest($request);
    if ($form-> isSubmitted() && $form->isValid()) {
        $Utilisateur = $form->getData();

        $password = $encoder->encodePassword($Utilisateur,$Utilisateur->getPassword());
        $Utilisateur->setPAssword($password);
        
        $this->entiyManager->persist($Utilisateur);
        $this->entiyManager->flush();
    }

        return $this->render('register/index.html.twig', [
            'form'=> $form->createView()
        ]);
    }
}

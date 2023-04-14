<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/backOffice', name: 'app_back_office_admin_dashboard')]
    public function index(): Response
    {
        return $this->render('back_office/admin_dashboard/dashboard.html.twig', [
            'controller_name' => 'AdminDashboardController',
        ]);
    }
}

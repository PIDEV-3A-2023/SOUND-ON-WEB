<?php

namespace App\Controller\BackOffice;

use App\Repository\CategorieRepository;
use App\Repository\MusiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class AdminDashboardController extends AbstractController
{
    #[Route('/backOffice', name: 'app_back_office_admin_dashboard')]
    public function index(ChartBuilderInterface $chartBuilder, CategorieRepository $categRepo, MusiqueRepository $musicRepo): Response
    {
        $genres = $categRepo->findAll();
        $genreNames = array();
        $dataArray = array();
        foreach($genres as $category) {
            $genreNames[] = $category->getNom();
            $dataArray[] = $musicRepo->countByCategory($category->getId());
        }
        $chart = $chartBuilder->createChart((Chart::TYPE_DOUGHNUT));

        $chart->setData([
            'labels' => $genreNames,
            'datasets' => [
                [
                    'label' => 'Statistiques des chansons par genre',
                    'backgroundColor' => ['rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'],
                    'data' => [1, 10, 5],
                ],
            ],
        ]);

        return $this->render('back_office/admin_dashboard/dashboard.html.twig', [
            'chart' => $chart,
        ]);
    }


}

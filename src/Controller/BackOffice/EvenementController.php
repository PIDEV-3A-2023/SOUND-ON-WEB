<?php

namespace App\Controller\BackOffice;
use App\Entity\PdfGeneratorService;
use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UtilisateurRepository;
use App\Service\MailerService; 
use Symfony\Component\Mime\Email; 
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;

use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;


#[Route('/back/office/evenement')]
class EvenementController extends AbstractController
{
    
    #[Route('/statistique', name: 'stats')]
    public function stat()
        {
    
            $repository = $this->getDoctrine()->getRepository(Evenement::class);//bch yjib les evenements lkol eli 3andou fel base bel findAll
    $evenements = $repository->findAll();
    $em = $this->getDoctrine()->getManager();
    $mostReserved = null;
foreach ($evenements as $evenement) //houwa 3andou liste mte3 des evenemnts bch yaamel boucle 3lihom 
{
    $reservations = $evenement->getReservations();//3ala kol evenement bch yekhou les reservations eli fih bel getReservations
    $num_reservations = count($reservations);//bch ye7seb 9adech 3andou mn reservation bel count
    if (!$evenement->isDiscountApplied() && $num_reservations > 1 && (!$mostReserved || $evenement->getPrix() > $mostReserved->getPrix())) 
    // bch ychouf est ce que levent sar 3liha remise wela lee  , idha  ken fih au moins 1 reservation , idha ken houwa akther we7ed fih reservations
    {
        $mostReserved = $evenement;
    }
}
if ($mostReserved) {
    $prix = $mostReserved->getPrix() * 0.8;// houni bch tssir remise 
    $mostReserved->setPrix($prix);
    $mostReserved->setDiscountApplied(True);
    $em->flush();
}

    $em = $this->getDoctrine()->getManager();

    $data = array();
    $total=0;
    foreach ($evenements as $evenement) {
        $reservations = $evenement->getReservations();
        $num_reservations = count($reservations);
       //$total=$total+$num_reservations*$evenement->getPrix();
       
        $data[] = [$evenement->getTitre(), $num_reservations];
    }
    //dd($total);
    $pr1 = 0;
    $pr2 = 0;


    foreach ($evenements as $evenement) {
        if ($evenement->getPrix() >= 100)  :

            $pr1 += 1;
        else:

            $pr2 += 1;

        endif;

    }



    $pieChart = new PieChart();
    $pieChart->getData()->setArrayToDataTable(
        array_merge([['Titre', 'Nombre de réservations']], $data)
    );
    $pieChart->getOptions()->setTitle('Statistiques sur les réservations');
    $pieChart->getOptions()->setHeight(1000);
    $pieChart->getOptions()->setWidth(1400);
    $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
    $pieChart->getOptions()->getTitleTextStyle()->setColor('green');
    $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
    $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
    $pieChart->getOptions()->getTitleTextStyle()->setFontSize(30);

    $pieChart1 = new PieChart();
    $pieChart1->getData()->setArrayToDataTable(
        [['Prix', 'Nom'],
            ['evenement inferieur 100dt ', $pr2],
            ['evenement superieur ou egale 100dt', $pr1],
        ]
    );
    $pieChart1->getOptions()->setTitle('statistique a partir des prix');
    $pieChart1->getOptions()->setHeight(1000);
    $pieChart1->getOptions()->setWidth(1400);
    $pieChart1->getOptions()->getTitleTextStyle()->setBold(true);
    $pieChart1->getOptions()->getTitleTextStyle()->setColor('green');
    $pieChart1->getOptions()->getTitleTextStyle()->setItalic(true);
    $pieChart1->getOptions()->getTitleTextStyle()->setFontName('Arial');
    $pieChart1->getOptions()->getTitleTextStyle()->setFontSize(30);



    return $this->render('stats/stat.html.twig', array('piechart' => $pieChart,'piechart2'=>$pieChart1,'total'=>$total));
        }
    #[Route('/nosevenements/{page?1}/{nbre?3}', name: 'app_evenement_indexf', methods: ['GET'])]
    public function indexf(EntityManagerInterface $entityManager,$page,$nbre,ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Evenement::class);
        $nbevenement = $repository->count([]);
        // 24
        $nbrePage = ceil($nbevenement / $nbre) ;

        $evenements = $repository->findBy([], [],$nbre, (intval($page) - 1 ) * $nbre);

        return $this->render('back_office/evenement/indexf.html.twig', [
            'evenements' => $evenements,
            'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('e')
            ->from(Evenement::class, 'e');

        // Basic search by username or nbvotes
        $searchQuery = $request->query->get('search');
        if ($searchQuery) {
            $queryBuilder->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('e.titre', ':searchQuery'),
                $queryBuilder->expr()->eq('e.dateEvenement', ':searchQuery'),
                $queryBuilder->expr()->eq('e.adresse', ':searchQuery'),
                $queryBuilder->expr()->eq('e.id', ':searchQuery'),

            ))
            ->setParameter('searchQuery', $searchQuery);
        }

        // Sorting
        $sort = $request->query->get('sort');
        if ($sort) {
            $queryBuilder->orderBy('e.' . $sort, 'ASC');
        }

        $evenements = $queryBuilder->getQuery()->getResult();

        return $this->render('back_office/evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }
   

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UtilisateurRepository $userrepo,MailerService $mailer,NotifierInterface $notifier ): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          /////code image
          
          $file = $form->get('photo')->getData();
          $filename = md5(uniqid()).'.'.$file->guessExtension();
          $file->move($this->getParameter('uploads'),$filename);
          $evenement->setPhoto($filename);
          /////
          $entityManager->persist($evenement);
          $titre = $form->get('titre')->getData();
            
            
          $evenements = $entityManager
          ->getRepository(Evenement::class)
          ->findBy(['titre'=>$titre]);
         if (empty($evenements)) 
         {
          $entityManager->flush();
          $clients=$userrepo->findByClientRole(); 
          foreach($clients as $client)
          {
              $to=$client->getEmail();
              //$prenom=$client->getNom();
              $subject="Nouvel Evenement";
            //$titre=  $evenement->getTitre();
            //$adresse=$evenement->getAdresse();
            //$date=$evenement->getDateEvenement();
            //$text = "Chers Clients ".$prenom." Notre équipe SOUND ON vous informe qu'on a un nouvel evenement  le ".$date->format('d/m/Y')." à ".$adresse;

              $twig = $this->container->get('twig');
              $html=$twig->render('email/email.html.twig',['evenement'=>$evenement]);
          
          $mailer->sendEmail($to,$subject,$html);
          }
        
           $notifier->send(new Notification('Evenement ajouté avec succés ', ['browser']));
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }
        else 
        {
            $notifier->send(new Notification('Evenement existe déja ', ['browser']));
            return $this->redirectToRoute('app_evenement_new', [], Response::HTTP_SEE_OTHER);
        }
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

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();
                        $filename = md5(uniqid()).'.'.$file->guessExtension();
                        $file->move($this->getParameter('uploads'),$filename);
                        $evenement->setPhoto($filename);
            $entityManager->flush();
            $notifier->send(new Notification('Evenement modifié avec succés ', ['browser']));

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }
        $notifier->send(new Notification('Evenement supprimé avec succés ', ['browser']));

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/pdf/evenement', name: 'generator_service')]
    public function pdfEvenement(): Response
    { 
        $evenement= $this->getDoctrine()
        ->getRepository(Evenement::class)
        ->findAll();

   

        $html =$this->renderView('pdf/index.html.twig', ['evenement' => $evenement]);
        $pdfGeneratorService=new PdfGeneratorService();
        $pdf = $pdfGeneratorService->generatePdf($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);
       
    }
    #[Route('/show_in_map/{idEvenement}', name: 'app_evenement_map', methods: ['GET'])]
    public function Map( Evenement $idEvenement,EntityManagerInterface $entityManager ): Response
    {

        $evenement = $entityManager
            ->getRepository(Evenement::class)->findBy( 
                ['id'=>$idEvenement ]
            );
        return $this->render('map/api_arcgis.html.twig', [
            'evenement' => $evenement,
        ]);
    }
    #[Route('/dislike/{id}', name: 'dislike_evenement')]
public function dislike(Request $request, Evenement $evenement)
{
    $evenement->setLikes($evenement->getLikes() - 1);
    $this->getDoctrine()->getManager()->flush();

    $likeCount = $evenement->getLikes();

    return $this->json([
        'likeCount' => $likeCount,]);
}

#[Route('/like/{id}', name: 'like_evenement')]
public function like(Request $request, Evenement $evenement)
{
    $evenement->setLikes($evenement->getLikes() + 1);
    $this->getDoctrine()->getManager()->flush();

    $likeCount = $evenement->getLikes();

    return $this->json([
        'likeCount' => $likeCount,]);
}
 

}

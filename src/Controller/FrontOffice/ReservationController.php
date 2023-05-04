<?php

namespace App\Controller\FrontOffice;

use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Entity\Evenement;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/voirticket/{id}/{idUser}', name: 'app_reservation_voirticket', methods: ['GET', 'POST'])]
public function voirticket(String $id,String $idUser)
{
    
    $qrImageData = $this->qrcoding('http://127.0.0.1:8000/reservation/bot/' . $id . '/' . $idUser);
    return $this->render('front_office/reservation/voirticket.html.twig',['qr_code'=> $qrImageData]);

    
}
    #[Route('/lesreservations', name: 'app_reservation_index1', methods: ['GET'])]
    public function index1(ReservationRepository $reservationRepository): Response
    {
        return $this->render('front_office/reservation/indexb.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }
    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('front_office/reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('front_office/reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }
   
    #[Route('/{id}/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository,Evenement $id): Response
    {
        $reservation = new Reservation();
        $reservation->setIdEvenement($id);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        
        
       

        if ($form->isSubmitted() && $form->isValid()) {
            $iduser=$form->get('idUser')->getData();
            
           
            $reservation-> setDate (new \DateTime());
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front_office/reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            

        ]);
    }
   

  

  

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front_office/reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
    public function qrcoding($data)
   {$writer = new PngWriter();
    $qrCode = QrCode::create($data)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
        ->setSize(500)
        ->setMargin(0)
        ->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));
    $logo = Logo::create('uploads/evenements/logo.jpg')
        ->setResizeToWidth(90);
    $label = Label::create('')->setFont(new NotoSans(8));

    $qrCodes = [];
    $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
    $qrCodes['simple'] = $writer->write(
                            $qrCode,
                            null,
                            $label->setText('Simple')
                        )->getDataUri();

    $qrCode->setForegroundColor(new Color(255, 0, 0));
    $qrCodes['changeColor'] = $writer->write(
        $qrCode,
        null,
        $label->setText('Color Change')
    )->getDataUri();

    $qrCode->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 0, 0));
    $qrCodes['changeBgColor'] = $writer->write(
        $qrCode,
        null,
        $label->setText('Background Color Change')
    )->getDataUri();

    $qrCode->setSize(200)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
    $qrCodes['withImage'] = $writer->write(
        $qrCode,
        $logo,
        $label->setText('SOUND ON')->setFont(new NotoSans(20))
    )->getDataUri();
    return $qrCodes;
}
#[Route('/bot/{idEvent}/{idUser}', name: 'app_reservation_redirect', methods: ['GET', 'POST'])]
     public function redirectToform(Request $request, EntityManagerInterface $entityManager, $idEvent,$idUser): Response
     {
        return  $this->redirectToRoute('app_participation_ticket', [
            'idEvent' => $idEvent,
            'idUser'=>$idUser
        ], Response::HTTP_SEE_OTHER);
     }
     #[Route('/ticket/{idEvent}/{idUser}', name: 'app_participation_ticket', methods: ['GET'])]
    public function showTicket(EntityManagerInterface $entityManager,$idEvent,String $idUser): Response
    {
        $event=$entityManager->getRepository(Evenement::class)->findOneBy(['titre'=>$idEvent]);
        $user=$entityManager->getRepository(Utilisateur::class)->findOneBy(['prenom'=>$idUser]);
        
        $fullName = $user->getNom() . ' ' . $user->getPrenom();
        $email = $user->getEmail();
        $qrImageData = $this->qrcoding('User: ' . $fullName . ' / ' . $email);
           return $this->render('front_office/reservation/ticket.html.twig', [
               
               'event' => $event,
               'qr_code'=> $qrImageData,
           ]);
    
}


}

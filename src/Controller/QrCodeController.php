<?php

namespace App\Controller;
use App\Entity\Produit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class QrCodeController extends AbstractController
{
    #[Route('/qr-codes/{id}', name: 'app_qr_codes', methods: ['GET'])]
public function index(int $id,UrlGeneratorInterface $urlGenerator): Response
{
    $writer = new PngWriter();
    
    // Get the event data from the database using the ID parameter
    $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);

    // Create the QR code with the event data
    $qrCode = QrCode::create(
        sprintf(
            "ID: %d\nPrix: %d\nQuantite: %d\nLibelle: %s\nType: %s\nDescription: %s\nRESERVATION_URL: %s",
            $produit->getId(),
            $produit->getPrix(),
            $produit->getQuantite(),
            $produit->getLibelle(),
            $produit->getType(),
            $produit->getDescription(),

            $reservationUrl = $urlGenerator->generate('app_front_office_produit_commande', ['id'=>$produit->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
        )
    )->setEncoding(new Encoding('UTF-8'))
     ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
     ->setSize(120)
     ->setMargin(0)
     ->setForegroundColor(new Color(0, 0, 0))
     ->setBackgroundColor(new Color(255, 255, 255));
    
    // Add logo and label to the QR code
    $logo = Logo::create('img.png')->setResizeToWidth(60);
    $label = Label::create('')->setFont(new NotoSans(8));
 
    // Generate QR codes with different styles
    $qrCodes = [];
    $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
    $qrCodes['simple'] = $writer->write($qrCode, null, $label->setText('Simple'))->getDataUri();
    $qrCode->setForegroundColor(new Color(255, 0, 0));
    $qrCodes['changeColor'] = $writer->write($qrCode, null, $label->setText('Color Change'))->getDataUri();
    $qrCode->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 0, 0));
    $qrCodes['changeBgColor'] = $writer->write($qrCode, null, $label->setText('Background Color Change'))->getDataUri();
    $qrCode->setSize(200)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
    $qrCodes['withImage'] = $writer->write($qrCode, $logo, $label->setText('With Image')->setFont(new NotoSans(20)))->getDataUri();
    $reservationUrl = $urlGenerator->generate('app_front_office_produit_commande', ['id'=>$produit->getId()]);
    return $this->render('qr_code/index.html.twig', array_merge($qrCodes,['reservationUrl' => null]));
}

}
?>
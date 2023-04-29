<?php

namespace App\Controller\BackOffice;

use App\Entity\PdfGeneratorService;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/backOffice/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_back_office_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository,Request $request,EntityManagerInterface $entityManager): Response
    {
        
        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('e')
            ->from(Produit::class, 'e');

        

        // Sorting
        $sort = $request->query->get('sort');
        if ($sort) {
            $queryBuilder->orderBy('e.' . $sort, 'ASC');
        }

        $produits = $queryBuilder->getQuery()->getResult();
        return $this->render('back_office/produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/new', name: 'app_back_office_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository,SluggerInterface $slugger): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setImage($newFilename);
            }
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_back_office_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_office_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('back_office/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_office_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cv = $form->get('image')->getData();
            $fichier = md5(uniqid()).'.'.$cv->guessExtension();
            $cv->move(
                $this->getParameter('uploads'),
                $fichier);
                $produit->setImage($fichier);

            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_back_office_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_office_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_back_office_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/evenement', name: 'generator_service')]
    public function pdfEvenement(): Response
    { 
        $produit= $this->getDoctrine()
        ->getRepository(Produit::class)
        ->findAll();

   

        $html =$this->renderView('pdf/index.html.twig', ['produit' => $produit]);
        $pdfGeneratorService=new PdfGeneratorService();
        $pdf = $pdfGeneratorService->generatePdf($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);
       
    }
}

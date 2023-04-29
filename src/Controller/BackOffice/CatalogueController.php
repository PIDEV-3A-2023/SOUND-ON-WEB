<?php

namespace App\Controller\BackOffice;

use App\Entity\Catalogue;
use App\Form\CatalogueType;
use App\Repository\CatalogueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/office/catalogue')]
class CatalogueController extends AbstractController
{
    #[Route('/', name: 'app_back_office_catalogue_index', methods: ['GET'])]
    public function index(CatalogueRepository $catalogueRepository): Response
    {
        return $this->render('back_office/catalogue/index.html.twig', [
            'catalogues' => $catalogueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_office_catalogue_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CatalogueRepository $catalogueRepository): Response
    {
        $catalogue = new Catalogue();
        $form = $this->createForm(CatalogueType::class, $catalogue);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            $destination = 'C:/uploadedFiles/Images/';
            $originalFileName = pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);
            $fileName = $originalFileName.'-'.uniqid().'.'.$image->guessExtension();
            $image->move($destination, $fileName);
            $catalogue->setImage('C:/uploadedFiles/Images/'.$fileName);
            $catalogueRepository->save($catalogue, true);
            return $this->redirectToRoute('app_back_office_catalogue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/catalogue/new.html.twig', [
            'catalogue' => $catalogue,
            'form' => $form,
        ]);
    } 
    #[Route('/{id}', name: 'app_back_office_catalogue_show', methods: ['GET'])]
    public function show(Catalogue $catalogue): Response
    {
        return $this->render('back_office/catalogue/show.html.twig', [
            'catalogue' => $catalogue,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_back_office_catalogue_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Catalogue $catalogue, CatalogueRepository $catalogueRepository): Response
    {
        $form = $this->createForm(CatalogueType::class, $catalogue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if($image){
            $destination = 'C:/uploadedFiles/Images/';
            $originalFileName = pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);
            $fileName = $originalFileName.'-'.uniqid().'.'.$image->guessExtension();
            $image->move($destination, $fileName);
            $catalogue->setImage('C:/uploadedFiles/Images/'.$fileName);}
            $catalogueRepository->save($catalogue, true);
            return $this->redirectToRoute('app_back_office_catalogue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/catalogue/edit.html.twig', [
            'catalogue' => $catalogue,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_office_catalogue_delete', methods: ['POST'])]
    public function delete(Request $request, Catalogue $catalogue, CatalogueRepository $catalogueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$catalogue->getId(), $request->request->get('_token'))) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($catalogue->getImage());
            $catalogueRepository->remove($catalogue, true);
        }

        return $this->redirectToRoute('app_back_office_catalogue_index', [], Response::HTTP_SEE_OTHER);
    }
}

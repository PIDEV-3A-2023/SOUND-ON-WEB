<?php

namespace App\Controller\BackOffice;

use App\Entity\Musique;
use App\Form\MusiqueType;
use App\Repository\MusiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backOffice/musique')]
class MusiqueController extends AbstractController
{
    function getDuration($file){
        if (file_exists($file)){
            ## open and read video file
            $handle = fopen($file, "r");

            ## read video file size
            $contents = fread($handle, filesize($file));
            fclose($handle);
            $make_hexa = hexdec(bin2hex(substr($contents,strlen($contents)-3)));
            $duration = 0;
            if (strlen($contents) > $make_hexa){
                $pre_duration = hexdec(bin2hex(substr($contents,strlen($contents)-$make_hexa,3))) ;
                $post_duration = $pre_duration/1000;
                $timehours = $post_duration/3600;
                $timeminutes =($post_duration % 3600)/60;
                $timeseconds = ($post_duration % 3600) % 60;
                $timehours = explode(".", $timehours);
                $timeminutes = explode(".", $timeminutes);
                $timeseconds = explode(".", $timeseconds);
                $duration = $timehours[0]. ":" . $timeminutes[0]. ":" . $timeseconds[0];}
                return $duration;
            } else {
                return false;
            }
        }


    #[Route('/', name: 'app_back_office_musique_index', methods: ['GET'])]
    public function index(MusiqueRepository $musiqueRepository): Response
    {
        return $this->render('back_office/musique/index.html.twig', [
            'musiques' => $musiqueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_office_musique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MusiqueRepository $musiqueRepository): Response
    {
        $musique = new Musique();
        $form = $this->createForm(MusiqueType::class, $musique);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $audioFile */
            $audioFile = $form['chemin']->getData();
            $userId = $form->get('idUser')->getData()->getId();
            $destination = 'C:/uploadedFiles/Music/'.$userId.'/';
            $originalFileName = pathinfo($audioFile->getClientOriginalName(),PATHINFO_FILENAME);
            $fileName = $originalFileName.'-'.uniqid().'.'.$audioFile->guessExtension();
            $audioFile->move($destination, $fileName);
            $musique->setChemin('C:/uploadedFiles/Music/'.$userId.'/'.$fileName);
            $musique->setLongueur($this->getDuration('C:/uploadedFiles/Music/'.$userId.'/'.$fileName));
            $musiqueRepository->save($musique, true);
            return $this->redirectToRoute('app_back_office_musique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/musique/new.html.twig', [
            'musique' => $musique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_office_musique_show', methods: ['GET'])]
    public function show(Musique $musique): Response
    {
        return $this->render('back_office/musique/show.html.twig', [
            'musique' => $musique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_office_musique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Musique $musique, MusiqueRepository $musiqueRepository): Response
    {
        $form = $this->createForm(MusiqueType::class, $musique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $musiqueRepository->save($musique, true);

            return $this->redirectToRoute('app_back_office_musique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back_office/musique/edit.html.twig', [
            'musique' => $musique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_office_musique_delete', methods: ['POST'])]
    public function delete(Request $request, Musique $musique, MusiqueRepository $musiqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$musique->getId(), $request->request->get('_token'))) {
            $fileSystem = new Filesystem();
            $fileSystem->remove($musique->getChemin());
            $musiqueRepository->remove($musique, true);
        }

        return $this->redirectToRoute('app_back_office_musique_index', [], Response::HTTP_SEE_OTHER);
    }
}

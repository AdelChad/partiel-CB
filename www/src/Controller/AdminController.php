<?php

namespace App\Controller;

use App\Entity\Fds;
use App\Entity\Product;
use App\Form\FdsType;
use App\Service\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response{
        return $this->render('admin/index.html.twig', [
        ]);
    }

    #[Route('/fds/create', name: 'fds_add')]
    public function createFds(Request $request, FileManager $fm,  EntityManagerInterface $entityManager): Response
    {
        $fds = new Fds();
        $form = $this->createForm(FdsType::class, $fds);

        $form->handleRequest($request);

        $errors = $form->getErrors(true, false);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileImg = $form->get('fileFds')->getData();
            if($fileImg != null){
                $slugger = new AsciiSlugger();
                $slug = $slugger->slug($fds->getTitle());
                $filePath = $fm->upload($fileImg, $slug, '', false);

                $fds->setPath($filePath);
            }
            $fds->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($fds);
            $entityManager->flush();

            $this->addFlash('success', 'Le fds a été ajouté avec succès !');
            return $this->redirectToRoute('app_product_associate_create');
        }
        return $this->render('admin/fds/create-edit.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }

    #[Route('/fds/edit/{id}', name: 'fds_edit')]
    public function editFds(Request $request, FileManager $fm,  EntityManagerInterface $entityManager, Fds $fds): Response
    {
        $form = $this->createForm(FdsType::class, $fds);

        $form->handleRequest($request);

        $errors = $form->getErrors(true, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileImg = $form->get('fileFds')->getData();
            if($fileImg != null){
                $slugger = new AsciiSlugger();
                $slug = $slugger->slug($fds->getTitle());
                $filePath = $fm->upload($fileImg, $slug, '', true);

                $fds->setPath($filePath);
            }
            $fds->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($fds);
            $entityManager->flush();

            $this->addFlash('success', 'Le fds a été ajouté avec succès !');
            return $this->redirectToRoute('app_admin_home');
        }
        return $this->render('admin/fds/create-edit.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors,
            'fds'   => $fds
        ]);
    }

    #[Route('/fds/download/{id}', name: 'fds_download')]
    public function downloadFds($id, FileManager $fm, FdsRepository $fdsRepository): Response
    {
       $fds = $fdsRepository->findOneBy(['id'=>$id]);
       return $fm->download($fds->getPath());
    }
}

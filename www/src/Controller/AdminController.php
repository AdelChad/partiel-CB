<?php

namespace App\Controller;

use App\Entity\Fds;
use App\Entity\Product;
use App\Form\FdsType;
use App\Form\ProductType;
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

    #[Route('/product/create', name: 'product_add')]
    public function createProduct(Request $request, FileManager $fm,  EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        $errors = $form->getErrors(true, false);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileImg = $form->get('fileImg')->getData();
            if($fileImg != null){
                $slugger = new AsciiSlugger();
                $slug = $slugger->slug($product->getTitle());
                $filePath = $fm->upload($fileImg, $slug, '', true);

                $product->setImage($filePath);
            }
            $product->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit ajouté avec succès !');
            return $this->redirectToRoute('app_admin_home');
        }
        return $this->render('admin/product/create-edit.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }

    #[Route('/product/edit/{id}', name: 'product_edit')]
    public function editProduct(Request $request, FileManager $fm,  EntityManagerInterface $entityManager, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        $errors = $form->getErrors(true, false);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileImg = $form->get('fileImg')->getData();

            if($fileImg != null){
                $slugger = new AsciiSlugger();
                $slug = $slugger->slug($product->getTitle());
                $filePath = $fm->upload($fileImg, $slug, '', true);

                $product->setImage($filePath);
            }
            $product->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit modifié avec succès !');
            return $this->redirectToRoute('app_admin_home');
        }
        return $this->render('admin/product/create-edit.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors,
            'product' => $product
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
}

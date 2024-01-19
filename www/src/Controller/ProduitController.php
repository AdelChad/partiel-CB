<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\NotificationRepository;
use App\Repository\ProductRepository;
use App\Service\FileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/product', name:'app_produit_')]
class ProduitController extends AbstractController
{
    #[Route('', name: 'list')]
    public function index(NotificationRepository $notificationRepository, ProductRepository $prodRepo): Response
    {

        if($this->isGranted('ROLE_ADMIN')){
            $prodList = $prodRepo->findAll();
            $user = $this->getUser();
            $notifications = $notificationRepository->findBy(['user' => $user, 'isRead' => false]);
            return $this->render('product/list.html.twig', [
                'prodList' => $prodList,
                'notificationList' => $notifications,
            ]);
        }
        return $this->redirectToRoute('app_home');
    }

    #[Route('/create', name: 'create')]
    public function createProduct(Request $request, FileManager $fm,  EntityManagerInterface $entityManager): Response
    {

        if($this->isGranted('ROLE_ADMIN')){
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
                return $this->redirectToRoute('app_admin_fds_add');
            }
            return $this->render('product/create-edit.html.twig', [
                'form' => $form->createView(),
                'errors' => $errors
            ]);
        }
        return $this->redirectToRoute('app_home');
    }

    #[Route('/edit/{id}', name: 'upload')]
    public function editProduct(Request $request, FileManager $fm,  EntityManagerInterface $entityManager, Product $product): Response
    {

        if($this->isGranted('ROLE_ADMIN')){
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

            return $this->render('product/create-edit.html.twig', [
                'form' => $form->createView(),
                'errors' => $errors,
                'product' => $product
            ]);
        }
        return $this->redirectToRoute('app_home');
    }

    
}

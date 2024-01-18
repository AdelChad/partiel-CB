<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name:'app_produit_')]
class ProduitController extends AbstractController
{
    #[Route('', name: 'list')]
    public function index(ProductRepository $prodRepo): Response
    {
        $prodList = $prodRepo->findAll();

        return $this->render('product/list.html.twig', [
            'prodList' => $prodList,
        ]);
    }
}

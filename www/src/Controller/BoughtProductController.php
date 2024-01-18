<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoughtProductController extends AbstractController
{
    #[Route('/achat/produit', name: 'app_bought_product')]
    public function index(UserRepository $userRepository, ): Response
    {
        //récupérer l'utilisateur connecté
        //$user = $this->getUser();
        $user = $userRepository->find(1);

        //récupérer les produits achetés par l'utilisateur
        $BoughtProducts = $user->getProduct()->toArray();

        return $this->render('bought_product/index.html.twig', [
            'controller_name' => 'BoughtProductController',
            'BoughtProducts' => $BoughtProducts
        ]);
    }
}

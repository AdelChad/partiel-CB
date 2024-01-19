<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoughtProductController extends AbstractController
{
    #[Route('/achat/produit', name: 'app_bought_product')]
    public function index(UserRepository $userRepository, NotificationRepository $notificationRepository ): Response
    {
        $user = $this->getUser();
        $notifications = $notificationRepository->findBy(['user' => $user, 'isRead' => false]);


        if($this->getUser() == null){
            return $this->redirectToRoute('app_login');
        }
        //récupérer l'utilisateur connecté (userInterface)
        $user = $this->getUser();

        //récupérer l'utilisateur connecté
        $user = $userRepository->find($user);

        //récupérer les produits achetés par l'utilisateur
        $BoughtProducts = $user->getProduct()->toArray();

        $BoughtProductsWithFdsPath = [];
        foreach ($BoughtProducts as $product) {
            $fds = $product->getFds();
            if ($fds) {
                $productData = [
                    'id' => $product->getId(),
                    'title' => $product->getTitle(),
                    'description' => $product->getDescription(),
                    'createdAt' => $product->getCreatedAt(),
                    'price' => $product->getPrice(),
                    'updateAt' => $product->getUpdateAt(),
                    'fds' => $product->getFds(),
                    'users' => $product->getUsers(),
                    'image' => $product->getImage(),
                    'fdsPath' => $fds->getPath(),
                ];
                $BoughtProductsWithFdsPath[] = $productData;
            }
        }

        return $this->render('bought_product/index.html.twig', [
            'controller_name' => 'BoughtProductController',
            'BoughtProducts' => $BoughtProductsWithFdsPath,
            'notificationList' => $notifications
        ]);
    }
}

<?php

namespace App\Controller;

use App\Form\UserProductType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProductAssociateController extends AbstractController
{
    #[Route('/product-associate', name: 'app_product_associate')]
    public function index(UserRepository $userRepository): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_home');
        }
        // Récupérer tous les utilisateurs
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            // Vérifier si l'utilisateur possède des produits associés
            if (!$user->getProduct()->isEmpty()) {
                $userId = $user->getId();
                $userEmail = $user->getEmail();

                // Récupérer les produits associés à l'utilisateur
                $userProductsArray[] = [
                    'id' => $userId,
                    'email' => $userEmail,
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'products' => $user->getProduct()->toArray()
                ];
            }
        }
        return $this->render('product_associate/index.html.twig', [
            'controller_name' => 'ProductAssociateController',
            'userProductsArray' => $userProductsArray
        ]);
    }

    #[Route('/product-associate-create', name: 'app_product_associate_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        if(!$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(UserProductType::class);

        $form->handleRequest($request);
        
        try{
            if ($form->isSubmitted() && $form->isValid()) {
                // vérifier si l'utilisateur à déjà le produit associé
                if($form->getData()['user']->getProduct()->contains($form->getData()['product'])){
                    $this->addFlash('danger', 'L\'utilisateur possède déjà ce produit !');
                    return $this->redirectToRoute('app_product_associate_create');
                }
                $formData = $form->getData();
    
                // Associez le produit à l'utilisateur
                $selectedUser = $formData['user'];
                $selectedProduct = $formData['product'];
    
                // Ajouter le produit à l'utilisateur
                $selectedUser->addProduct($selectedProduct);
                $em->flush();
    
                $this->addFlash('success', 'Le produit à bien été associé à l\'utilisateur !');
    
                return $this->redirectToRoute('app_product_associate');
            }
        }catch (\Exception $e) {
            $this->addFlash('danger', 'Une erreur est survenue lors de l\'association du produit : ' . $e->getMessage());
            return $this->redirectToRoute('app_product_associate_create');
        }
        
        return $this->render('product_associate/create.html.twig', [
            'controller_name' => 'ProductAssociateController',
            'form' => $form->createView()
        ]);
    }
}

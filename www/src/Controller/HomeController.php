<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/', name:'app_home')]
    public Function index()
    {
        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            $user = $this->getUser();
            return $this->render('home/home.html.twig', [
                'user' => $user
            ]);
        }
        return $this->redirectToRoute('app_login');
    }
}
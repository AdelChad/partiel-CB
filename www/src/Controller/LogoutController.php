<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function index(Security $security): Response
    {   
        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            $security->logout();
            $security->logout(false);
            return $this->redirectToRoute('app_login');
        }
        return $this->redirectToRoute('app_login');
    }
}

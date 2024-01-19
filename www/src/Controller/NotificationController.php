<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification/{id}/vue', name: 'app_notification')]
    public function index($id, NotificationRepository $notificationRepository, EntityManagerInterface $entityManager): Response
    {
        $notification = $notificationRepository->findOneBy(['id' => $id]);
        $notification->setIsRead(true);

        $entityManager->persist($notification);
        $entityManager->flush();
        return $this->render('notification/index.html.twig', [
            'controller_name' => 'NotificationController',
        ]);
    }
}

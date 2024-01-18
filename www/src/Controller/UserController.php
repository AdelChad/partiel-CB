<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name:'app_user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'list')]
    public function index(UserRepository $userRepo): Response
    {
        $userList = $userRepo->findAll();

        return $this->render('user/list.html.twig', [
            'userList' => $userList,
        ]);
    }

    #[Route('/{id}', name: 'view')]
    public function view($id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find(intval($id));

        return $this->render('user/view.html.twig', ['user' => $user]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(User $user, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'update' => true,
        ]);
        
        $form->handleRequest($request);
        $errors = $form->getErrors(true, false);
        
        // traitement formulaire si envoyé et ok
        if ($form->isSubmitted()) {
            
            $email = $form->get('email')->getData();
            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('lastname')->getData();
            $password = $form->get('password')->getData();

            $user->setEmail($email);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPassword($passwordHasher->hashPassword($user, $password));
            $user->setUpdatedAt(new \DateTimeImmutable('now'));

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/update.html.twig', [
            'updateUserForm' => $form->createView()
        ]);
    }
}

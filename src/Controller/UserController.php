<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{name}', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'profile')]
    public function profile(string $name, UserRepository $repository): Response
    {
        $user = $repository->find($name);

        return $this->render('user/profile.html.twig', [
            "user" => $user
        ]);
    }
    #[Route('/modifier', name: 'update')]
    public function update(string $name, UserRepository $repository, EntityManagerInterface $manager): Response
    {
        $user = $repository->find($name);

        $user = new User();
        $userForm = $this->createForm(User::class, $user);

        return $this->render('user/update.html.twig', [
            "user" => $user,
            "userForm" => $userForm->createView()
        ]);
    }
}

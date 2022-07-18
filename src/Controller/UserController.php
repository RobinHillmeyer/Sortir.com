<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mon-profil', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/{name}', name: 'profile')]
    public function profile(string $name, UserRepository $repository): Response
    {
        $user = $repository->find($name);

        return $this->render('user/profile.html.twig', [
            "user" => $user
        ]);
    }
    #[Route('/modifier', name: 'update')]
    public function update(): Response
    {
        return $this->render('user/update.html.twig', [
        ]);
    }
}

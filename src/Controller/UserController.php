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
    #[Route('/modifier-profil', name: 'update')]
    public function update (Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {

//        TODO : hacher mot de passe et confirmation mdp


        $userForm = $this->createForm(UserType::class, $this->getUser());
        $userForm->handleRequest($request);

        $plaintextPassword = $this->getUser()->getPassword();

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $hachedPassword = $hasher->hashPassword(
                $this->getUser(),
                $plaintextPassword
            );
            $this->getUser()->setPassword($hachedPassword);

            $manager->persist($this->getUser());
            $manager->flush();

            return $this->redirectToRoute('user_profile', [
                'name' => $this->getUser()->getName(),
            ]);
        }
        return $this->render('user/update.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }
}

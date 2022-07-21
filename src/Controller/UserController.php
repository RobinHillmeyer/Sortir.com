<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UpdateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function update (Request $request,
                            EntityManagerInterface $manager,
                            UserPasswordHasherInterface $hasher,
                            SluggerInterface $slugger,
                            UpdateService $updateService): Response
    {


        $userForm = $this->createForm(UserType::class, $this->getUser());
        $userForm->handleRequest($request);


        if ($userForm->isSubmitted() && $userForm->isValid()) {

//            $updateService->uploadPicture();
            /**@var UploadedFile $uploadedFile */
            $uploadedFile = $userForm->get('profileImage')->getData();

            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

                try {
                    $uploadedFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $this->getUser()->setProfileImage($newFilename);
            }

            $plaintextPassword = $userForm->get('password')->getData();

            if ($plaintextPassword){
                $hachedPassword = $hasher->hashPassword(
                    $this->getUser(),
                    $plaintextPassword
                );
                $this->getUser()->setPassword($hachedPassword);
            }

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

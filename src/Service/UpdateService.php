<?php

namespace App\Service;


use App\Form\UserType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UpdateService
{
// TODO : A voir plus tard ou à supprimer

    public function __construct(protected SluggerInterface $slugger, protected UserType $form)
    {
    }

    public function uploadPicture() : void
    {

        /**@var UploadedFile $uploadedFile */
        $uploadedFile = $this->form->get('profileImage')->getData();

        if ($uploadedFile) {
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

            try {
                $uploadedFile->move(
                    $this->form->getParameter('img_directory'),
                    $newFilename
                );
            } catch (FileException $e) {

            }
            $this->form->getUser()->setProfileImage($newFilename);
        }
    }

}
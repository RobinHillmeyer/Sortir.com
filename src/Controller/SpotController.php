<?php

namespace App\Controller;

use App\Entity\Spot;
use App\Form\SpotType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lieu', name: 'spot_')]
class SpotController extends AbstractController
{
    #[Route('/creer-un-lieu', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $spot = new Spot();
        $spotForm = $this->createForm(SpotType::class, $spot);

        $spotForm->handleRequest($request);

        if ($spotForm->isSubmitted() && $spotForm->isValid()) {
            $entityManager->persist($spot);
            $entityManager->flush();

            return $this->redirectToRoute('trip_create');
        }

        return $this->render('spot/create.html.twig', [
            'spotForm' => $spotForm->createView()
        ]);
    }
}

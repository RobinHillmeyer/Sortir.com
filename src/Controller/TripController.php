<?php

namespace App\Controller;

use App\Entity\Spot;
use App\Entity\State;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\SpotType;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/sortie', name: 'trip_')]
class TripController extends AbstractController
{
    #[Route('/creer-une-sortie', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request ): Response
    {
        $trip = new Trip();
        $state = new State();

        $trip->setState($state->setWording("En cours"));
        $trip->setPromoter($this->getUser());

        $tripForm = $this->createForm(TripType::class, $trip);

        $tripForm->handleRequest($request);

        $spot = $tripForm->get('spot1')->getData();

        dump($spot);

        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            $entityManager->persist($state);
            $entityManager->persist($spot);
            dump($spot);
            $entityManager->persist($trip);
            $entityManager->flush();

            return $this->redirectToRoute('trip_list');
        }

        return $this->render('trip/create.html.twig', [
            'tripForm' => $tripForm->createView()
        ]);
    }

    #[Route('/liste-des-sorties', name: 'list')]
    public function list(TripRepository $tripRepository): Response
    {
        $trips = $tripRepository->findAll();

        return $this->render('trip/list.html.twig', [
            'trips' => $trips
        ]);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail(int $id, TripRepository $tripRepository): Response
    {
        $trip = $tripRepository->find($id);

        return $this->render('trip/detail.html.twig', [
            'trip' => $trip
        ]);
    }
}

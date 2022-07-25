<?php

namespace App\Controller;

use App\Entity\Spot;
use App\Entity\State;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\SpotType;
use App\Form\TripType;
use App\Repository\CampusRepository;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/', name: 'trip_')]
class TripController extends AbstractController
{
    #[Route('/creer-une-sortie', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request, StateRepository $stateRepository): Response
    {
        $trip = new Trip();


        $trip->setState($stateRepository->find(1));
        $trip->setPromoter($this->getUser());

        $tripForm = $this->createForm(TripType::class, $trip);

        $tripForm->handleRequest($request);


        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $spot = $tripForm->get('spot1')->getData();

            if ($spot->getName()) {
                $trip->setSpot($spot);
            }

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie a été créée.');
            return $this->redirectToRoute('trip_list');
        }

        return $this->render('trip/create.html.twig', [
            'tripForm' => $tripForm->createView()
        ]);
    }

    #[Route('', name: 'list')]
    public function list(TripRepository $tripRepository, CampusRepository $campusRepository): Response
    {
        $trips = $tripRepository->findAll();
        $campus = $campusRepository->findAll();

        return $this->render('trip/list.html.twig', [
            'trips' => $trips,
            'campus' => $campus
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

    #[Route('/publish/{id}', name: 'publish')]
    public function publish(EntityManagerInterface $entityManager, StateRepository $stateRepository, TripRepository $tripRepository, int $id): Response {
        $trip = $tripRepository->find($id);
        $trip->setState($stateRepository->find(2));

        $entityManager->persist($trip);
        $entityManager->flush();

        return $this->redirectToRoute('trip_list');
    }

    #[Route('/cancelled/{id}', name: 'publish')]
    public function cancelled(EntityManagerInterface $entityManager, StateRepository $stateRepository, TripRepository $tripRepository, int $id): Response {
        $trip = $tripRepository->find($id);
        $trip->setState($stateRepository->find(4));

        $entityManager->persist($trip);
        $entityManager->flush();

        return $this->redirectToRoute('trip_list');
    }

}

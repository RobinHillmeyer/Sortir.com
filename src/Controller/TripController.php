<?php

namespace App\Controller;


use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\CampusRepository;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
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


    #[Route('/modifier-une-sortie/{id}', name: 'updateTrip')]
    public function updateTrip(EntityManagerInterface $manager, Request $request, TripRepository $tripRepository, int $id): Response
    {
        $trip = $tripRepository->find($id);
        $tripUpdateForm = $this->createForm(TripType::class, $trip);
        $tripUpdateForm->handleRequest($request);

        if ($tripUpdateForm->isSubmitted() && $tripUpdateForm->isValid()) {
            $spot = $tripUpdateForm->get('spot1')->getData();

            if ($spot->getName()) {
                $trip->setSpot($spot);
            }

            $manager->persist($trip);
            $manager->flush();

            $this->addFlash('success', 'La sortie a été modifiée.');
            return $this->redirectToRoute('trip_list');
        }
        return $this->render('trip/update.html.twig', [
            'tripForm' => $tripUpdateForm->createView(),
            'trip' => $trip
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

    #[Route('/cancel/{id}', name: 'cancel')]
    public function cancel(EntityManagerInterface $entityManager, StateRepository $stateRepository, TripRepository $tripRepository, int $id): Response {
        $trip = $tripRepository->find($id);
        $trip->setState($stateRepository->find(4));

        $entityManager->persist($trip);
        $entityManager->flush();

        return $this->redirectToRoute('trip_list');
        // TODO render -> page annuler avec formulaire
    }

//    #[Route('/lifeCycleTrip/{id}', name: 'lifeCycleTrip')]
//    public function lifeCycleTrip(EntityManagerInterface $entityManager, StateRepository $stateRepository, TripRepository $tripRepository, int $id): Response {
//        $trip = $tripRepository->find($id);
//        if ()                                           //start_date_time == dateNow
//        {
//            $trip->setState($stateRepository->find(5));
//        }
//        elseif ()                                       //duration -- 1minute && duration = 0
//        {
//            $trip->setState($stateRepository->find(6));
//        }
//        elseif ()                                       //getState(6) >= 30jours
//        {
//            $trip->setState($stateRepository->find(7));
//        }
//
//        $entityManager->persist($trip);
//        $entityManager->flush();
//
//        return $this->redirectToRoute('trip_list');
//    }

}

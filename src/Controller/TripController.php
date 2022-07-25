<?php

namespace App\Controller;


use App\Entity\Trip;
use App\Entity\User;
use App\Form\CancelType;
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

        $trip->setPromoter($this->getUser());

        $tripForm = $this->createForm(TripType::class, $trip);

        $tripForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $spot = $tripForm->get('spot1')->getData();

            if ($spot->getName()) {
                $trip->setSpot($spot);
            }

            if ($tripForm->get('create')->isClicked()) {
                $trip->setState($stateRepository->find(1));
            } elseif ($tripForm->get('publish')->isClicked()) {
                $trip->setState($stateRepository->find(2));
            }

            $trip->addUser($this->getUser());

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
        $trips = $tripRepository->findTrips();
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
        $users = $trip->getUsers();

        return $this->render('trip/detail.html.twig', [
            'trip' => $trip,
            'users' => $users
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

    #[Route('/annuler-la-sortie/{id}', name: 'cancel')]
    public function cancel(EntityManagerInterface $entityManager, StateRepository $stateRepository, TripRepository $tripRepository, int $id, Request $request, CampusRepository $campusRepository): Response {
        $trip = $tripRepository->find($id);

        $cancelForm = $this->createForm(CancelType::class, $trip);
        $cancelForm->handleRequest($request);

        if ($cancelForm->isSubmitted() && $cancelForm->isValid()) {
            $trip->setState($stateRepository->find(4));

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash("success", "La sortie a bien été annulée");
            return $this->redirectToRoute('trip_list');

        }

        return $this->render('trip/cancel.html.twig', [
            'cancelForm' => $cancelForm->createView(),
            'trip' => $trip
        ]);
    }

    #[Route('/sortie-annulee/{id}', name: 'cancelDetail')]
    public function cancelDetail(TripRepository $tripRepository, int $id): Response {
        $trip = $tripRepository->find($id);

        return $this->render('trip/cancelDetail.html.twig', [
            'trip' => $trip
        ]);
    }

    #[Route('/subscribeTrip/{id}', name: 'subscribeTrip')]
    public function subscribeTrip(EntityManagerInterface $entityManager, TripRepository $tripRepository, int $id): Response
    {
        $trip = $tripRepository->find($id);

        /**@var \App\Entity\User $user*/
        $user = $this->getUser();

        if ($trip->getUsers()->count() < $trip->getRegistrationNumberMax() and $trip->getState()->getWording() == "Ouverte") {
            // TODO: ajouter une contrainte de date dans le if
            $trip->addUser($user);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vous êtres bien inscrit à la sortie : '.$trip->getName());
        } else {
            $this->addFlash('error', 'Il n\' y a plus de place dans cette sortie');
        }


        return $this->redirectToRoute('trip_list');
    }

    #[Route('/unsubscribeTrip/{id}', name: 'unsubscribeTrip')]
    public function unsubscribeTrip(EntityManagerInterface $entityManager, TripRepository $tripRepository, int $id): Response
    {
        $trip = $tripRepository->find($id);

        /**@var \App\Entity\User $user*/
        $user = $this->getUser();

        if ($trip->getState()->getWording() == "Ouverte") {
            $trip->removeUser($user);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vous vous êtes desisté de la sortie : '.$trip->getName());
        } else {
            $this->addFlash('error', 'Il est trop tard pour de désinscrire de la sortie');
        }

        return $this->redirectToRoute('trip_list');
    }



}

<?php

namespace App\Service;

use App\Entity\Trip;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Void_;


class LifeCycleTripService
{
    public function __construct(protected TripRepository $tripRepository,
                                protected StateRepository $stateRepository,
                                protected EntityManagerInterface $entityManager)
    {
    }

    public function lifeCycleTrip(): void
    {
        $trips = $this->tripRepository->findTrips();
        foreach ($trips as $trip) {
            $duration = $trip->getDuration();
            $dateStart = clone $trip->getStartDateTime();
            $dateStart2 = clone $trip->getStartDateTime();
            $dateStart3 = clone $trip->getStartDateTime();
            $deadLine = $trip->getRegistrationDeadLine();
            $month = $dateStart2->modify('+30 days');
            $dateEnd = $dateStart3->modify("+$duration min");
            $dateNow = new \DateTime("now");


            if ($dateNow >= $deadLine and $trip->getState()->getWording() == "Ouverte") {
                $trip->setState($this->stateRepository->find(3));
            } elseif ($dateStart <= $dateNow and $trip->getState()->getWording() == "Clôturée") {
                $trip->setState($this->stateRepository->find(5));
            } elseif ($dateEnd < $dateNow and $trip->getState()->getWording() == "Activité en cours") {
                $trip->setState($this->stateRepository->find(6));
            } elseif ($month <= $dateNow) {
                $trip->setState($this->stateRepository->find(7));
            }

            $this->entityManager->persist($trip);
            $this->entityManager->flush();
        }

    }

}
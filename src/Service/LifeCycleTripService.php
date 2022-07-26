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
            $month = date_add($trip->getStartDateTime(), date_interval_create_from_date_string("30 days"));
            dump($trip);
            if ($trip->getStartDateTime() >= date('d-m-Y')) {
                $trip->setState($this->stateRepository->find(5));
                dump($trip);
            } elseif ($duration > 0 and $trip->getState()->getId() == 5) {
                while ($duration > 0) {
                    $duration--;
                }
                $trip->setState($this->stateRepository->find(6));
                dump($trip);
            } elseif ($month >= date('d-m-Y')) {
                $trip->setState($this->stateRepository->find(7));
                dump($trip);
            }
            dump($trip);
            $this->entityManager->persist($trip);
            $this->entityManager->flush();
        }
    }

}
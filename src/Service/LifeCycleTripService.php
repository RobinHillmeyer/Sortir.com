<?php

namespace App\Service;

use App\Repository\StateRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Response;

class LifeCycleTripService
{
    public function __construct(protected TripRepository $tripRepository,
                                protected StateRepository $stateRepository,
                                protected EntityManagerInterface $entityManager)
    {
    }

    public function lifeCycleTrip(int $id): Response
    {
        $trip = $this->tripRepository->find($id);
        if ($trip->getStartDateTime() >= date() && ($trip->getStartDateTime() + $trip->getDuration()) <= date())  //start_date_time == dateNow -> en cours
        {
            $trip->setState($this->stateRepository->find(5));
        }
        elseif (($trip->getStartDateTime() + $trip->getDuration()) > date())   //duration -- 1minute && duration = 0 -> terminée
        {
            $trip->setState($this->stateRepository->find(6));
        }
        elseif ($trip->getStartDateTime()+30 >= date()) //getState(6) >= 30jours -> historiée
        {
            $trip->setState($this->stateRepository->find(7));
        }

        $this->entityManager->persist($trip);
        $this->entityManager->flush();

        return $this->redirectToRoute('trip_list');
    }

}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpotController extends AbstractController
{
    #[Route('/spot', name: 'app_spot')]
    public function index(): Response
    {
        return $this->render('spot/index.html.twig', [
            'controller_name' => 'SpotController',
        ]);
    }
}

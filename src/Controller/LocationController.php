<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    /**
     * @Route("/location", name="app_location")
     */
    public function index(): Response
    {
        return $this->render('location/home.html.twig', [
            'controller_name' => 'LocationController',
        ]);
    }
}

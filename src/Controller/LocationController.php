<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\SearchLocationType;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    /**
     * @Route("/location", name="app_location")
     */
    public function index(LocationRepository $locationRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $search = new Location();
        $searchForm = $this->createForm(SearchLocationType::class, $search);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {

        $allLocation = $locationRepository->searchCity();
        }

        $allLocation = $locationRepository->findAll();
        return $this->render("location/location.html.twig", ['searchForm' => $searchForm->createView(),
            'allLocation'=>$allLocation]);
        }
}

<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Trip;
use App\Form\AllTripSearchType;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class MainConstrollerController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     *
     */
    public function tripList(Request $request, EntityManagerInterface $entityManager, TripRepository $tripRepository): Response
    {

        $searchForm = $this->createForm(AllTripSearchType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $AllTrip=$tripRepository->search($request->request->get('all_trip_search'));
        }else{
            $AllTrip=$tripRepository->findAll();

        }
        return $this->render("home.html.twig", ['searchForm' => $searchForm->createView(),
            'AllTrip'=>$AllTrip]);
    }



}




<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\SearchFormTripType;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $search = new Trip();
        $searchForm = $this->createForm(SearchFormTripType::class, $search);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {

            $AllTrip = $tripRepository->getAllTrip();
        }

        $AllTrip = $tripRepository->findAll();

        return $this->render("home.html.twig", ['searchForm' => $searchForm->createView(),
            'AllTrip'=>$AllTrip]);
    }


}




<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Search\SearchForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class MainConstrollerController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     *
     */
    public function tripList(TripRepository $tripRepository): Response
    {
        $trip=$tripRepository->tripList();
        return $this->render("home.html.twig",[
        "trip"=>$trip
        ]);
    }
}




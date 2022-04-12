<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/trips, name="trip_")
 */

class TripController extends AbstractController
{
    /**
     * @Route("/trip", name="app_trip")
     */
    public function index(): Response
    {
        return $this->render('trip/triptest.html.twig', [
            'controller_name' => 'TripController',
        ]);
    }

    /**
     * @Route("/createtrip", name="createtrip")
     */
    public function create(Request $request, EntityManagerInterface $entityManager){
        $trip = new Trip();
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);
        if ($tripForm->isSubmitted() && $tripForm->isValid()){
            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', "Votre sortie a bien été crée!");
            return $this->redirectToRoute('main_home');
        }
        return $this->render("trip/createtrip.html.twig", ['tripForm'=>$tripForm->createView()]);
    }

    /**
     * @Route (/detailtrip/{id}, name="details)
     */
    public function details($id, TripRepository $tripRepository){
        $trip=$tripRepository->find($id);
        if (!$trip){
            throw $this->createNotFoundException("Cette sortie n'est pas créée, désolée!");
        }
        return $this->render("trip/details.html.twig", ['trip'=>$trip]);
    }

}

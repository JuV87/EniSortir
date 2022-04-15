<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\EtatRepository;
use App\Repository\LocationRepository;
use App\Repository\PlaceRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/trip", name="trip_")
 */

class TripController extends AbstractController
{

    /**
     * @Route("/createtrip", name="createtrip")
     */
    public function create(Request $request, UserRepository $user, EntityManagerInterface $entityManager, EtatRepository $etatRepository, LocationRepository $locationRepository, PlaceRepository $placeRepository){

        $trip = new Trip();
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);
        if ($tripForm->isSubmitted() && $tripForm->isValid()){
            $trip->setEtat($etat = $etatRepository->find(1));
            $trip->setLocation($location = $locationRepository->find(1));
            $trip->setPlace($place =$placeRepository->find(1));
            $trip->setOrganizer($user->find(1));

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', "Votre sortie a bien été crée!");
            return $this->redirectToRoute('home');
        }
        return $this->render("trip/createtrip.html.twig", ['tripForm'=>$tripForm->createView()]);
    }

    /**
     * @Route ("/detailtrip/{id}", name="details")
     */

    public function details($id, TripRepository $tripRepository){
        $trip=$tripRepository->find($id);
        if (!$trip){
            throw $this->createNotFoundException("Cette sortie n'est pas créée, désolée!");
        }
        return $this->render("trip/details.html.twig", ['trip'=>$trip]);
    }

    /**
     * @Route ("/modify/{id}", name="modify")
     */
    public function modify($id, EntityManagerInterface $entityManager, EtatRepository $etatRepository, Request $request, TripRepository $tripRepository){
        $trip = $tripRepository->find($id);
        if (!$trip){
            throw $this->createNotFoundException('Attention, cette sortie n\'est pas dans la base de données!');
        }
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);
        if ( $tripForm->isSubmitted() && $tripForm->isValid()){
            $trip->setEtat($etat = $etatRepository->find(1));
            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', "Votre sortie a bien été modifié !");
            return $this->redirectToRoute('home');
        }
        return $this->render('trip/modify.html.twig', ['tripForm'=>$tripForm->createView(),
        'trip'=>$trip
        ]);
    }





}

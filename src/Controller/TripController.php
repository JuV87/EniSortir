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
     * @Route("/createtrip/{id}", name="createtrip")
     */
    public function create($id, Request $request, UserRepository $user, EntityManagerInterface $entityManager, EtatRepository $etatRepository, LocationRepository $locationRepository, PlaceRepository $placeRepository){

        $trip = new Trip();
        $currentUser = $user->find($id);
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);
        if ($tripForm->isSubmitted() && $tripForm->isValid()){
            $trip->setEtat($etat = $etatRepository->find(1));
            $trip->setLocation($location = $locationRepository->find($user->find($id)->getId()));
            $trip->setPlace($place =$placeRepository->find(1));
            $trip->setOrganizer($user->find($user->find($id)->getId()));

            $entityManager->persist($trip);
            $entityManager->flush();

            $this->addFlash('success', "Votre sortie a bien été crée!");
            return $this->redirectToRoute('home');
        }
        return $this->render("trip/createtrip.html.twig", ['tripForm'=>$tripForm->createView(),
                                                                'user'=>$currentUser
        ]);
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
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);
        if (!$trip){
            throw $this->createNotFoundException('Attention, cette sortie n\'est pas dans la base de données!');
        }

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
    /**
     * @Route ("/delete/{id}", name="delete")
     */
    public function modify_delete($id, EntityManagerInterface $entityManager, Request $request, TripRepository $tripRepository){

        $trip = $tripRepository->find($id);
        if (!$trip){
            throw $this->createNotFoundException('Attention, cette sortie n\'est pas dans la base de données!');
        }
            $entityManager->remove($trip);
            $entityManager->flush();

            $this->addFlash('success', "Votre sortie a bien été annulée !");

        return $this->redirectToRoute('home');
    }

    /**
     * @Route ("/update/{id}", name="update")
     */
    public function update($id, EtatRepository $etatRepository,EntityManagerInterface $entityManager, Request $request, TripRepository $tripRepository){

        $trip = $tripRepository->find($id);
        if (!$trip){
            throw $this->createNotFoundException('Attention, cette sortie n\'est pas dans la base de données!');
        }
        $trip->setEtat($etat = $etatRepository->find(2));

        $entityManager->persist($trip);
        $entityManager->flush();

        $this->addFlash('success', "Votre sortie a bien été publiée !");

        return $this->redirectToRoute('home');
    }





}

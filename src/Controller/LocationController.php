<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\AddLocationType;
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
        $newLocation = new Location();
        $searchForm = $this->createForm(AddLocationType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $newLocation =$searchForm->getData();
             $entityManager->persist($newLocation);
            $entityManager->flush();

            $this->addFlash('success', "Votre campus a bien été crée!");
            return $this->redirectToRoute('app_location');
        }

        $allLocation = $locationRepository->findAll();
        return $this->render("location/location.html.twig", ['searchForm' => $searchForm->createView(),
            'allLocation'=>$allLocation]);
        }

    /**
     * @Route ("location/delete/{id}", name="location_delete")
     */
    public function delete($id, EntityManagerInterface $entityManager, Request $request, LocationRepository $locationRepository){

        $location = $locationRepository->find($id);
        if (!$location ){
            throw $this->createNotFoundException('Attention, ce site n\'est pas dans la base de données!');
        }
        $entityManager->remove($location);
        $entityManager->flush();

        $this->addFlash('success', "Votre site a bien été supprimé !");

        return $this->redirectToRoute('app_location');
    }

    /**
     * @Route ("/modify/{id}", name="location_modify")
     */
    public function modify($id, EntityManagerInterface $entityManager, LocationRepository $locationRepository, Request $request){
        $location = $locationRepository->find($id);
        $searchForm = $this->createForm(AddLocationType::class, $location);
        $searchForm->handleRequest($request);
        if (!$location){
            throw $this->createNotFoundException('Attention, ce site n\'est pas dans la base de données!');
        }

        if ( $searchForm->isSubmitted() && $searchForm->isValid()){

            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', "Votre site a bien été modifié !");
            return $this->redirectToRoute('app_location');
        }
        return $this->render('location/modify.html.twig', ['searchForm'=> $searchForm->createView(),
            'location'=> $location
        ]);
    }
}

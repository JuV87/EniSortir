<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\AddCityType;
use App\Form\SearchCityType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CityController extends AbstractController
{
    /**
     * @Route("/city", name="app_city")
     */
    public function index(CityRepository $cityRepository, Request $request,EntityManagerInterface $entityManager){

      $city = new City();

        $searchForm = $this->createForm(AddCityType::class, $city);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash('success', "Votre ville a bien été ajouté !");
        return $this->redirectToRoute('app_city');
            }

        $allCities = $cityRepository->findAll();
        return $this->render("city/city.html.twig", ['searchForm'=>$searchForm->createView(),
            'allCities'=>$allCities]);
}

    /**
     * @Route ("city/delete/{id}", name="city_delete")
     */
    public function delete($id, EntityManagerInterface $entityManager, Request $request, CityRepository $cityRepository){

        $city = $cityRepository->find($id);
        if (!$city){
            throw $this->createNotFoundException('Attention, cette ville n\'est pas dans la base de données!');
        }
        $entityManager->remove($city);
        $entityManager->flush();

        $this->addFlash('success', "Votre ville a bien été supprimée !");

        return $this->redirectToRoute('app_city');
    }

    /**
     * @Route ("city/modify/{id}", name="city_modify")
     */
    public function modify($id, EntityManagerInterface $entityManager, CityRepository $cityRepository, Request $request){
        $city = $cityRepository->find($id);
        $searchForm = $this->createForm(AddCityType::class, $city);
        $searchForm->handleRequest($request);
        if (!$city){
            throw $this->createNotFoundException('Attention, cette ville n\'est pas dans la base de données!');
        }

        if ( $searchForm->isSubmitted() && $searchForm->isValid()){

            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash('success', "Votre ville a bien été modifiée !");
            return $this->redirectToRoute('home');
        }
        return $this->render('city/modify.html.twig', ['searchForm'=> $searchForm->createView(),
            'city'=> $city
        ]);
    }


}


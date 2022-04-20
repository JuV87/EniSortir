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
use function Sodium\add;

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
        return $this->render("city/city.html.twig", ['searchForm' => $searchForm->createView(),
            'allCities'=>$allCities]);
}


}


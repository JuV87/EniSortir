<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Location;
use App\Form\SearchCityType;
use App\Form\SearchLocationType;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class CityController extends AbstractController
{
    /**
     * @Route("/city", name="app_city")
     */
    public function index(CityRepository $cityRepository, Request $request,EntityManagerInterface $entityManager){

      $search = new City();

        $searchForm = $this->createForm(SearchCityType::class, $search);
        $searchForm->handleRequest($request);
            if ($searchForm->isSubmitted()) {

            $allCities = $cityRepository->findAll();
            $this->render('city/citySearch.html.twig', ['searchForm' => $searchForm->createView(),
                'allCities'=>$allCities]);
            }

        $allCities = $cityRepository->findAll();
        return $this->render("city/city.html.twig", ['searchForm' => $searchForm->createView(),
            'allCities'=>$allCities]);
}


}


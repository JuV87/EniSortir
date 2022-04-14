<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\SearchCityType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    /**
     * @Route("/city", name="app_city")
     */
    public function index(CityRepository $cityRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $search = new City();
        $searchForm = $this->createForm(SearchCityType::class, $search);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {

            $allCity = $cityRepository->searchCity();
        }

        $allCity = $cityRepository->findAll();
        return $this->render("city/city.html.twig", ['searchForm' => $searchForm->createView(),
            'allCity'=>$allCity]);
    }
}

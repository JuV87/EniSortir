<?php

namespace App\Controller;

use App\Entity\City;

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



        $allCities = $cityRepository->findAll();
        return $this->render("city/city.html.twig", [
            'allCities'=>$allCities]);
}


}


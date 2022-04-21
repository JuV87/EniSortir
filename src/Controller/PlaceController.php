<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    /**
     * @Route("/place", name="app_place")
     */
    public function create(Request $request, EntityManagerInterface $entityManager, PlaceRepository $placeRepository){

        $place = new Place();
        $placeForm = $this->createForm(PlaceType::class, $place);
            $placeForm->handleRequest($request);

            if ($placeForm->isSubmitted() && $placeForm->isValid()){
                $place=$placeForm->getData();
                $entityManager->persist($place);
                $entityManager->flush();

            $this->addFlash('success', "Votre lieu de sortie a bien été crée!");
            return $this->redirectToRoute('app_place');
        }
        return $this->render("place/index.html.twig", ['placeForm'=>$placeForm->createView(),
            'place'=>$place
        ]);
    }
}

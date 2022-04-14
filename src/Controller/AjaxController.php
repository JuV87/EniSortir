<?php

use App\Entity\City;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController

{
    /**
     * @Route("/ajax/rechercheLieuByVille", name="rechercher_lieu_by_ville")
     */
    public function rechercheAjaxByVille(Request $request, EntityManagerInterface $entityManager, PlaceRepository $placeRepository): JsonResponse
    {
    //declaration des variables
        $json_data = array();
        $i = 0;
    //recherche les lieux correspondant à la ville selectionnée
        $lieux = $placeRepository->findBy(['city' => $request->request->get('city_id')]);
    //si lieux trouvées ...
        if (sizeof($lieux) > 0) {
    //pour chaque lieu, hydratation d'un tableau
            foreach ($lieux as $lieu) {
                $json_data[$i++] = array('id' => $lieu->getId(), 'name' => $lieu->getName());
            }
    //renvoie un tableau au format json
            return new JsonResponse($json_data);
    //sinon (lieux non trouvé) ...
        } else {
    //hydratation du tableau avec : Pas de lieu correspondant à cette ville.
            $json_data[$i++] = array('id' => '', 'name' => 'Pas de lieu correspondant à cette ville.');
    //renvoie un tableau au format json
            return new JsonResponse($json_data);
        }
    }


}
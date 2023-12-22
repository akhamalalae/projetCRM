<?php

namespace App\Controller\ModuleGestionEntreprises;

use App\Core\Service\PointVentes\PointVentes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PointVentesController extends AbstractController
{
    /**
    * @Route("/gestionnaire/produits/formulaires", name="getProduitsFormulairesOptions", methods={"GET","POST"})
    *
    * @param Request $request
    * @param PointVentes $service
    *
    * @return JsonResponse
    */
    public function getPointVentesCalander(Request $request, PointVentes $service):JsonResponse
    {
        return $this->json($service->getJson($request));
    }
}

<?php

namespace App\Controller\ModuleGestionCategoriesEtProduits;

use App\Core\Service\CategoriesProduits\CategoriesProduits;
use App\Core\Service\CategoriesProduits\Produits;
use App\Core\Trait\RenderTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesProduitsController extends AbstractController
{
    use RenderTrait;

    /**
     * @Route("/gestionnaire/entreprise/categories/produits", name="categoriesProduits", methods={"GET","POST"})
     *
     * @param Request $request
     * @param CategoriesProduits $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesProduits(Request $request,CategoriesProduits $service): Response
    {
        return $this->renderTrait($request, $service, []);
    }

    /**
    * @Route("/gestionnaire/produits/formulaires", name="getProduitsFormulairesOptions", methods={"GET","POST"})
    *
    * @param Request $request
    * @param Produits $service
    *
    * @return JsonResponse
    */
    public function getProduits(Request $request, Produits $service):JsonResponse
    {
        return $this->json($service->getJson($request));
    }
}


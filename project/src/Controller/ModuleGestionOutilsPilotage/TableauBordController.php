<?php

namespace App\Controller\ModuleGestionOutilsPilotage;

use App\Core\Service\GestionOutilsPilotage\ChoixTableauBord;
use App\Core\Service\GestionOutilsPilotage\ListEntitiesPropriete;
use App\Core\Service\GestionOutilsPilotage\ListOperators;
use App\Core\Service\GestionOutilsPilotage\Synthese;
use App\Core\Service\GestionOutilsPilotage\TableauBord;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Trait\RenderTrait;

class TableauBordController extends AbstractController
{
    use RenderTrait;

    /**
     * @Route("/gestionnaire/choix/tableau_de_bord", name="choix_tableau_de_bord", methods={"GET","POST"})
     *
     * @param Request $request
     * @param ChoixTableauBord $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function choixTableauBordd(Request $request, ChoixTableauBord $service):Response
    {
        return $this->renderTrait($request, $service, []);
    }

    /**
     * @Route("/gestionnaire/tableau_de_bord/{id}", name="tableau_de_bord", methods={"GET","POST"})
     *
     * @param Request $request
     * @param TableauBord $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableauBrd(Request $request,TableauBord $service, $id):Response
    {
        return $this->renderTrait($request, $service, ['id' => $id, 'user' => $this->getUser()]);
    }

    /**
     * @Route("/gestionnaires/synthese", name="synthese_gestionnaires", methods={"GET","POST"})
     *
     * @param Synthese $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function synthese(Synthese $service): Response
    {
        return $this->render($service->view(), $service->parameters());
    }

    /**
    * @Route("/gestionnaire/tableau_de_bord/get/entities",name="getlistEntitiesPropriete", methods={"GET","POST"})
    *
    * @param Request $request
    * @param ListEntitiesPropriete $service
    *
    * @return JsonResponse
    */
    public function listEntitiesPropriete(Request $request, ListEntitiesPropriete $service):JsonResponse
    {
        return $this->json($service->getJson($request));
    }

    /**
    * @Route("/gestionnaire/tableau_de_bord/get/typeChamp", name="getTypeChamp", methods={"GET","POST"})
    *
    * @param Request $request
    * @param ListOperators $service
    *
    * @return JsonResponse
    */
    public function listOperators(Request $request, ListOperators $service):JsonResponse
    {
        return $this->json($service->getJson($request));
    }
}


<?php

namespace App\Controller\ModuleGestionOutilsPilotage;

use App\Entity\RenderVous;
use App\Entity\Typeschamps;
use App\Entity\EntitiesPropriete;
use App\Controller\BaseController;
use App\Core\Service\GestionOutilsPilotage\ChoixTableauBord;
use App\Core\Service\GestionOutilsPilotage\TableauBord;
use App\Core\Trait\RenderTrait;
use App\Entity\TableauBordFiltresOperators;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TableauBordController extends BaseController
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
        return $this->renderTrait($request, $service,[]);
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
        return $this->renderTrait($request, $service,['id' => $id]);
    }

     /**
     * @Route("/gestionnaire/tableau_de_bord/get/entities", name="getEntitiesProprieteConditionByEntities", methods={"GET","POST"})
     */
    public function entitiesProprieteConditionByEntities(Request $request)
    {
        $idEntite = $request->get('idEntite');
        $listes_EntitiesPropriete  = "";

        $entitiesProprietes = $this->em->getRepository(EntitiesPropriete::class)->findBy(['entitie' => $idEntite, 'status' => 0]);

        foreach ($entitiesProprietes as $une_prop) {
            $listes_EntitiesPropriete .= "<option value=".$une_prop->getId()." >".$une_prop->getName()."( ".$une_prop->getTypesChamps()->getLibelle()." )"."</option>";
        }

        return $this->json(array('listes_EntitiesPropriete' => $listes_EntitiesPropriete));
    }

     /**
     * @Route("/gestionnaire/tableau_de_bord/get/typeChamp", name="getTypeChamp", methods={"GET","POST"})
     */
    public function typeChamp(Request $request)
    {
        $idEntitePropriete = $request->get('idEntitePropriete');

        $entitiesProprietes = $this->em->getRepository(EntitiesPropriete::class)->find($idEntitePropriete);

        $arrayOperators = array();

        if($entitiesProprietes->getTypesChamps()->getId() == Typeschamps::DATETYPE) {
            array_push($arrayOperators, TableauBordFiltresOperators::OPERATEUREGAL,
                TableauBordFiltresOperators::OPERATEURSUPERIEUR,
                TableauBordFiltresOperators::OPERATEURINFERIEUR,
                TableauBordFiltresOperators::OPERATEURDIFFERENT
            );
        }

        if($entitiesProprietes->getTypesChamps()->getId() == Typeschamps::TEXTTYPE || $entitiesProprietes->getTypesChamps()->getId() == Typeschamps::ZONETEXTTYPE) {
            array_push($arrayOperators, TableauBordFiltresOperators::OPERATEUREGAL,
                TableauBordFiltresOperators::OPERATEURDIFFERENT,
                TableauBordFiltresOperators::OPERATEURCONTIENT
            );
        }

        if($entitiesProprietes->getTypesChamps()->getId() == Typeschamps::ENTIERTYPE || $entitiesProprietes->getTypesChamps()->getId() == Typeschamps::MONEYTYPE) {
            array_push($arrayOperators, TableauBordFiltresOperators::OPERATEUREGAL,
                TableauBordFiltresOperators::OPERATEURSUPERIEUR,
                TableauBordFiltresOperators::OPERATEURINFERIEUR,
                TableauBordFiltresOperators::OPERATEURDIFFERENT
            );
        }

        if($entitiesProprietes->getTypesChamps()->getId() == Typeschamps::BOOLEANTYPE ) {
            array_push($arrayOperators, TableauBordFiltresOperators::OPERATEUREGAL,
                TableauBordFiltresOperators::OPERATEURDIFFERENT
            );
        }

        $operators = $this->em->getRepository(TableauBordFiltresOperators::class)->getOperators($arrayOperators);

        $listOperators = "<option selected>Choisir l'opérateur</option>";
        foreach ($operators as $op) {
            $listOperators .= "<option value=".$op->getId()." >".$op->getLibelle()."</option>";
        }

        return $this->json(array('listOperators' => $listOperators));
    }

    /**
     * @Route("/gestionnaires/synthese", name="synthese_gestionnaires", methods={"GET","POST"})
     */
    public function syntheseGestionnaires(Request $request): Response
    {
        $menus = $this->serviceMenu();

        $rendezVous = $this->em->getRepository(RenderVous::class)->findAll();

        return $this->render('tableauBord/SyntheseGestionnaires.html.twig', [
            'menus' => $menus,
            'rendezVous' => $rendezVous,
        ]);
    }

}

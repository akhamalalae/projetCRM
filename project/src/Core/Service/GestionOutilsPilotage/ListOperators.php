<?php

namespace App\Core\Service\GestionOutilsPilotage;

use App\Core\Interface\AjaxInterface;
use App\Entity\EntitiesPropriete;
use App\Entity\TableauBordFiltresOperators;
use App\Entity\Typeschamps;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ListOperators implements AjaxInterface
{
    public function __construct(public EntityManagerInterface $em)
    {
    }

    //AjaxInterface

    /**
     * Get Json
     *
     * @param Request $request
     *
     * @return array
     */
    public function getJson($request):array
    {
        $idEntitePropriete = $request->get('idEntitePropriete');

        $entitiesProprietes = $this->em->getRepository(EntitiesPropriete::class)->find($idEntitePropriete);

        $arrayOperators = array();

        if ($entitiesProprietes->getTypesChamps()->getId() == Typeschamps::DATETYPE) {
            array_push($arrayOperators, TableauBordFiltresOperators::OPERATEUREGAL,
                TableauBordFiltresOperators::OPERATEURSUPERIEUR,
                TableauBordFiltresOperators::OPERATEURINFERIEUR,
                TableauBordFiltresOperators::OPERATEURDIFFERENT
            );
        }

        if (
            $entitiesProprietes->getTypesChamps()->getId() == Typeschamps::TEXTTYPE
            || $entitiesProprietes->getTypesChamps()->getId() == Typeschamps::ZONETEXTTYPE
        ) {
            array_push($arrayOperators, TableauBordFiltresOperators::OPERATEUREGAL,
                TableauBordFiltresOperators::OPERATEURDIFFERENT,
                TableauBordFiltresOperators::OPERATEURCONTIENT
            );
        }

        if (
            $entitiesProprietes->getTypesChamps()->getId() == Typeschamps::ENTIERTYPE
            || $entitiesProprietes->getTypesChamps()->getId() == Typeschamps::MONEYTYPE
        ) {
            array_push($arrayOperators, TableauBordFiltresOperators::OPERATEUREGAL,
                TableauBordFiltresOperators::OPERATEURSUPERIEUR,
                TableauBordFiltresOperators::OPERATEURINFERIEUR,
                TableauBordFiltresOperators::OPERATEURDIFFERENT
            );
        }

        if ($entitiesProprietes->getTypesChamps()->getId() == Typeschamps::BOOLEANTYPE) {
            array_push($arrayOperators, TableauBordFiltresOperators::OPERATEUREGAL,
                TableauBordFiltresOperators::OPERATEURDIFFERENT
            );
        }

        $operators = $this->em->getRepository(TableauBordFiltresOperators::class)->getOperators($arrayOperators);

        $listOperators = "<option selected>Choisir l'opérateur</option>";

        foreach ($operators as $op) {
            $listOperators .= "<option value=".$op->getId()." >".$op->getLibelle()."</option>";
        }

        return ['listOperators' => $listOperators];
    }
}

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
    public function getJson($request): array
    {
        $idEntitePropriete = $request->get('idEntitePropriete');

        $entitiesProprietes = $this->em->getRepository(EntitiesPropriete::class)->find($idEntitePropriete);

        $arrayOperators = array();

        if ($entitiesProprietes) {
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
                || $entitiesProprietes->getTypesChamps()->getId() == Typeschamps::DATETYPE
            ) {
                $arrayOperators = $this->getOpTypeDateAndIntAndMoney();
            }
    
            if ($entitiesProprietes->getTypesChamps()->getId() == Typeschamps::BOOLEANTYPE) {
                array_push($arrayOperators, TableauBordFiltresOperators::OPERATEUREGAL,
                    TableauBordFiltresOperators::OPERATEURDIFFERENT
                );
            }
        }

        $operators = $this->em->getRepository(TableauBordFiltresOperators::class)->getOperators($arrayOperators);

        $listOperators = "<option selected>Choisir l'opérateur</option>";

        foreach ($operators as $op) {
            $listOperators .= sprintf('<option value="%s">%s</option>',
                $op->getId(),
                $op->getLibelle()
            );
        }

        return ['listOperators' => $listOperators];
    }

    /**
     * Get operateur si le champ est de type dateTime ou integer ou money
     *
     * @return array
     */
    public function getOpTypeDateAndIntAndMoney(): array
    {
        $arr = array();

        array_push($arr, TableauBordFiltresOperators::OPERATEUREGAL,
            TableauBordFiltresOperators::OPERATEURSUPERIEUR,
            TableauBordFiltresOperators::OPERATEURINFERIEUR,
            TableauBordFiltresOperators::OPERATEURDIFFERENT
        );

        return $arr;
    }
}

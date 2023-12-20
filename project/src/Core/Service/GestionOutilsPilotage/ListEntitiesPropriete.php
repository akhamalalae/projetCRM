<?php

namespace App\Core\Service\GestionOutilsPilotage;

use App\Core\Interface\AjaxInterface;
use App\Entity\EntitiesPropriete;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ListEntitiesPropriete implements AjaxInterface
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
        $idEntite = $request->get('idEntite');
        $listEntitiesPropriete  = "";

        $entitiesProprietes = $this->em->getRepository(EntitiesPropriete::class)->findBy(
            ['entitie' => $idEntite, 'status' => 0]
        );

        foreach ($entitiesProprietes as $une_prop) {
            $listEntitiesPropriete .= "<option value=".$une_prop->getId()
                                ." >".$une_prop->getName()
                                ."( ".$une_prop->getTypesChamps()->getLibelle()
                                ." )"."</option>";
        }

        return ['listes_EntitiesPropriete' => $listEntitiesPropriete];
    }
}

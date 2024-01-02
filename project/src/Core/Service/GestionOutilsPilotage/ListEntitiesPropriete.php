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
            ['entitie' => $idEntite, 'status' => 0, 'fonctionAgregation' => null]
        );

        foreach ($entitiesProprietes as $prop) {
            $listEntitiesPropriete .=  sprintf('<option value="%s" > %s (%s) </option>',
                $prop->getId(),
                $prop->getName(),
                $prop->getTypesChamps()->getLibelle()
            );
        }

        return ['listes_EntitiesPropriete' => $listEntitiesPropriete];
    }
}

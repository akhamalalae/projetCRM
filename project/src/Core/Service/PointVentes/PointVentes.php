<?php

namespace App\Core\Service\PointVentes;

use App\Core\Interface\AjaxInterface;
use App\Entity\Entreprise;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class PointVentes implements AjaxInterface
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
        $selected = $request->get('selected');
        $listePointeVentes = "";

        if($selected != []) {
            $entreprise = $this->em->getRepository(Entreprise::class)->find($selected);
            $pointeVente = $entreprise->getPointVentes();
            foreach ($pointeVente as $pointeVente) {
                $listePointeVentes .= "<option value=".$pointeVente->getId()." >".$pointeVente->getLibelle()."</option>";
            }
        }

        return ['listePointeVentes' => $listePointeVentes];
    }
}

<?php

namespace App\Core\Service\CategoriesProduits;

use App\Entity\Produit;
use App\Core\Interface\AjaxInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class Produits implements AjaxInterface
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
        $idEntreprises = $request->get('idEntreprises');

        $listeProduits = "";
        if ($idEntreprises != []) {
            $produits = $this->em->getRepository(Produit::class)->getProduitsFormulaires($idEntreprises);

            foreach ($produits as $produit) {
                $listeProduits .= "<option value=".$produit->getId()." >".$produit->getName()."</option>";
            }
        }

        return ['listeProduits' => $listeProduits];
    }
}

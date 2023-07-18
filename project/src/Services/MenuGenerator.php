<?php

// src/Service/MessageGenerator.php
namespace App\Services;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MenuCategorie;
use App\Entity\MenuSousCategorie;
use App\Repository\MenuCategorieRepository;
use App\Repository\MenuSousCategorieRepository;
class MenuGenerator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getMenu()
    {
        $menus = array();

        $menuCategorie = $this->em->getRepository(MenuCategorie::class);
        $menuCategorie = $menuCategorie->getMenu();
        $menu["menuCategorie"] = $menuCategorie;

        $menuSousCategorie = $this->em->getRepository(MenuSousCategorie::class);
        $menuSousCategorie = $menuSousCategorie->getMenuSousCategorie();
        $menu["menuSousCategorie"] = $menuSousCategorie;

        $menus = array (
            "menuCategorie" => $menu["menuCategorie"],
            "menuSousCategorie" => $menu["menuSousCategorie"]
        );

        return $menus;
    }
}
<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MenuCategorie;
use App\Entity\MenuSousCategorie;

class MenuGenerator
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getMenu(): array
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
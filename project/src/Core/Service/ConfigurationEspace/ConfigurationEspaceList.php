<?php

namespace App\Core\Service\ConfigurationEspace;

use App\Core\Interface\RenderInterface;
use App\Entity\PointVente;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class ConfigurationEspaceList implements RenderInterface
{
    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

    const VIEW_PATH         = 'configurationEspace/index.html.twig';

    //RenderInterface

    /**
     * view
     *
     * @return string
     */
    public function view()
    {
        return self::VIEW_PATH;
    }

    /**
     * parameters
     *
     * @return array
     */
    public function parameters()
    {
        $pointVente = $this->em->getRepository(PointVente::class)->findAll();

        return [
            'menus' => $this->menuGenerator->getMenu(),
            'pointVente' => $pointVente,
        ];
    }
}

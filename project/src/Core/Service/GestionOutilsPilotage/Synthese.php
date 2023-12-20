<?php

namespace App\Core\Service\GestionOutilsPilotage;

use App\Core\Interface\RenderInterface;
use App\Entity\RenderVous;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class Synthese implements RenderInterface
{
    const VIEW_PATH     = 'tableauBord/SyntheseGestionnaires.html.twig';

    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

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
        $rendezVous = $this->em->getRepository(RenderVous::class)->findAll();

        return [
            'menus'      => $this->menuGenerator->getMenu(),
            'rendezVous' => $rendezVous
        ];
    }
}

<?php

namespace App\Core\Service\Calendar;

use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Entity\RenderVous;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class CalendarList implements RenderInterface, InitialisationInterface
{
    private object  $user;

    const VIEW_PATH     = 'calendrierRenderVous/renderVousEffectuer.html.twig';

    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

    //InitialisationInterface

    /**
     * Initialisation
     *
     * @param array $params
     * @return void
     */
    public function init($param)
    {
        $this->user = $param['user'];
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
        $rendezVous = $this->em->getRepository(RenderVous::class)->findRealizeRendezVous($this->user->getId());

        return [
            'menus' => $this->menuGenerator->getMenu(),
            'current_page' => '',
            'rendezVous' => $rendezVous,
        ];
    }
}

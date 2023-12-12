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
     * Save form data
     *
     * @return string
     */
    public function view()
    {
        return 'calendrierRenderVous/renderVousEffectuer.html.twig';
    }

    /**
     * Save specific data
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

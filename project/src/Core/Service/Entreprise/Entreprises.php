<?php

namespace App\Core\Service\Entreprise;

use App\Core\Interface\RenderInterface;
use App\Entity\Entreprise;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class Entreprises implements RenderInterface
{
    const VIEW_PATH         = 'entrepriseProduits/index.html.twig';
    const CURRENT_PAGE      = 'entreprise';

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
        $entreprises = $this->em->getRepository(Entreprise::class)->findAll();

        return [
            'menus'         => $this->menuGenerator->getMenu(),
            'current_page'  => self::CURRENT_PAGE,
            'entreprises'   => $entreprises,
        ];
    }
}

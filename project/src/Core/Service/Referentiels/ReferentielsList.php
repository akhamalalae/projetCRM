<?php

namespace App\Core\Service\Referentiels;

use App\Core\Interface\RenderInterface;
use App\Entity\Referentiels;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class ReferentielsList implements RenderInterface
{
    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

    //RenderInterface

    /**
     * Save form data
     *
     * @return string
     */
    public function view()
    {
        return 'referentiels/index.html.twig';
    }

    /**
     * Save specific data
     *
     * @return array
     */
    public function parameters()
    {
        $referentiels = $this->em->getRepository(Referentiels::class)->findBy(['status' => false]);

        return [
            'menus' => $this->menuGenerator->getMenu(),
            'referentiels' => $referentiels,
        ];
    }
}

<?php

namespace App\Core\Service\Entreprise;

use App\Core\Interface\RenderInterface;
use App\Entity\Entreprise;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class Entreprises implements RenderInterface
{
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
        return 'entrepriseProduits/index.html.twig';
    }

    /**
     * current_page
     *
     * @return string
     */
    public function getCurrentPage()
    {
        return 'entreprise';
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
            'menus' => $this->menuGenerator->getMenu(),
            'current_page' => $this->getCurrentPage(),
            'entreprises' => $entreprises,
        ];
    }
}

<?php

namespace App\Core\Service\Formulaire;

use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Entity\Formulaire;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class FormulaireList implements RenderInterface, InitialisationInterface
{
    private object $user;

    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

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
        return 'formulaire/index.html.twig';
    }

    /**
     * parameters
     *
     * @return array
     */
    public function parameters()
    {
        $formulaires = $this->em->getRepository(Formulaire::class)->findBy(
            ['user' => $this->user,'status' => 0]
        );

        return [
            'menus'             => $this->menuGenerator->getMenu(),
            'current_page'      => 'formulaire',
            'countformulaires'  => count($formulaires),
            'formulaires'       => $formulaires
        ];
    }
}

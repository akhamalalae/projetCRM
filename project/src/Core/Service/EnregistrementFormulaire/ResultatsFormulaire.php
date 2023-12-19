<?php

namespace App\Core\Service\EnregistrementFormulaire;

use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Entity\ChampsFormulaire;
use App\Entity\EnregistrementFormulaire;
use App\Entity\Formulaire;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class ResultatsFormulaire implements RenderInterface, InitialisationInterface
{
    private int $id;

    const VIEW_PATH         = 'enregistrementFormulaire/formulaireResultats.html.twig';

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
        $this->id = $param['id'];
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
        $formulaire = $this->em->getRepository(Formulaire::class)->find($this->id);

        $champsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
            ['formulaire' => $this->id,'status' => 0]
        );
        $enregistrementFormulaire = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(
            ['formulaires' => $this->id]
        );

        return [
            'menus'                     => $this->menuGenerator->getMenu(),
            'formulaire'                => $formulaire,
            'enregistrementFormulaire'  => $enregistrementFormulaire,
            'champsFormulaires'         => $champsFormulaires
        ];
    }
}

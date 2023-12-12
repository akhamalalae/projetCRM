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
     * Save form data
     *
     * @return string
     */
    public function view()
    {
        return 'enregistrementFormulaire/formulaireResultats.html.twig';
    }

    /**
     * Save specific data
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
            'formulaire' => $formulaire,
            'enregistrementFormulaire' => $enregistrementFormulaire,
            'champsFormulaires' => $champsFormulaires
        ];
    }
}

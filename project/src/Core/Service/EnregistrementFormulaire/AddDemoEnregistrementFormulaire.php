<?php

namespace App\Core\Service\EnregistrementFormulaire;

use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Entity\ChampsFormulaire;
use App\Entity\Formulaire;
use App\Form\Formulaires\EnregistrementFormulaireType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class AddDemoEnregistrementFormulaire implements RenderInterface, InitialisationInterface, CreateFormInterface
{
    private int     $id;
    private array   $datachampsFormulaires = [];

    const VIEW_PATH         = 'enregistrementFormulaire/index.html.twig';

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
        $this->datachampsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
            ['formulaire' => $this->id,'status' => 0], ['ordre' => 'ASC']
        );
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

        return [
            'menus'      => $this->menuGenerator->getMenu(),
            'formulaire' => $formulaire
        ];
    }

    //CreateFormInterface

    /**
     * Set type create form
     *
     * @return string
     */
    public function formType()
    {
        return EnregistrementFormulaireType::class;
    }

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        return $this->datachampsFormulaires;
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject()
    {
        return new ChampsFormulaire();
    }

     /**
     * Set options create form
     *
     * @return array
     */
    public function formOptions()
    {
        return ['champsFormulaires' => $this->datachampsFormulaires];
    }

    /**
     * Set options create form
     *
     * @return array
     */
    public function formOtherOptions()
    {
        return [];
    }
}

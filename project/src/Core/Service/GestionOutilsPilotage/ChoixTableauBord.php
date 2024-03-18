<?php

namespace App\Core\Service\GestionOutilsPilotage;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\RequeteTableauBord;
use App\Form\TableauBord\ChoixTableauBordType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class ChoixTableauBord implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int                      $choix;
    private RequeteTableauBord|null  $requeteTableauBord;

    const VIEW_PATH                  = 'tableauBord/choixTableauBord.html.twig';
    const CURRENT_PAGE               = 'formulaire';
    const ROUTE                      = 'tableau_de_bord';
    const TYPE_FLASH                 = 'warning';
    const MESSAGE_FLASH_CREATE_BORD  = 'Créer un nouveau tableau de Bord';
    const MESSAGE_FLASH_CHARGER_BORD = 'Charger le tableau de Bord : ';

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
        return [
            'menus'        => $this->menuGenerator->getMenu(),
            'current_page' => self::CURRENT_PAGE
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
        return ChoixTableauBordType::class;
    }

    /**
     * Set name create form
     *
     * @return string
     */
    public function formName()
    {
        return 'form';
    }

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        return  null;
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject()
    {
        return new RequeteTableauBord();
    }

     /**
     * Set options create form
     *
     * @return array
     */
    public function formOptions()
    {
        return [];
    }

    //SubmittedFormInterface

    /**
     * Save form data
     *
     * @param Form $form
     * @return void
     */
    public function save($form)
    {
        $this->saveSpecific($form);
    }

    /**
     * Save specific data
     *
     * @param Form $form
     * @return void
     */
    public function saveSpecific($form)
    {
        $data                       = $form->getData();
        $this->requeteTableauBord   = $data["RequeteTableauBord"];
        $this->choix                = $data["choix"];
    }

    /**
     * Save
     * @return void
     */
    public function beforeSave()
    {
    }

     /**
     * Save
     *
     * @return void
     */
    public function afterSave()
    {
    }

    //RedirectToRouteInterface

    /**
     * Name route
     *
     * @return string
     */
    public function route()
    {
        return self::ROUTE;
    }

    /**
     * parametersRoute
     *
     * @return array
     */
    public function parametersRoute()
    {
        if ($this->choix === 0) {
            //'Créer un nouveau tableau de Bord' => 0,
            return ['id' => 0];
        }

        //'Charger un tableau de Bord' => 1,
        return ['id' => $this->requeteTableauBord->getId()];
    }

    //AddFlashInterface

    /**
     * Type Flash
     *
     * @return string
     */
    public function type()
    {
        return self::TYPE_FLASH;
    }

    /**
     * Message Flash
     *
     * @return string
     */
    public function message()
    {
        if ($this->choix === 0) {
            //'Créer un nouveau tableau de Bord' => 0,
            return self::MESSAGE_FLASH_CREATE_BORD;
        }

        //'Charger un tableau de Bord' => 1,
        return self::MESSAGE_FLASH_CHARGER_BORD . $this->requeteTableauBord?->getLibelle();
    }
}

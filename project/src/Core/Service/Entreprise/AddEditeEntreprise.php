<?php

namespace App\Core\Service\Entreprise;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\Entreprise;
use App\Form\Entreprises\EntrepriseType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class AddEditeEntreprise implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int $id = 0;

    const VIEW_PATH         = 'entrepriseProduits/entreprisesProduits.html.twig';
    const ROUTE             = 'entreprise';
    const TYPE_FLASH        = 'warning';
    const MESSAGE_FLASH     = 'Enregistrement effectué avec succès';

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
        return [
            'menus' => $this->menuGenerator->getMenu()
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
        return EntrepriseType::class;
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
        if ($this->id === 0) {
            return  $this->createNewObject();
        }

        return $this->em->getRepository(Entreprise::class)->find($this->id);
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject()
    {
        return new Entreprise();
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
        $this->em->persist($form->getData());
        $this->em->flush();
    }

    /**
     * Save specific data
     *
     * @param Form $form
     * @return void
     */
    public function saveSpecific($form)
    {
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
        return [];
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
        return self::MESSAGE_FLASH;
    }
}

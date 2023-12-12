<?php

namespace App\Core\Service\ConfigurationEspace;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\ConfigurationEspace;
use App\Entity\ConfigurationObjet;
use App\Form\ConfigurationEspace\ConfigurationObjetType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class AddConfigurationEspaceObject implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int    $id = 0;
    private object $data;

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
        $this->id   = $param['id'];
        $this->data = $this->em->getRepository(ConfigurationEspace::class)->find($this->id);
    }

    //RenderInterface

    /**
     * Save form data
     *
     * @return string
     */
    public function view()
    {
        return 'configurationEspace/configurationObjet.html.twig';
    }

    /**
     * Save specific data
     *
     * @return array
     */
    public function parameters()
    {
        $objets = $this->em->getRepository(ConfigurationObjet::class)->findByConfigurationEspace($this->data);

        return [
            'menus' => $this->menuGenerator->getMenu(),
            'configurationEspace' => $this->data,
            'configurationObjets' => $objets,
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
        return ConfigurationObjetType::class;
    }

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        return  $this->createNewObject();
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject()
    {
        return new ConfigurationObjet();
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
        $form->getData()->setConfigurationEspace($this->data);
    }

    /**
     * Save
     * @return void
     */
    public function saveBeforeSubmitFormData()
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
        return 'configuration_espace';
    }

    /**
     * parametersRoute
     *
     * @return array
     */
    public function parametersRoute()
    {
        return ['id' => $this->id];
    }

    //AddFlashInterface

    /**
     * Type Flash
     *
     * @return string
     */
    public function type()
    {
        return 'warning';
    }

    /**
     * Message Flash
     *
     * @return string
     */
    public function message()
    {
        return 'Enregistrement effectué avec succès';
    }
}

<?php

namespace App\Core\Service\Calendar;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\RenderVous;
use App\Form\RenderVous\RenderVousType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class AddEditeCalendar implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int     $id = 0;
    private object  $user;

    const VIEW_PATH_ADD    =  'calendrierRenderVous/addRendezVousForm.html.twig';
    const VIEW_PATH_EDITE  =  'calendrierRenderVous/editRendezVousForm.html.twig';

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
        if ($this->id === 0) {
            return self::VIEW_PATH_ADD;
        }

        return self::VIEW_PATH_EDITE;
    }

    /**
     * parameters
     *
     * @return array
     */
    public function parameters()
    {
        return [
            'calendar' => $this->formData(),
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
        return RenderVousType::class;
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

        return $this->em->getRepository(RenderVous::class)->findOneById($this->id);
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject()
    {
        return new RenderVous();
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
        if ($this->id === 0) {
            $form->getData()->setUserCreateur($this->user);
            $form->getData()->setDateCreation(new DateTime());
        }

        if ($this->id !== 0) {
            $form->getData()->setDateModification(new DateTime());
        }
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
        return '';
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
        return '';
    }

    /**
     * Message Flash
     *
     * @return string
     */
    public function message()
    {
        return '';
    }
}

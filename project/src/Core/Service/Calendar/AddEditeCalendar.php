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
            return 'calendrierRenderVous/addRendezVousForm.html.twig';
        }

        return 'calendrierRenderVous/editRendezVousForm.html.twig';
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
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        if ($this->id === 0) {
            return  $this->createNewObject();
        }

        return $this->em->getRepository(RenderVous::class)->findOneById($this->id);;
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

     /**
     * Set options create form
     *
     * @return array
     */
    public function formOtherOptions()
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
        if ($this->id !== 0) {
            $this->saveSpecific($form);
            $this->em->persist($form->getData());
            $this->em->flush();
        }
    }

    /**
     * Save specific data
     *
     * @param Form $form
     * @return void
     */
    public function saveSpecific($form)
    {
        $form->getData()->setUserCreateur($this->user);
        $form->getData()->setDateCreation(new DateTime());
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

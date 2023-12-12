<?php

namespace App\Core\Service\Calendar;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\RenderVous;
use App\Form\Intervenants\AgendaFiltreIntervenantsType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class Calendar implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int     $countRendezVous = 0;
    private string  $urlApi;
    private string  $urlApiGetTokenJWT;
    private object  $user;
    private $data;
    private $form;

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
        $this->user              = $param['user'];
        $this->urlApi            = $param['urlApi'];
        $this->urlApiGetTokenJWT = $param['urlApiGetTokenJWT'];

        $events = $this->em->getRepository(RenderVous::class)->findCalendarRendezVous($this->user->getId());
        $this->countRendezVous   = count($events);
        $this->data              = $this->rdvs($events);
    }

    //RenderInterface

    /**
     * Save form data
     *
     * @return string
     */
    public function view()
    {
        return 'calendrierRenderVous/renderVous.html.twig';
    }

    /**
     * Save specific data
     *
     * @return array
     */
    public function parameters()
    {
        return [
            'menus'             => $this->menuGenerator->getMenu(),
            'current_page'      => '',
            'countRendezVous'   => $this->countRendezVous,
            'data'              => $this->data,
            'urlApi'            => $this->urlApi,
            "urlApiGetTokenJWT" => $this->urlApiGetTokenJWT,
        ];
    }

    public function agendaFiltres($form)
    {
        $intervenants = array();
        $entreprises = array();
        $formulaire = array();
        $pointeVente = array();

        foreach ($form->getData()["intervenants"] as $value) {
            array_push($intervenants, $value->getId());
        }

        foreach ($form->getData()["entreprises"] as $value) {
            array_push($entreprises, $value->getId());
        }

        foreach ($form->getData()["formulaire"] as $value) {
            array_push($formulaire, $value->getId());
        }

        foreach ($form->getData()["pointeVente"] as $value) {
            array_push($pointeVente, $value->getId());
        }

        return $this->em->getRepository(RenderVous::class)->agendaFiltres(
            $intervenants,
            $entreprises,
            $formulaire,
            $pointeVente
        );

    }

    public function rdvs($events)
    {
        $rdvs = [];

        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
            ];
        }

        return json_encode($rdvs);
    }

    //CreateFormInterface

    /**
     * Set type create form
     *
     * @return string
     */
    public function formType()
    {
        return AgendaFiltreIntervenantsType::class;
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
        return null;
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
        $this->form = $form;
        $this->saveBeforeSubmitFormData();
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
    public function saveBeforeSubmitFormData()
    {
        $events                 = $this->agendaFiltres($this->form);
        $this->countRendezVous  = count($events);
        $this->data             = $this->rdvs($events);
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

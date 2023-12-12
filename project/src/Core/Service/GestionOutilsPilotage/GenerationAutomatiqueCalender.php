<?php

namespace App\Core\Service\GestionOutilsPilotage;

use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RedirectToRouteInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\HistoriqueGenerationAutomatiqueRouting;
use App\Form\Planning\GenerationAutomatiqueRendezVousType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class GenerationAutomatiqueCalender implements RenderInterface, InitialisationInterface, CreateFormInterface,
                                            SubmittedFormInterface, RedirectToRouteInterface
{
    private object   $user;
    private DateTime $dateDebut;

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
        return 'calendrierRenderVous/generationAutomatiqueRendezVous.html.twig';
    }

    /**
     * parameters
     *
     * @return array
     */
    public function parameters()
    {
        $routings = $this->em->getRepository(HistoriqueGenerationAutomatiqueRouting::class)->findBy(
            ['userCreateur' => $this->user]
        );

        return [
            'current_page' => 'automatisation_routing',
            'routings' => $routings
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
        return GenerationAutomatiqueRendezVousType::class;
    }

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        return null;
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
        $this->dateDebut = $form->getData()["start"];
        $ecart = $form->getData()["nbrMinutes"];
        $formulaires = $form->getData()["formulaires"];
        $dateExecution = $form->getData()["dateExecution"];
        $dateNoow = new DateTime();

        if (intval($this->dateDebut->format('H')) > 8 || intval($this->dateDebut->format('H')) < 18) {
            $this->historiqueGenerationAutomatiqueRouting(
                $this->dateDebut,
                $ecart,
                $formulaires,
                $dateNoow,
                $dateExecution
            );
        }
    }

    /**
     * Save specific data
     *
     * @param DateTime $dateDebut
     * @param int $ecart
     * @param array $formulaires
     * @param DateTime $dateNow
     * @param DateTime $dateExecution
     *
     * @return void
     */
    public function historiqueGenerationAutomatiqueRouting($dateDebut, $ecart, $formulaires, $dateNow, $dateExecution)
    {
        $historiqueGenerationAutomatiqueRouting = new HistoriqueGenerationAutomatiqueRouting();
        $historiqueGenerationAutomatiqueRouting->setDateDebut($dateDebut);
        $historiqueGenerationAutomatiqueRouting->setDateExecution($dateExecution);
        $historiqueGenerationAutomatiqueRouting->setEcartEnMunites($ecart);
        $historiqueGenerationAutomatiqueRouting->setDateCreation($dateNow);
        $historiqueGenerationAutomatiqueRouting->setIsGenerer(false);
        $historiqueGenerationAutomatiqueRouting->setUserCreateur($this->user);
        foreach ($formulaires as $formulaire) {
            $historiqueGenerationAutomatiqueRouting->addFormulaire($formulaire);
        }
        $this->em->persist($historiqueGenerationAutomatiqueRouting);
        $this->em->flush();
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
        if (intval($this->dateDebut->format('H')) > 8 || intval($this->dateDebut->format('H')) < 18) {
            return 'generation_automatique_rendez_vous';
        }
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
        if (intval($this->dateDebut->format('H')) < 8 || intval($this->dateDebut->format('H')) > 18) {
            return 'warning';
        }
    }

    /**
     * Message Flash
     *
     * @return string
     */
    public function message()
    {
        return "L'heure de la date  de début doit être comprise entre 08h et 18H!";
    }
}

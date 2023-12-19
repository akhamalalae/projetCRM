<?php

namespace App\Core\Service\GestionOutilsPilotage;

use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RedirectToRouteInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\HistoriqueGenerationAutomatiqueRouting;
use App\Form\Planning\GenerationAutomatiqueRendezVousType;
use App\Services\GenerationAutomatiqueRendezVous;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class GenerationAutomatiqueCalender implements RenderInterface, InitialisationInterface, CreateFormInterface,
                                            SubmittedFormInterface, RedirectToRouteInterface
{
    private object   $user;
    private DateTime $dateDebut;

    const VIEW_PATH         = 'calendrierRenderVous/generationAutomatiqueRendezVous.html.twig';
    const CURRENT_PAGE      = 'automatisation_routing';
    const ROUTE             = 'generation_automatique_rendez_vous';
    const TYPE_FLASH        = 'warning';
    const MESSAGE_FLASH     = "L'heure de la date  de début doit être comprise entre 08h et 18H!";

    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator,
        public GenerationAutomatiqueRendezVous $generationAutomatique)
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
        return self::VIEW_PATH;
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
            'menus'        => $this->menuGenerator->getMenu(),
            'current_page' => self::CURRENT_PAGE,
            'routings'     => $routings
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
        return new HistoriqueGenerationAutomatiqueRouting();
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
        $this->dateDebut    = $form->getData()["start"];
        $ecart              = $form->getData()["nbrMinutes"];
        $formulaires        = $form->getData()["formulaires"];
        $dateExecution      = $form->getData()["dateExecution"];
        $dateNoow           = new DateTime();

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
        $historiqueGenerationAutomatiqueRouting = $this->createNewObject();
        $historiqueGenerationAutomatiqueRouting->setDateDebut($dateDebut)
            ->setDateExecution($dateExecution)
            ->setEcartEnMunites($ecart)
            ->setDateCreation($dateNow)
            ->setIsGenerer(false)
            ->setUserCreateur($this->user);

        foreach ($formulaires as $formulaire) {
            $historiqueGenerationAutomatiqueRouting->addFormulaire($formulaire);
        }

        $this->generationAutomatique->create($historiqueGenerationAutomatiqueRouting);
        $historiqueGenerationAutomatiqueRouting->setIsGenerer(true);

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
            return self::ROUTE;
        }

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
        if (intval($this->dateDebut->format('H')) < 8 || intval($this->dateDebut->format('H')) > 18) {
            return self::TYPE_FLASH;
        }

        return '';
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

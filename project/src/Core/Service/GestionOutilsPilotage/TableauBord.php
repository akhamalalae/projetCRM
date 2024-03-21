<?php

namespace App\Core\Service\GestionOutilsPilotage;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\Entities;
use App\Entity\EntitiesPropriete;
use App\Entity\Formulaire;
use App\Entity\RequeteTableauBord;
use App\Entity\User;
use App\Form\TableauBord\TableauBordType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;

class TableauBord extends TableauBordCreateRequete implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int                 $id = 0;
    private array               $listesChamps = [];
    private array               $listesEntities = [];
    private ArrayCollection     $originalFiltres;
    private User                $user;

    const VIEW_PATH            = 'tableauBord/index.html.twig';
    const CURRENT_PAGE         = 'Tableau De Bord!';
    const FORM_NAME            = 'form';
    const ROUTE                = 'tableau_de_bord';
    const TYPE_FLASH_WARNING   = 'warning';
    const TYPE_FLASH_SUCCESS   = 'success';
    const MESSAGE_FLASH        = "Requête effectué avec succés!";

    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

    //InitialisationInterface

     /**
     * Initialisation
     *
     * @param array $param
     *
     * @return void
     */
    public function init($param): void
    {
        $this->id               = $param['id'];
        $this->user             = $param['user'];
        $this->listesChamps     = $this->em->getRepository(EntitiesPropriete::class)->findBy(['status' => 0]);
        $this->listesEntities   = $this->em->getRepository(Entities::class)->findBy(['status' => 0]);

        if ($this->id !== 0) {
            $this->requeteTableauBord = $this->getRequeteTableauBordById();

            // Create an ArrayCollection of the current objects in the database
            $this->originalFiltres = new ArrayCollection();
            
            foreach ($this->requeteTableauBord->getRequeteTableauBordFiltres() as $filtre) {
                $this->originalFiltres->add($filtre);
            }
        }
    }

    //RenderInterface

     /**
     * View
     *
     * @return string
     */
    public function view(): string
    {
        return self::VIEW_PATH;
    }

     /**
     * Parameters view
     *
     * @return array
     */
    public function parameters(): array
    {
        $nombreFiltres = count($this->requeteTableauBord?->getRequeteTableauBordFiltres());

        return [
            'menus'                         => $this->menuGenerator->getMenu(),
            "nombreFiltres"                 => $nombreFiltres,
            'libelleResultatsRequeteTB'     => $this->requeteTableauBord?->listChampsClauseSelect(),
            'resultatsRequeteTableauBord'   => $this->getResultatsRequeteTableauBord(),
            'currentPage'                   => self::CURRENT_PAGE,
            'requeteTableauBord'            => $this->requeteTableauBord,
            'listesChamps'                  => $this->listesChamps,
            'listesEntities'                => $this->listesEntities
        ];
    }

    //CreateFormInterface

     /**
     * Set type create form
     *
     * @return string
     */
    public function formType(): string
    {
        return TableauBordType::class;
    }

     /**
     * Set name create form
     *
     * @return string
     */
    public function formName(): string
    {
        return self::FORM_NAME;
    }

     /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData(): object|null
    {
        if ($this->id === 0) {
            //'Créer un nouveau tableau de Bord'
            return $this->requeteTableauBord = $this->createNewObject();
        }

        //'Charger un tableau de Bord'
        return $this->requeteTableauBord = $this->getRequeteTableauBordById();
    }

     /**
     * Set options create form
     *
     * @return array
     */
    public function formOptions(): array
    {
        return [];
    }

    //SubmittedFormInterface

     /**
     * Save form data
     *
     * @param Form $form
     *
     * @return void
     */
    public function save($form): void
    {
        $this->requeteTableauBord = $form->getData();
        
        if ($this->requeteTableauBord?->getEnregistrerRequete() === true && $this->warningInValidSyntax() === false) {
            $this->beforeSave();

            $this->saveSpecific($form);

            $this->em->persist($this->requeteTableauBord);
            $this->em->flush();

            $this->afterSave();
        }
    }

     /**
     * Save specific data
     *
     * @param Form $form
     *
     * @return void
     */
    public function saveSpecific($form): void
    {
        if ($this->id !== 0) {
            foreach ($this->requeteTableauBord->getRequeteTableauBordFiltres() as $filtre) {
                if ($this->originalFiltres->contains($filtre)) {
                    $filtre->setDateModification(new DateTime())
                            ->setUserModificateur($this->user);
                } else {
                    $filtre->setUserCreateur($this->user);
                }
            }
        }
    }

    /**
     * BeforeSave
     *
     * @return void
     */
    public function beforeSave(): void
    {
        if ($this->id === 0) {
            $this->requeteTableauBord->setDateCreation(new DateTime())
                    ->setUserCreateur($this->user);
        }

        if ($this->id !== 0) {
            $this->requeteTableauBord->setDateModification(new DateTime())
                    ->setUserModificateur($this->user);
        }
    }

     /**
     * AfterSave
     *
     * @return void
     */
    public function afterSave(): void
    {
    }

    //RedirectToRouteInterface

     /**
     * Name route
     *
     * @return string
     */
    public function route(): string
    {
        if ($this->conditionsRedirectToRoute()) {
            return self::ROUTE;
        }

        return '';
    }

     /**
     * ParametersRoute
     *
     * @return array
     */
    public function parametersRoute(): array
    {
        if ($this->conditionsRedirectToRoute()) {
            return ['id' => $this->requeteTableauBord?->getId()];
        }

        return [];
    }

    /**
     * Conditions Redirect To Route
     *
     * @return bool
     */
    public function conditionsRedirectToRoute(): bool
    {
        return $this->id === 0 &&
               $this->warningInValidSyntax() === false &&
               $this->requeteTableauBord->getEnregistrerRequete() === true;
    }

    //AddFlashInterface

     /**
     * Type Flash
     *
     * @return string
     */
    public function type(): string
    {
        if ($this->warningInValidSyntax() === true) {
            return self::TYPE_FLASH_WARNING;
        }

        return self::TYPE_FLASH_SUCCESS;
    }

     /**
     * Message Flash
     *
     * @return string
     */
    public function message(): string
    {
        if ($this->warningInValidSyntax() === true) {
            return $this->messageInValidSyntax();
        }

        return self::MESSAGE_FLASH;
    }

     /**
     * Create new object
     *
     * @return RequeteTableauBord|null
     */
    public function createNewObject(): RequeteTableauBord|null
    {
        return new RequeteTableauBord();
    }

    /**
     * Get Requete Tableau Bord By Id
     *
     * @return RequeteTableauBord|null
     */
    public function getRequeteTableauBordById():RequeteTableauBord|null
    {
        return $this->em->getRepository(RequeteTableauBord::class)->find($this->id);
    }

     /**
     * Get resultats Requete Tableau Bord
     *s
     * @return array
     */
    public function getResultatsRequeteTableauBord(): array
    {
        if ($this->warningInValidSyntax() === false) {
            return $this->em->getRepository(Formulaire::class)->getResultRequete($this->query());
        }

        return [];
    }

     /**
     * Warning valid syntax
     *
     * @return bool
     */
    public function warningInValidSyntax(): bool
    {
        return $this->requeteTableauBord->checkRequeteValidSyntax()['warning'];
    }

     /**
     * Message valid syntax
     *
     * @return string
     */
    public function messageInValidSyntax(): string
    {
        return $this->requeteTableauBord->checkRequeteValidSyntax()['message'];
    }
}


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
use App\Form\TableauBord\TableauBordType;
use App\Services\MenuGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class TableauBord extends TableauBordCreateRequete implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int                 $id = 0;
    private ArrayCollection     $orignalRequeteTableauBordFiltres;
    private array|null          $resultatsRequeteTableauBord = null;
    private array               $listesChamps = [];
    private array               $listesEntities = [];

    const VIEW_PATH         = 'tableauBord/index.html.twig';
    const CURRENT_PAGE      = 'tableauBord';
    const FORM_NAME         = 'form';
    const ROUTE             = 'tableau_de_bord';
    const TYPE_FLASH        = 'warning';
    const MESSAGE_FLASH     = "Requête effectué avec succés!";

    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

    //InitialisationInterface

     /**
     * Initialisation
     *
     * @param array $params
     *
     * @return void
     */
    public function init($param)
    {
        $this->id               = $param['id'];
        $this->listesChamps     = $this->em->getRepository(EntitiesPropriete::class)->findBy(['status' => 0]);
        $this->listesEntities   = $this->em->getRepository(Entities::class)->findBy(['status' => 0]);

        if ($this->id !== 0) {
            //'Charger un tableau de Bord'
            $this->requeteTableauBord = $this->getRequeteTableauBordById();

            if ($this->requeteTableauBord) {

                $this->orignalRequeteTableauBordFiltres = new ArrayCollection();

                foreach ($this->requeteTableauBord->getRequeteTableauBordFiltres() as $champ) {
                    $this->orignalRequeteTableauBordFiltres->add($champ);
                }
            }
        }
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
        $this->getResultatsRequeteTableauBord();

        return [
            'menus'                         => $this->menuGenerator->getMenu(),
            'current_page'                  => self::CURRENT_PAGE,
            'resultatsRequeteTableauBord'   => $this->resultatsRequeteTableauBord,
            'libelleResultatsRequeteTB'     =>$this->libelleClauseSelect,
            'listes_champs'                 => $this->listesChamps ,
            'listes_entities'               => $this->listesEntities,
            'requete_tableau_bord_id'       => $this->id,
            'requete_tableau_bord'          => $this->requeteTableauBord,
            "nombreFiltres"                 => count($this->requeteTableauBord->getRequeteTableauBordFiltres())
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
        return TableauBordType::class;
    }

     /**
     * Set name create form
     *
     * @return string
     */
    public function formName()
    {
        return self::FORM_NAME;
    }

     /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        if ($this->id === 0) {
            //'Créer un nouveau tableau de Bord'
            return $this->requeteTableauBord = $this->createNewObject();
        }

        //'Charger un tableau de Bord'
        return $this->requeteTableauBord = $this->getRequeteTableauBordById();
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
     * Get Requete Tableau Bord By Id
     *
     * @return RequeteTableauBord
     */
    public function getRequeteTableauBordById():RequeteTableauBord
    {
        return $this->em->getRepository(RequeteTableauBord::class)->find($this->id);
    }

     /**
     * Set options create form
     *
     * @return array
     */
    public function formOptions()
    {
        return [
            'listes_champs' => $this->listesChamps,
            'em'            => $this->em,
        ];
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
     *
     * @return void
     */
    public function save($form)
    {
        $this->requeteTableauBord = $form->getData();

        $this->saveSpecific($form);

        if ($this->requeteTableauBord->getEnregistrerRequete() === true) {
            $this->em->persist($this->requeteTableauBord);
            $this->em->flush();
        }

        $this->afterSave();
    }

     /**
     * Save specific data
     *
     * @param Form $form
     * @return void
     */
    public function saveSpecific($form)
    {
        $this->beforeSave();
    }

    /**
     * Save
     *
     * @return void
     */
    public function beforeSave()
    {
        if ($this->id === 0) {
            $this->requeteTableauBord->setDateCreation(new DateTime());
        }

        if ($this->id !== 0) {
            $this->requeteTableauBord->setDateModification(new DateTime());
        }
    }

     /**
     * Save
     *
     * @return void
     */
    public function afterSave()
    {
        $this->getResultatsRequeteTableauBord();
    }

    //RedirectToRouteInterface

     /**
     * Name route
     *
     * @return string
     */
    public function route()
    {
        if ($this->id === 0 && $this->warningIncorrectSyntax === false) {
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
        if ($this->id === 0 && $this->warningIncorrectSyntax === false) {
            return ['id' => $this->requeteTableauBord?->getId()];
        }

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
        if ($this->warningIncorrectSyntax === true) {
            return $this->messageFlash;
        }

        return self::MESSAGE_FLASH;
    }

     /**
     * Get resultats Requete Tableau Bord
     *
     * @return void
     */
    public function getResultatsRequeteTableauBord()
    {
        $this->checkIfTheQuerySyntaxIsCorrect();

        if ($this->warningIncorrectSyntax === false) {
            $this->resultatsRequeteTableauBord = $this->em->getRepository(Formulaire::class)
                    ->requeteTableauBordFiltres($this->createRequete());
        }
    }
}

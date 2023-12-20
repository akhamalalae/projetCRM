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
use App\Entity\Typeschamps;
use App\Form\TableauBord\TableauBordType;
use App\Services\MenuGenerator;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;

class TableauBord implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int             $id;
    private array           $listesChamps;
    private array           $listesEntities;
    private ArrayCollection $orignalRequeteTableauBordFiltres;
    private object          $requeteTableauBord;
    private bool            $warning = false;
    private array|null      $resultatsRequeteTableauBord = null;
    private bool            $checkValidateSyntaxe = false;

    const VIEW_PATH         = 'tableauBord/index.html.twig';
    const CURRENT_PAGE      = 'formulaire';
    const TYPE_FLASH        = 'warning';
    const MESSAGE_FLASH_1   = "Veuillez choisir les filtres de la requête!";
    const MESSAGE_FLASH_2   = "Veuillez choisir les champs a afficher!";
    const MESSAGE_FLASH_3   = "Veuillez remplir le nom de la requête!";
    const MESSAGE_FLASH_4   = "Erreur de syntaxe du champ Valeur des filtres, veuillez regarder la documentation!";

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
        $this->id               = $param['id'];
        $this->listesChamps     = $this->em->getRepository(EntitiesPropriete::class)->findBy(['status' => 0]);
        $this->listesEntities   = $this->em->getRepository(Entities::class)->findBy(['status' => 0]);

        $this->orignalRequeteTableauBordFiltres = new ArrayCollection();

        if ($this->id === 0) {
            //'Créer un nouveau tableau de Bord' => 0,
            $this->requeteTableauBord = $this->createNewObject();
        }

        if ($this->id !== 0) {
            //'Charger un tableau de Bord' => 1,
            $this->requeteTableauBord = $this->em->getRepository(RequeteTableauBord::class)->find($this->id);

            foreach ($this->requeteTableauBord->getRequeteTableauBordFiltres() as $champ) {
                $this->orignalRequeteTableauBordFiltres->add($champ);
            }
        }

        $this->checkValidateSyntaxe = $this->checkValidateSyntaxeChampValeur(
            $this->requeteTableauBord->getRequeteTableauBordFiltres()
        );
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
            'menus'                         => $this->menuGenerator->getMenu(),
            'current_page'                  => self::CURRENT_PAGE,
            'resultatsRequeteTableauBord'   => $this->resultatsRequeteTableauBord,
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
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        if ($this->id === 0) {
            //'Créer un nouveau tableau de Bord' => 0,
            return $this->requeteTableauBord = $this->createNewObject();
        }

        //'Charger un tableau de Bord' => 1,
        return $this->requeteTableauBord = $this->em->getRepository(RequeteTableauBord::class)->find($this->id);
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
     * @return void
     */
    public function save($form)
    {
        $this->saveSpecific($form);

        if ($this->warning === false) {
            $this->em->persist($form->getData());

            $requeteSQL = $this->createRequete($form->getData());

            $this->resultatsRequeteTableauBord = $this->changeLabelFormatResultats(
                $this->em->getRepository(Formulaire::class)->requeteTableauBordFiltres($requeteSQL)
            );

            // Ne pas enregistrer la requête
            if ($this->id === 0 && $form->getData()->getEnregistrerRequete() === false) {
                $this->em->remove($form->getData());
            }

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
        if (count($this->requeteTableauBord->getRequeteTableauBordFiltres()) === 0) {
            $this->warning = true;
        }

        if (count($this->requeteTableauBord->getPropertiesEntityChoixChamps()) === 0) {
            $this->warning = true;
        }

        if (
            $this->id == 0
            && $this->requeteTableauBord->getEnregistrerRequete() === true
            && $this->requeteTableauBord->getLibelle() === ''
        ) {
            $this->warning = true;
        }

        if ($this->checkValidateSyntaxe  === true) {
            $this->warning = true;
        }
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
        return self::TYPE_FLASH;
    }

    /**
     * Message Flash
     *
     * @return string
     */
    public function message()
    {
        $message = '';

        if (count($this->requeteTableauBord->getRequeteTableauBordFiltres()) === 0) {
            $message = self::MESSAGE_FLASH_1;
        }

        if (count($this->requeteTableauBord->getPropertiesEntityChoixChamps()) === 0) {
            $message = self::MESSAGE_FLASH_2;
        }

        if (
            $this->id === 0
            && $this->requeteTableauBord->getEnregistrerRequete() === true
            && $this->requeteTableauBord->getLibelle() === ''
        ) {
            $message = self::MESSAGE_FLASH_3;
        }

        if ($this->checkValidateSyntaxe === true) {
            $message = self::MESSAGE_FLASH_4;
        }

        return $message;
    }

    /**
     * réécrire le format de résultat de la requete
     *
     * @param array $resultatsRequeteTableauBord
     *
     * @return array
     */
    public function changeLabelFormatResultats(array $resultatsRequeteTableauBord)
    {
        foreach ($resultatsRequeteTableauBord as $key => $var) {
            foreach ($var as $keychamp => $varchamp) {
                $explode = explode("_", $keychamp);

                $champ = $this->em->getRepository(EntitiesPropriete::class)->find($explode[1]);

                $resultatsRequeteTableauBord[$key][
                    $champ->getName()." (".$champ->getEntitie()->getLibelle().")"
                ] = $varchamp;

                unset($resultatsRequeteTableauBord[$key][$keychamp]);
            }
        }

        return $resultatsRequeteTableauBord;
    }

    /**
     * vérifier la valeur saisie dans les filtres de la requete
     *
     * @param PersistentCollection $filtres
     *
     * @return bool
     */
    public function checkValidateSyntaxeChampValeur(PersistentCollection $filtres)
    {
        $erreur = false;

        foreach ($filtres as $filtre) {
            $valeur = $filtre->getValeur();

            if ($filtre->getEntitiesPropriete()->getTypesChamps()->getId() === Typeschamps::DATETYPE) {
                $format = 'Y/m/d H:i';
                $checkDateFormat = DateTimeImmutable::createFromFormat($format, $valeur);
                if($checkDateFormat === false) {
                    $erreur = true;
                    break;
                }
            }

            if ($filtre->getEntitiesPropriete()->getTypesChamps()->getId() === Typeschamps::BOOLEANTYPE) {
                $valeur = $valeur === '0'? 0: 1;
            }
        }

        return $erreur;
    }

    /**
     * creation requete sql
     *
     * @param RequeteTableauBord $requeteTableauBord
     *
     * @return string
     */
    public function createRequete(RequeteTableauBord $requeteTableauBord)
    {
        $clauseWhere = '';
        $clauseSelect = '';
        $jointure = array();

        foreach ($requeteTableauBord->getPropertiesEntityChoixChamps() as $champ) {
            $property = $champ->getLibelle();
            $id = $champ->getId();
            $entityLibelle = $champ->getEntitie()->getLibelle();

            if ($clauseSelect !== '') {
                $clauseSelect .= " ,";
            }

            $clauseSelect .= lcfirst($entityLibelle) . "." . $property . " AS " . $property . "_" . $id;

            $jointure = $this->createJoint($champ, $entityLibelle, $jointure);
        }

        foreach ($requeteTableauBord->getRequeteTableauBordFiltres() as $filtre) {
            $property = $filtre->getEntitiesPropriete()->getLibelle();
            $operator = $filtre->getTableauBordFiltreOperator()->getLibelle();
            $valeur = $filtre->getValeur();

            if ($operator == "Contient") {
                $operator = "LIKE";
                $valeur = "%$valeur%";
            }

            $condition = "";
            if ($filtre->getTableauBordFiltreCondition() != '') {
                $condition = $filtre->getTableauBordFiltreCondition()->getLibelle();
            }

            $nomJointureFiltre = lcfirst($filtre->getEntitiesPropriete()->getEntitie()->getLibelle());

            $clauseWhere .= " $condition $nomJointureFiltre.$property $operator '$valeur' ";

            $champ = $filtre->getEntitiesPropriete();
            $entityLibelle = $filtre->getEntitie()->getLibelle();

            $jointure = $this->createJoint($champ, $entityLibelle, $jointure);
        }

        $clauseJiointures = "";
        foreach ($jointure as $uneJointure) {
            $clauseJiointures .= " $uneJointure";
        }

        return "SELECT $clauseSelect FROM App\Entity\Formulaire formulaire $clauseJiointures WHERE $clauseWhere";
    }

    /**
     * creation des joitures de la requete sql
     *
     * @param EntitiesPropriete $champ
     * @param string $entityLibelle
     * @param array $jointure
     *
     * @return array
     */
    public function createJoint(EntitiesPropriete $champ, string $entityLibelle, array $jointure)
    {
        if ($champ->getEntitieJoiture() != null && ! array_key_exists($entityLibelle, $jointure)) {
            $propertyEntityJointure = $champ->getEntitieJoiture()->getLibelle();
            $entityNomJointure = $champ->getEntitie()->getNomProprieteeJointure();

            $jointure[$entityLibelle] =
                " LEFT JOIN "
                . lcfirst($propertyEntityJointure)
                . "." . $entityNomJointure
                . " " . lcfirst($entityLibelle);
        }

        return $jointure;
    }
}

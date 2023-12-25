<?php

namespace App\Core\Service\GestionOutilsPilotage;

use App\Entity\EntitiesPropriete;
use App\Entity\RequeteTableauBord;
use App\Entity\Typeschamps;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class TableauBordCreateRequete
{
    protected RequeteTableauBord  $requeteTableauBord;
    protected array               $jointure = [];
    protected array               $libelleClauseSelect = [];
    protected string              $clauseSelect = '';
    protected string              $clauseWhere = '';
    protected string              $clauseGroupeBy = '';
    protected string              $messageFlash = '';
    protected bool                $warningIncorrectSyntax = false;
    protected bool                $checkIfValid = false;

    const MESSAGE_FLASH_1   = "Veuillez choisir les filtres de la requête!";
    const MESSAGE_FLASH_2   = "Veuillez choisir les champs a afficher!";
    const MESSAGE_FLASH_3   = "Veuillez remplir le nom de la requête!";
    const MESSAGE_FLASH_4   = "Erreur de syntaxe du champ Valeur des filtres, veuillez regarder la documentation!";

    protected function __construct(public EntityManagerInterface $em)
    {
    }

     /**
     * creation requete sql
     *
     * @return string
     */
    protected function createRequete()
    {
        $this->createRequeteChoixChamps();

        $this->createRequeteFiltres();

        $clauseJiointures = implode(' ',$this->jointure);

        return sprintf(
            "SELECT %s FROM App\Entity\Formulaire formulaire %s WHERE %s GROUP BY %s",
            $this->clauseSelect,
            $clauseJiointures,
            $this->clauseWhere,
            $this->clauseGroupeBy
        );
    }

     /**
     * choix champs
     *
     * @return void
     */
    protected function createRequeteChoixChamps()
    {
        foreach ($this->requeteTableauBord->getPropertiesEntityChoixChamps() as $champ) {
            $property         = $champ->getLibelle();
            $propertyName     = $champ->getName();
            $id               = $champ->getId();
            $entityLibelle    = $champ->getEntitie()->getLibelle();
            $clauseSelectName = sprintf("%s_%s", $property, $id);

            if (strpos($this->clauseSelect, $clauseSelectName) === false) {
                $this->libelleClauseSelect[$clauseSelectName] = sprintf(
                    "%s (%s)",
                    $propertyName,
                    $entityLibelle
                );

                $this->clauseGroupeBy .= sprintf(
                    "%s %s",
                    ($this->clauseGroupeBy) !== '' ? ',' : '',
                    $clauseSelectName
                );

                $this->clauseSelect .= sprintf(
                    "%s %s.%s AS %s",
                    ($this->clauseSelect) !== '' ? ',' : '',
                    lcfirst($entityLibelle),
                    $property,
                    $clauseSelectName
                );

                $this->createJoint($champ, $entityLibelle);
            }
        }
    }

     /**
     * filtres
     *
     * @return void
     */
    protected function createRequeteFiltres()
    {
        foreach ($this->requeteTableauBord->getRequeteTableauBordFiltres() as $filtre) {
            $property            = $filtre->getEntitiesPropriete();
            $propertyLibelle     = $property->getLibelle();
            $operator            = $filtre->getTableauBordFiltreOperator()->getLibelle();
            $valeur              = $filtre->getValeur();
            $nomJointureProperty = sprintf("%s.%s", lcfirst($property->getEntitie()->getLibelle()), $propertyLibelle);
            $entityLibelle       = $filtre->getEntitie()->getLibelle();

            if (strpos($this->clauseWhere, $nomJointureProperty) === false) {
                if ($operator == "Contient") {
                    $operator = "LIKE";
                    $valeur = "%$valeur%";
                }

                $condition = "";
                if ($filtre->getTableauBordFiltreCondition() != '') {
                    $condition = $filtre->getTableauBordFiltreCondition()->getLibelle();
                }

                $this->clauseWhere .= " $condition $nomJointureProperty $operator '$valeur' ";

                $this->createJoint($property, $entityLibelle);
            }
        }
    }

     /**
     * creation des joitures de la requete sql
     *
     * @param EntitiesPropriete $champ
     * @param string $entityLibelle
     *
     * @return void
     */
    protected function createJoint(EntitiesPropriete $champ, string $entityLibelle)
    {
        if ($champ->getEntitieJoiture() != null && ! array_key_exists($entityLibelle, $this->jointure)) {
            $propertyEntityJointure = $champ->getEntitieJoiture()->getLibelle();
            $entityNomJointure      = $champ->getEntitie()->getNomProprieteeJointure();

            $this->jointure[$entityLibelle] = sprintf(
                " LEFT JOIN %s.%s %s",
                lcfirst($propertyEntityJointure),
                $entityNomJointure,
                lcfirst($entityLibelle)
            );
        }
    }

    /**
     * Check If The Query Syntax Is Correct
     *
     * @return void
     */
    protected function checkIfTheQuerySyntaxIsCorrect()
    {
        if (count($this->requeteTableauBord->getRequeteTableauBordFiltres()) === 0) {
            $this->warningIncorrectSyntax   = true;
            $this->messageFlash             = self::MESSAGE_FLASH_1;
        }

        if (count($this->requeteTableauBord->getPropertiesEntityChoixChamps()) === 0) {
            $this->warningIncorrectSyntax   = true;
            $this->messageFlash             = self::MESSAGE_FLASH_2;
        }

        if (
            $this->requeteTableauBord->getEnregistrerRequete() === true
            && $this->requeteTableauBord->getLibelle() === null
        ) {
            $this->warningIncorrectSyntax   = true;
            $this->messageFlash             = self::MESSAGE_FLASH_3;
        }

        $this->checkValidSyntaxe($this->requeteTableauBord->getRequeteTableauBordFiltres());

        if ($this->checkIfValid === true) {
            $this->warningIncorrectSyntax   = true;
            $this->messageFlash             = self::MESSAGE_FLASH_4;
        }
    }

     /**
     * vérifier la valeur saisie dans les filtres de la requete
     *
     * @return void
     */
    protected function checkValidSyntaxe($filtres)
    {
        foreach ($filtres as $filtre) {
            $valeur = $filtre->getValeur();

            if ($filtre->getEntitiesPropriete()->getTypesChamps()->getId() === Typeschamps::DATETYPE) {
                $format = 'Y/m/d H:i';
                $checkDateFormat = DateTimeImmutable::createFromFormat($format, $valeur);
                if($checkDateFormat === false) {
                    $this->checkIfValid  = true;
                    break;
                }
            }

            if ($filtre->getEntitiesPropriete()->getTypesChamps()->getId() === Typeschamps::BOOLEANTYPE) {
                $valeur = $valeur === '0' ? 0: 1;
            }
        }
    }
}

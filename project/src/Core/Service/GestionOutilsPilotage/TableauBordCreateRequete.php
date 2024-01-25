<?php

namespace App\Core\Service\GestionOutilsPilotage;

use App\Entity\EntitiesPropriete;
use App\Entity\RequeteTableauBord;
use Doctrine\ORM\EntityManagerInterface;

class TableauBordCreateRequete
{
    protected RequeteTableauBord  $requeteTableauBord;
    protected array               $jointures = [];
    protected string              $clauseSelect = '';
    protected string              $clauseWhere = '';
    protected string              $clauseGroupeBy = '';

    protected function __construct(public EntityManagerInterface $em)
    {
    }

     /**
     * creation requete sql
     *
     * @return string
     */
    protected function query(): string
    {
        $this->createRequeteChoixChamps();

        $this->createRequeteFiltres();

        return sprintf(
            "SELECT DISTINCT %s FROM App\Entity\Formulaire formulaire %s WHERE %s GROUP BY %s",
            $this->clauseSelect,
            implode(' ',$this->jointures),
            $this->clauseWhere,
            $this->clauseGroupeBy
        );
    }

     /**
     * choix champs
     *
     * @return void
     */
    protected function createRequeteChoixChamps(): void
    {
        foreach ($this->requeteTableauBord->getPropertiesEntityChoixChamps() as $champ) {
            if (strpos($this->clauseSelect, $champ->clauseSelectName()) === false) {
                $this->createClauseSelect($champ);

                $this->createClauseGroupeBy($champ);

                $this->createJoint($champ);
            }
        }
    }

     /**
     * Création clause where
     *
     * @return void
     */
    protected function createRequeteFiltres(): void
    {
        foreach ($this->requeteTableauBord->getRequeteTableauBordFiltres() as $filtre) {
            $property      = $filtre->getEntitiesPropriete();
            $jointureName  = $property->jointureName();

            if (strpos($this->clauseWhere, $jointureName) === false) {
                $this->clauseWhere .= $filtre->createRequeteClauseWhere();

                $this->createJoint($property);
            }
        }
    }

     /**
     * Création clause select
     *
     * @param EntitiesPropriete $champ
     *
     * @return void
     */
    protected function createClauseSelect(EntitiesPropriete $champ): void
    {
        $this->clauseSelect .= sprintf(
            "%s %s",
            ($this->clauseSelect) !== '' ? ',' : '',
            $champ->createRequeteClauseSelect()
        );
    }

     /**
     * Creation clause GroupeBy
     *
     * @param EntitiesPropriete $champ
     *
     * @return void
     */
    protected function createClauseGroupeBy(EntitiesPropriete $champ): void
    {
        if ($champ->getFonctionAgregation() === null) {
            $this->clauseGroupeBy .= sprintf(
                "%s %s",
                ($this->clauseGroupeBy) !== '' ? ',' : '',
                $champ->createRequeteClauseGroupeBy()
            );
        }
    }

     /**
     * Creation des joitures de la requete sql
     *
     * @param EntitiesPropriete $champ
     *
     * @return void
     */
    protected function createJoint(EntitiesPropriete $champ): void
    {
        $libelleJointure = $champ->getEntitie()?->getLibelle();

        if ($champ->getEntitieJoiture() != null && ! array_key_exists($libelleJointure, $this->jointures)) {
            $this->jointures[$libelleJointure] = $champ->createRequeteJoint();
        }
    }
}

<?php

namespace App\Entity;

use App\Repository\RequeteTableauBordFiltresRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequeteTableauBordFiltresRepository::class)
 */
class RequeteTableauBordFiltres
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $valeur;

    /**
     * @ORM\ManyToOne(targetEntity=RequeteTableauBord::class, inversedBy="requeteTableauBordFiltres")
     */
    public $requete_tableau_bord;

    /**
     * @ORM\ManyToOne(targetEntity=TableauBordFiltresConditions::class, inversedBy="requeteTableauBordFiltres")
     */
    public $tableau_bord_filtre_condition;

    /**
     * @ORM\ManyToOne(targetEntity=TableauBordFiltresOperators::class, inversedBy="requeteTableauBordFiltres")
     */
    public $tableau_bord_filtre_operator;

    /**
     * @ORM\ManyToOne(targetEntity=EntitiesPropriete::class, inversedBy="requeteTableauBordFiltres")
     */
    public $entities_propriete;

    /**
     * @ORM\ManyToOne(targetEntity=Entities::class, inversedBy="requeteTableauBordFiltres")
     */
    private $entitie;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getRequeteTableauBord(): ?RequeteTableauBord
    {
        return $this->requete_tableau_bord;
    }

    public function setRequeteTableauBord(?RequeteTableauBord $requete_tableau_bord): self
    {
        $this->requete_tableau_bord = $requete_tableau_bord;

        return $this;
    }

    public function getTableauBordFiltreCondition(): ?TableauBordFiltresConditions
    {
        return $this->tableau_bord_filtre_condition;
    }

    public function setTableauBordFiltreCondition(?TableauBordFiltresConditions $tableau_bord_filtre_condition): self
    {
        $this->tableau_bord_filtre_condition = $tableau_bord_filtre_condition;

        return $this;
    }

    public function getTableauBordFiltreOperator(): ?TableauBordFiltresOperators
    {
        return $this->tableau_bord_filtre_operator;
    }

    public function setTableauBordFiltreOperator(?TableauBordFiltresOperators $tableau_bord_filtre_operator): self
    {
        $this->tableau_bord_filtre_operator = $tableau_bord_filtre_operator;

        return $this;
    }

    public function getEntitiesPropriete(): ?EntitiesPropriete
    {
        return $this->entities_propriete;
    }

    public function setEntitiesPropriete(?EntitiesPropriete $entities_propriete): self
    {
        $this->entities_propriete = $entities_propriete;

        return $this;
    }

    public function getEntitie(): ?Entities
    {
        return $this->entitie;
    }

    public function setEntitie(?Entities $entitie): self
    {
        $this->entitie = $entitie;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function createRequeteClauseWhere(): string
    {
        $property            = $this->getEntitiesPropriete();
        $operator            = $this->getTableauBordFiltreOperator()->getLibelle();
        $valeur              = $this->getValeur();
        $jointuretName       = $property->jointureName();
        $condition           = '';

        if ($operator == "Contient") {
            $operator = "LIKE";
            $valeur = "%$valeur%";
        }

        if ($this->getTableauBordFiltreCondition() !== null) {
            $condition = $this->getTableauBordFiltreCondition()->getLibelle();
        }

        return " $condition $jointuretName $operator '$valeur' ";
    }
}

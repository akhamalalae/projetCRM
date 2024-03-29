<?php

namespace App\Entity;

use App\Repository\TableauBordFiltresConditionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableauBordFiltresConditionsRepository::class)
 */
class TableauBordFiltresConditions
{
    const CONDITION_ET = 1;
    const CONDITION_OU = 2;

    const CONDITION_EN_ET = 'AND';
    const CONDITION_EN_OU = 'OR';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=RequeteTableauBordFiltres::class, mappedBy="tableau_bord_filtre_condition")
     */
    private $requeteTableauBordFiltres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;  //valeur de la condition

    public function __construct()
    {
        $this->requeteTableauBordFiltres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, RequeteTableauBordFiltres>
     */
    public function getRequeteTableauBordFiltres(): Collection
    {
        return $this->requeteTableauBordFiltres;
    }

    public function addRequeteTableauBordFiltre(RequeteTableauBordFiltres $requeteTableauBordFiltre): self
    {
        if (!$this->requeteTableauBordFiltres->contains($requeteTableauBordFiltre)) {
            $this->requeteTableauBordFiltres[] = $requeteTableauBordFiltre;
            $requeteTableauBordFiltre->setTableauBordFiltreCondition($this);
        }

        return $this;
    }

    public function removeRequeteTableauBordFiltre(RequeteTableauBordFiltres $requeteTableauBordFiltre): self
    {
        if ($this->requeteTableauBordFiltres->removeElement($requeteTableauBordFiltre)) {
            // set the owning side to null (unless already changed)
            if ($requeteTableauBordFiltre->getTableauBordFiltreCondition() === $this) {
                $requeteTableauBordFiltre->setTableauBordFiltreCondition(null);
            }
        }

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getLibelleAnglais(): ?string
    {
        $conditionId = $this->id;

        switch ($conditionId) {
            case self::CONDITION_ET:
                return self::CONDITION_EN_ET;
            case self::CONDITION_OU:
                return self::CONDITION_EN_OU;
        }

        return $this->libelle;
    }
}

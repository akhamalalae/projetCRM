<?php

namespace App\Entity;

use App\Repository\TableauBordFiltresOperatorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableauBordFiltresOperatorsRepository::class)
 */
class TableauBordFiltresOperators
{
    const OPERATEUREGAL      = 1;
    const OPERATEURCONTIENT  = 2;
    const OPERATEURSUPERIEUR = 3;
    const OPERATEURINFERIEUR = 4;
    const OPERATEURDIFFERENT = 5;

    const OPERATEUR_EN_IS_NULL     = 'IS NULL';
    const OPERATEUR_EN_IS_NOT_NULL = 'IS NOT NULL';
    const OPERATEUR_EN_CONTIENT    = 'LIKE';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=RequeteTableauBordFiltres::class, mappedBy="tableau_bord_filtre_operator")
     */
    private $requeteTableauBordFiltres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

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
            $requeteTableauBordFiltre->setTableauBordFiltreOperator($this);
        }

        return $this;
    }

    public function removeRequeteTableauBordFiltre(RequeteTableauBordFiltres $requeteTableauBordFiltre): self
    {
        if ($this->requeteTableauBordFiltres->removeElement($requeteTableauBordFiltre)) {
            // set the owning side to null (unless already changed)
            if ($requeteTableauBordFiltre->getTableauBordFiltreOperator() === $this) {
                $requeteTableauBordFiltre->setTableauBordFiltreOperator(null);
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
        $operatorId = $this->id;

        if ($operatorId === self::OPERATEURCONTIENT) {
            return self::OPERATEUR_EN_CONTIENT;
        }

        return $this->libelle;
    }
}

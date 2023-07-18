<?php

namespace App\Entity;

use App\Repository\EntitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntitiesRepository::class)
 */
class Entities
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=EntitiesPropriete::class, mappedBy="entitie")
     */
    private $entitiesProprietes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=RequeteTableauBordFiltres::class, mappedBy="entitie")
     */
    private $requeteTableauBordFiltres;

    /**
     * @ORM\OneToMany(targetEntity=EntitiesPropriete::class, mappedBy="entitie_joiture")
     */
    private $entitiesProprietesJointures;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomProprieteeJointure;

    public function __construct()
    {
        $this->entitiesProprietes = new ArrayCollection();
        $this->requeteTableauBordFiltres = new ArrayCollection();
        $this->entitiesProprietesJointures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|EntitiesPropriete[]
     */
    public function getEntitiesProprietes(): Collection
    {
        return $this->entitiesProprietes;
    }

    public function addEntitiesPropriete(EntitiesPropriete $entitiesPropriete): self
    {
        if (!$this->entitiesProprietes->contains($entitiesPropriete)) {
            $this->entitiesProprietes[] = $entitiesPropriete;
            $entitiesPropriete->setEntitie($this);
        }

        return $this;
    }

    public function removeEntitiesPropriete(EntitiesPropriete $entitiesPropriete): self
    {
        if ($this->entitiesProprietes->removeElement($entitiesPropriete)) {
            // set the owning side to null (unless already changed)
            if ($entitiesPropriete->getEntitie() === $this) {
                $entitiesPropriete->setEntitie(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
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
            $requeteTableauBordFiltre->setEntitie($this);
        }

        return $this;
    }

    public function removeRequeteTableauBordFiltre(RequeteTableauBordFiltres $requeteTableauBordFiltre): self
    {
        if ($this->requeteTableauBordFiltres->removeElement($requeteTableauBordFiltre)) {
            // set the owning side to null (unless already changed)
            if ($requeteTableauBordFiltre->getEntitie() === $this) {
                $requeteTableauBordFiltre->setEntitie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EntitiesPropriete>
     */
    public function getEntitiesProprietesJointures(): Collection
    {
        return $this->entitiesProprietesJointures;
    }

    public function addEntitiesProprietesJointure(EntitiesPropriete $entitiesProprietesJointure): self
    {
        if (!$this->entitiesProprietesJointures->contains($entitiesProprietesJointure)) {
            $this->entitiesProprietesJointures[] = $entitiesProprietesJointure;
            $entitiesProprietesJointure->setEntitieJoiture($this);
        }

        return $this;
    }

    public function removeEntitiesProprietesJointure(EntitiesPropriete $entitiesProprietesJointure): self
    {
        if ($this->entitiesProprietesJointures->removeElement($entitiesProprietesJointure)) {
            // set the owning side to null (unless already changed)
            if ($entitiesProprietesJointure->getEntitieJoiture() === $this) {
                $entitiesProprietesJointure->setEntitieJoiture(null);
            }
        }

        return $this;
    }

    public function getNomProprieteeJointure(): ?string
    {
        return $this->nomProprieteeJointure;
    }

    public function setNomProprieteeJointure(?string $nomProprieteeJointure): self
    {
        $this->nomProprieteeJointure = $nomProprieteeJointure;

        return $this;
    }
}

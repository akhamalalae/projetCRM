<?php

namespace App\Entity;

use App\Repository\TypeschampsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TypeschampsRepository::class)
 */
class Typeschamps
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
    #[Groups(['formulaire'])]
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    #[Groups(['formulaire'])]
    private $ordre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelleAnglais;

    /**
     * @ORM\OneToMany(targetEntity=ChampsFormulaire::class, mappedBy="type")
     */
    private $champsFormulaire;

    /**
     * @ORM\OneToMany(targetEntity=EntitiesPropriete::class, mappedBy="typesChamps")
     */
    private $entitiesProprietes;

    public function __construct()
    {
        $this->champsFormulaire = new ArrayCollection();
        $this->entitiesProprietes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOrdre(): ?bool
    {
        return $this->ordre;
    }

    public function setOrdre(bool $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLibelleAnglais(): ?string
    {
        return $this->libelleAnglais;
    }

    public function setLibelleAnglais(string $libelleAnglais): self
    {
        $this->libelleAnglais = $libelleAnglais;

        return $this;
    }

    /**
     * @return Collection|ChampsFormulaire[]
     */
    public function getChampsFormulaire(): Collection
    {
        return $this->champsFormulaire;
    }

    public function addChampsFormulaire(ChampsFormulaire $champsFormulaire): self
    {
        if (!$this->champsFormulaire->contains($champsFormulaire)) {
            $this->champsFormulaire[] = $champsFormulaire;
            $champsFormulaire->setType($this);
        }

        return $this;
    }

    public function removeChampsFormulaire(ChampsFormulaire $champsFormulaire): self
    {
        if ($this->champsFormulaire->removeElement($champsFormulaire)) {
            // set the owning side to null (unless already changed)
            if ($champsFormulaire->getType() === $this) {
                $champsFormulaire->setType(null);
            }
        }

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
            $entitiesPropriete->setTypesChamps($this);
        }

        return $this;
    }

    public function removeEntitiesPropriete(EntitiesPropriete $entitiesPropriete): self
    {
        if ($this->entitiesProprietes->removeElement($entitiesPropriete)) {
            // set the owning side to null (unless already changed)
            if ($entitiesPropriete->getTypesChamps() === $this) {
                $entitiesPropriete->setTypesChamps(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReferentielsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReferentielsRepository::class)
 */
#[ApiResource]
class Referentiels
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
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=ReferentielsOptions::class, mappedBy="referentiels", cascade={"persist"}, orphanRemoval=true)
     */
    private $referentielsOptions;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    public function __construct()
    {
        $this->referentielsOptions = new ArrayCollection();
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
     * @return Collection|ReferentielsOptions[]
     */
    public function getReferentielsOptions(): Collection
    {
        return $this->referentielsOptions;
    }

    public function addReferentielsOption(ReferentielsOptions $referentielsOption): self
    {
        if (!$this->referentielsOptions->contains($referentielsOption)) {
            $this->referentielsOptions[] = $referentielsOption;
            $referentielsOption->setReferentiels($this);
        }

        return $this;
    }

    public function removeReferentielsOption(ReferentielsOptions $referentielsOption): self
    {
        if ($this->referentielsOptions->removeElement($referentielsOption)) {
            // set the owning side to null (unless already changed)
            if ($referentielsOption->getReferentiels() === $this) {
                $referentielsOption->setReferentiels(null);
            }
        }

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
}

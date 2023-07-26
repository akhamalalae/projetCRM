<?php

namespace App\Entity;

use App\Repository\HistoriqueGenerationAutomatiqueRoutingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoriqueGenerationAutomatiqueRoutingRepository::class)
 */
class HistoriqueGenerationAutomatiqueRouting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\ManyToMany(targetEntity=Formulaire::class, inversedBy="historiqueGenerationAutomatiqueRoutings")
     */
    private $formulaires;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ecartEnMunites;

    /**
     * @ORM\OneToMany(targetEntity=RenderVous::class, mappedBy="historiqueGenerationAutomatiqueRouting")
     */
    private $renderVous;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="historiqueGenerationAutomatiqueRoutings")
     */
    private $userCreateur;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isGenerer;

    public function __construct()
    {
        $this->formulaires = new ArrayCollection();
        $this->renderVous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

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

    /**
     * @return Collection|Formulaire[]
     */
    public function getFormulaires(): Collection
    {
        return $this->formulaires;
    }

    public function addFormulaire(Formulaire $formulaire): self
    {
        if (!$this->formulaires->contains($formulaire)) {
            $this->formulaires[] = $formulaire;
        }

        return $this;
    }

    public function removeFormulaire(Formulaire $formulaire): self
    {
        $this->formulaires->removeElement($formulaire);

        return $this;
    }

    public function getEcartEnMunites(): ?string
    {
        return $this->ecartEnMunites;
    }

    public function setEcartEnMunites(?string $ecartEnMunites): self
    {
        $this->ecartEnMunites = $ecartEnMunites;

        return $this;
    }

    /**
     * @return Collection|RenderVous[]
     */
    public function getRenderVous(): Collection
    {
        return $this->renderVous;
    }

    public function addRenderVou(RenderVous $renderVou): self
    {
        if (!$this->renderVous->contains($renderVou)) {
            $this->renderVous[] = $renderVou;
            $renderVou->setHistoriqueGenerationAutomatiqueRouting($this);
        }

        return $this;
    }

    public function removeRenderVou(RenderVous $renderVou): self
    {
        if ($this->renderVous->removeElement($renderVou)) {
            // set the owning side to null (unless already changed)
            if ($renderVou->getHistoriqueGenerationAutomatiqueRouting() === $this) {
                $renderVou->setHistoriqueGenerationAutomatiqueRouting(null);
            }
        }

        return $this;
    }

    public function getUserCreateur(): ?User
    {
        return $this->userCreateur;
    }

    public function setUserCreateur(?User $userCreateur): self
    {
        $this->userCreateur = $userCreateur;

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

    public function getIsGenerer(): ?bool
    {
        return $this->isGenerer;
    }

    public function setIsGenerer(?bool $isGenerer): self
    {
        $this->isGenerer = $isGenerer;

        return $this;
    }
}

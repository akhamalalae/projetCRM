<?php

namespace App\Entity;

use App\Repository\FormulaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FormulaireRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['formulaire:read']],
    denormalizationContext: ['groups' => ['formulaire:write']],
)]
class Formulaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="formulaire")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ChampsFormulaire::class, mappedBy="formulaire", cascade={"persist"}, orphanRemoval=true)
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $champFormulaire;

    /**
     * @ORM\OneToMany(targetEntity=EnregistrementFormulaire::class, mappedBy="formulaires")
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $enregistrement;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="intervenantsFormulaires")
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $intervenants;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=Entreprise::class, inversedBy="fromFormulaires")
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $entreprises;

    /**
     * @ORM\OneToMany(targetEntity=RenderVous::class, mappedBy="formulaire")
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $renderVous;

    /**
     * @ORM\ManyToMany(targetEntity=HistoriqueGenerationAutomatiqueRouting::class, mappedBy="formulaires")
     */
    private $historiqueGenerationAutomatiqueRoutings;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['formulaire:read', 'formulaire:write'])]
    private $dateModification;

    public function __construct()
    {
        $this->champFormulaire = new ArrayCollection();
        $this->enregistrement = new ArrayCollection();
        $this->intervenants = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
        $this->renderVous = new ArrayCollection();
        $this->historiqueGenerationAutomatiqueRoutings = new ArrayCollection();
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|ChampsFormulaire[]
     */
    public function getChampFormulaire(): Collection
    {
        return $this->champFormulaire;
    }

    public function addChampFormulaire(ChampsFormulaire $champFormulaire): self
    {
        if (!$this->champFormulaire->contains($champFormulaire)) {
            $this->champFormulaire[] = $champFormulaire;
            $champFormulaire->setFormulaire($this);
        }

        return $this;
    }

    public function removeChampFormulaire(ChampsFormulaire $champFormulaire): self
    {
        if ($this->champFormulaire->removeElement($champFormulaire)) {
            // set the owning side to null (unless already changed)
            if ($champFormulaire->getFormulaire() === $this) {
                $champFormulaire->setFormulaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EnregistrementFormulaire[]
     */
    public function getEnregistrement(): Collection
    {
        return $this->enregistrement;
    }

    public function addEnregistrement(EnregistrementFormulaire $enregistrement): self
    {
        if (!$this->enregistrement->contains($enregistrement)) {
            $this->enregistrement[] = $enregistrement;
            $enregistrement->setFormulaires($this);
        }

        return $this;
    }

    public function removeEnregistrement(EnregistrementFormulaire $enregistrement): self
    {
        if ($this->enregistrement->removeElement($enregistrement)) {
            // set the owning side to null (unless already changed)
            if ($enregistrement->getFormulaires() === $this) {
                $enregistrement->setFormulaires(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getIntervenants(): Collection
    {
        return $this->intervenants;
    }

    public function addIntervenant(User $intervenant): self
    {
        if (!$this->intervenants->contains($intervenant)) {
            $this->intervenants[] = $intervenant;
        }

        return $this;
    }

    public function removeIntervenant(User $intervenant): self
    {
        $this->intervenants->removeElement($intervenant);

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
     * @return Collection|Entreprise[]
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        $this->entreprises->removeElement($entreprise);

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
            $renderVou->setFormulaire($this);
        }

        return $this;
    }

    public function removeRenderVou(RenderVous $renderVou): self
    {
        if ($this->renderVous->removeElement($renderVou)) {
            // set the owning side to null (unless already changed)
            if ($renderVou->getFormulaire() === $this) {
                $renderVou->setFormulaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HistoriqueGenerationAutomatiqueRouting[]
     */
    public function getHistoriqueGenerationAutomatiqueRoutings(): Collection
    {
        return $this->historiqueGenerationAutomatiqueRoutings;
    }

    public function addHistoriqueGenerationAutomatiqueRouting(HistoriqueGenerationAutomatiqueRouting $historiqueGenerationAutomatiqueRouting): self
    {
        if (!$this->historiqueGenerationAutomatiqueRoutings->contains($historiqueGenerationAutomatiqueRouting)) {
            $this->historiqueGenerationAutomatiqueRoutings[] = $historiqueGenerationAutomatiqueRouting;
            $historiqueGenerationAutomatiqueRouting->addFormulaire($this);
        }

        return $this;
    }

    public function removeHistoriqueGenerationAutomatiqueRouting(HistoriqueGenerationAutomatiqueRouting $historiqueGenerationAutomatiqueRouting): self
    {
        if ($this->historiqueGenerationAutomatiqueRoutings->removeElement($historiqueGenerationAutomatiqueRouting)) {
            $historiqueGenerationAutomatiqueRouting->removeFormulaire($this);
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

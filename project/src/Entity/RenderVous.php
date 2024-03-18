<?php

namespace App\Entity;

use App\Repository\RenderVousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RenderVousRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['renderVous:read']],
    denormalizationContext: ['groups' => ['renderVous:write']],
)]
class RenderVous
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $id;

      /**
     * @ORM\Column(type="string", length=100)
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $end;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $allDay;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $backgroundColor;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $borderColor;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $textColor;

    /**
     * @ORM\ManyToOne(targetEntity=PointVente::class, inversedBy="renderVouses")
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $pointeVente;

    /**
     * @ORM\ManyToOne(targetEntity=Formulaire::class, inversedBy="renderVous")
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $formulaire;

    /**
     * @ORM\ManyToOne(targetEntity=HistoriqueGenerationAutomatiqueRouting::class, cascade={"persist"}, inversedBy="renderVous")
     */
    private $historiqueGenerationAutomatiqueRouting;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="renderVous")
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $intervenant;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="renderVousUserCreateur")
     */
    private $userCreateur;

    /**
     * @ORM\OneToMany(targetEntity=EnregistrementFormulaire::class, mappedBy="calander_rendez_vous")
     */
    private $enregistrementFormulaires;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $effectuer;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="entrepriseRendezVous")
     */
    #[Groups(['renderVous:read', 'renderVous:write'])]
    private $entreprise;

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
        $this->enregistrementFormulaires = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

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

    public function getAllDay(): ?bool
    {
        return $this->allDay;
    }

    public function setAllDay(bool $allDay): self
    {
        $this->allDay = $allDay;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->borderColor;
    }

    public function setBorderColor(string $borderColor): self
    {
        $this->borderColor = $borderColor;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function getPointeVente(): ?PointVente
    {
        return $this->pointeVente;
    }

    public function setPointeVente(?PointVente $pointeVente): self
    {
        $this->pointeVente = $pointeVente;

        return $this;
    }

    public function getFormulaire(): ?Formulaire
    {
        return $this->formulaire;
    }

    public function setFormulaire(?Formulaire $formulaire): self
    {
        $this->formulaire = $formulaire;

        return $this;
    }

    public function getHistoriqueGenerationAutomatiqueRouting(): ?HistoriqueGenerationAutomatiqueRouting
    {
        return $this->historiqueGenerationAutomatiqueRouting;
    }

    public function setHistoriqueGenerationAutomatiqueRouting(?HistoriqueGenerationAutomatiqueRouting $historiqueGenerationAutomatiqueRouting): self
    {
        $this->historiqueGenerationAutomatiqueRouting = $historiqueGenerationAutomatiqueRouting;

        return $this;
    }

    public function getIntervenant(): ?User
    {
        return $this->intervenant;
    }

    public function setIntervenant(?User $intervenant): self
    {
        $this->intervenant = $intervenant;

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

    /**
     * @return Collection|EnregistrementFormulaire[]
     */
    public function getEnregistrementFormulaires(): Collection
    {
        return $this->enregistrementFormulaires;
    }

    public function addEnregistrementFormulaire(EnregistrementFormulaire $enregistrementFormulaire): self
    {
        if (!$this->enregistrementFormulaires->contains($enregistrementFormulaire)) {
            $this->enregistrementFormulaires[] = $enregistrementFormulaire;
            $enregistrementFormulaire->setCalanderRendezVous($this);
        }

        return $this;
    }

    public function removeEnregistrementFormulaire(EnregistrementFormulaire $enregistrementFormulaire): self
    {
        if ($this->enregistrementFormulaires->removeElement($enregistrementFormulaire)) {
            // set the owning side to null (unless already changed)
            if ($enregistrementFormulaire->getCalanderRendezVous() === $this) {
                $enregistrementFormulaire->setCalanderRendezVous(null);
            }
        }

        return $this;
    }

    public function getEffectuer(): ?bool
    {
        return $this->effectuer;
    }

    public function setEffectuer(?bool $effectuer): self
    {
        $this->effectuer = $effectuer;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

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

<?php

namespace App\Entity;

use App\Repository\ChampsFormulaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ChampsFormulaireRepository::class)
 */
class ChampsFormulaire
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
    #[Groups(['formulaire:read'])]
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['formulaire:read'])]
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    #[Groups(['formulaire:read'])]
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Formulaire::class, inversedBy="champFormulaire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formulaire;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['formulaire:read'])]
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['formulaire:read'])]
    private $dateModification;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['formulaire:read'])]
    private $ordre;

    /**
     * @ORM\ManyToOne(targetEntity=Typeschamps::class, inversedBy="champsFormulaire", cascade={"persist"})
     */
    #[Groups(['formulaire:read'])]
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Options::class, mappedBy="champsFormulaire", cascade={"persist"}, orphanRemoval=true)
     */
    #[Groups(['formulaire:read'])]
    private $options;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiels::class)
     */
    #[Groups(['formulaire:read'])]
    private $referentiels;

    /**
     * @ORM\OneToMany(targetEntity=Files::class, mappedBy="champsFormulaire")
     */
    #[Groups(['formulaire:read'])]
    private $files;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->status = 0;
        $this->ordre = 0;
        $this->dateCreation = new DateTime();
        $this->dateModification = new DateTime();
        $this->files = new ArrayCollection();
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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getType(): ?Typeschamps
    {
        return $this->type;
    }

    public function setType(?Typeschamps $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Options[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Options $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->setChampsFormulaire($this);
        }

        return $this;
    }

    public function removeOption(Options $option): self
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getChampsFormulaire() === $this) {
                $option->setChampsFormulaire(null);
            }
        }

        return $this;
    }

    public function getReferentiels(): ?Referentiels
    {
        return $this->referentiels;
    }

    public function setReferentiels(?Referentiels $referentiels): self
    {
        $this->referentiels = $referentiels;

        return $this;
    }

    /**
     * @return Collection|Files[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Files $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setChampsFormulaire($this);
        }

        return $this;
    }

    public function removeFile(Files $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getChampsFormulaire() === $this) {
                $file->setChampsFormulaire(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }

}

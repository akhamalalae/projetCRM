<?php

namespace App\Entity;

use App\Repository\FilesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FilesRepository::class)
 */
class Files
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="file", type="string", length=255, unique=true, nullable=true)
     */
    private $file;

    /**
     * @ORM\ManyToMany(targetEntity=EnregistrementFormulaire::class, mappedBy="files")
     */
    private $enregistrementFormulaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=ChampsFormulaire::class, inversedBy="files")
     */
    private $champsFormulaire;

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
        $this->enregistrementFormulaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return Collection|EnregistrementFormulaire[]
     */
    public function getEnregistrementFormulaire(): Collection
    {
        return $this->enregistrementFormulaire;
    }

    public function addEnregistrementFormulaire(EnregistrementFormulaire $enregistrementFormulaire): self
    {
        if (!$this->enregistrementFormulaire->contains($enregistrementFormulaire)) {
            $this->enregistrementFormulaire[] = $enregistrementFormulaire;
            $enregistrementFormulaire->addFile($this);
        }

        return $this;
    }

    public function removeEnregistrementFormulaire(EnregistrementFormulaire $enregistrementFormulaire): self
    {
        if ($this->enregistrementFormulaire->removeElement($enregistrementFormulaire)) {
            $enregistrementFormulaire->removeFile($this);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getChampsFormulaire(): ?ChampsFormulaire
    {
        return $this->champsFormulaire;
    }

    public function setChampsFormulaire(?ChampsFormulaire $champsFormulaire): self
    {
        $this->champsFormulaire = $champsFormulaire;

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

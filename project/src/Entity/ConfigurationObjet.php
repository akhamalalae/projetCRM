<?php

namespace App\Entity;

use App\Repository\ConfigurationObjetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigurationObjetRepository::class)
 */
class ConfigurationObjet
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
     * @ORM\ManyToOne(targetEntity=ConfigurationEspace::class, inversedBy="configurationObjets")
     */
    private $configurationEspace;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

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

    public function getConfigurationEspace(): ?ConfigurationEspace
    {
        return $this->configurationEspace;
    }

    public function setConfigurationEspace(?ConfigurationEspace $configurationEspace): self
    {
        $this->configurationEspace = $configurationEspace;

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

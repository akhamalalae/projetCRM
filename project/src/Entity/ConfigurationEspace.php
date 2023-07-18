<?php

namespace App\Entity;

use App\Repository\ConfigurationEspaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigurationEspaceRepository::class)
 */
class ConfigurationEspace
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $x;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $y;

    /**
     * @ORM\ManyToOne(targetEntity=PointVente::class, inversedBy="color")
     */
    private $pointVente;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=ConfigurationObjet::class, mappedBy="configurationEspace")
     */
    private $configurationObjets;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    public function __construct()
    {
        $this->configurationObjets = new ArrayCollection();
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

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(?int $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(?int $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getPointVente(): ?PointVente
    {
        return $this->pointVente;
    }

    public function setPointVente(?PointVente $pointVente): self
    {
        $this->pointVente = $pointVente;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, ConfigurationObjet>
     */
    public function getConfigurationObjets(): Collection
    {
        return $this->configurationObjets;
    }

    public function addConfigurationObjet(ConfigurationObjet $configurationObjet): self
    {
        if (!$this->configurationObjets->contains($configurationObjet)) {
            $this->configurationObjets[] = $configurationObjet;
            $configurationObjet->setConfigurationEspace($this);
        }

        return $this;
    }

    public function removeConfigurationObjet(ConfigurationObjet $configurationObjet): self
    {
        if ($this->configurationObjets->removeElement($configurationObjet)) {
            // set the owning side to null (unless already changed)
            if ($configurationObjet->getConfigurationEspace() === $this) {
                $configurationObjet->setConfigurationEspace(null);
            }
        }

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}

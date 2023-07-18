<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PointVenteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Generale\Adresse;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PointVenteRepository::class)
 */
#[ApiResource(
)]
class PointVente extends Adresse
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=RenderVous::class, mappedBy="pointeVente")
     */
    private $renderVouses;

    /**
     * @ORM\ManyToMany(targetEntity=Entreprise::class, inversedBy="pointVentes")
     */
    private $entreprises;

    /**
     * @ORM\OneToMany(targetEntity=ConfigurationEspace::class, mappedBy="pointVente")
     */
    private $configurationEspaces;

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
        $this->renderVouses = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
        $this->configurationEspaces = new ArrayCollection();
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
     * @return Collection|RenderVous[]
     */
    public function getRenderVouses(): Collection
    {
        return $this->renderVouses;
    }

    public function addRenderVouse(RenderVous $renderVouse): self
    {
        if (!$this->renderVouses->contains($renderVouse)) {
            $this->renderVouses[] = $renderVouse;
            $renderVouse->setPointeVente($this);
        }

        return $this;
    }

    public function removeRenderVouse(RenderVous $renderVouse): self
    {
        if ($this->renderVouses->removeElement($renderVouse)) {
            // set the owning side to null (unless already changed)
            if ($renderVouse->getPointeVente() === $this) {
                $renderVouse->setPointeVente(null);
            }
        }

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
     * @return Collection<int, ConfigurationEspace>
     */
    public function getConfigurationEspaces(): Collection
    {
        return $this->configurationEspaces;
    }

    public function addConfigurationEspaces(ConfigurationEspace $configurationEspaces): self
    {
        if (!$this->configurationEspaces->contains($configurationEspaces)) {
            $this->configurationEspaces[] = $configurationEspaces;
            $configurationEspaces->setPointVente($this);
        }

        return $this;
    }

    public function removeConfigurationEspaces(ConfigurationEspace $configurationEspaces): self
    {
        if ($this->configurationEspaces->removeElement($configurationEspaces)) {
            // set the owning side to null (unless already changed)
            if ($configurationEspaces->getPointVente() === $this) {
                $configurationEspaces->setPointVente(null);
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

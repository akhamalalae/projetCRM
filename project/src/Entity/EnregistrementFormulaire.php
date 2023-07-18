<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EnregistrementFormulaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EnregistrementFormulaireRepository::class)
 */
#[ApiResource(
    normalizationContext:['groups'=> 'enregistrementFormulaire'],
)]
class EnregistrementFormulaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['enregistrementFormulaire'])]
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(['enregistrementFormulaire'])]
    private $resultats = [];

    /**
     * @ORM\ManyToMany(targetEntity=Files::class, inversedBy="enregistrementFormulaire", cascade={"persist"})
     */
    private $files;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $intervenant;

     /**
     * @ORM\ManyToOne(targetEntity=Formulaire::class, inversedBy="enregistrement")
     */
    #[Groups(['enregistrementFormulaire'])]
    private $formulaires;

    /**
     * @ORM\ManyToOne(targetEntity=RenderVous::class, inversedBy="enregistrementFormulaires")
     */
    private $calander_rendez_vous;

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
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormulaires(): ?Formulaire
    {
        return $this->formulaires;
    }

    public function setFormulaires(?Formulaire $formulaires): self
    {
        $this->formulaires = $formulaires;

        return $this;
    }

    public function getResultats(): ?array
    {
        return $this->resultats;
    }

    public function setResultats(array $resultats): self
    {
        $this->resultats = $resultats;

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
        }

        return $this;
    }

    public function removeFile(Files $file): self
    {
        $this->files->removeElement($file);

        return $this;
    }

    public function getCalanderRendezVous(): ?RenderVous
    {
        return $this->calander_rendez_vous;
    }

    public function setCalanderRendezVous(?RenderVous $calander_rendez_vous): self
    {
        $this->calander_rendez_vous = $calander_rendez_vous;

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
